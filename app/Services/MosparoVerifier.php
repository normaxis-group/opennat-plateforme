<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Mosparo\ApiClient\Client as MosparoClient;
use Mosparo\ApiClient\Exception as MosparoException;

/**
 * Wrapper Laravel autour du SDK officiel mosparo/php-api-client.
 *
 * Pourquoi ne pas faire un Guzzle maison ? Parce que la signature HMAC-SHA256
 * de Mosparo est non-triviale (canonicalisation des champs, hash du payload,
 * concat avec le hash du formData...). Le SDK officiel est maintenu, audité
 * et résiste aux changements d'API. On gagne 0 sécurité à le réimplémenter.
 *
 * Responsabilités :
 *   - Respecter le kill-switch config('mosparo.enabled')
 *   - Filtrer les champs à ignorer dans la signature (CSRF, honeypot...)
 *   - Logguer chaque échec en warning (avec error_message Mosparo)
 *   - Décider du comportement quand l'API est down (fail-open / fail-closed)
 *
 * Usage :
 *   $verifier->verify(
 *       submitToken:    $request->input('_mosparo_submitToken'),
 *       validationToken: $request->input('_mosparo_validationToken'),
 *       formData:       $request->except(['_token', 'company_url_secondary', '_mosparo_*']),
 *   );  // → bool
 */
class MosparoVerifier
{
    public function __construct(
        protected ?MosparoClient $client = null,
    ) {}

    /**
     * Vérifie une soumission auprès de Mosparo.
     *
     * @param  string|null  $submitToken  Le _mosparo_submitToken posé par le widget JS.
     * @param  string|null  $validationToken  Le _mosparo_validationToken posé par le widget JS.
     * @param  array  $formData  Les champs du formulaire à signer (sans tokens / CSRF / honeypot).
     * @return bool True si la soumission est valide (à enregistrer), false sinon.
     */
    public function verify(?string $submitToken, ?string $validationToken, array $formData): bool
    {
        // Kill-switch global : on bypass complètement Mosparo en cas d'incident.
        if (! config('mosparo.enabled')) {
            return true;
        }

        // Tokens manquants = soumission directe sans passer par le widget JS.
        // C'est typiquement un bot qui poste en POST direct → on rejette.
        if (! filled($submitToken) || ! filled($validationToken)) {
            Log::info('Mosparo : soumission rejetée (tokens absents)', [
                'has_submit' => filled($submitToken),
                'has_validation' => filled($validationToken),
            ]);

            return false;
        }

        // Configuration absente / incomplète = anomalie de déploiement.
        // On loggue en error et on choisit le comportement on_failure.
        if (! filled(config('mosparo.public_key')) || ! filled(config('mosparo.private_key'))) {
            Log::error('Mosparo : MOSPARO_PUBLIC_KEY ou MOSPARO_PRIVATE_KEY manquant dans .env');

            return $this->failOpen();
        }

        try {
            // buildClient() est DANS le try : si le SDK n'est pas installé
            // (composer install pas fait après déploiement), `new MosparoClient`
            // throw "Class not found" → catché par \Throwable et fail-open.
            $client = $this->client ?? $this->buildClient();

            $response = $client->verifySubmission(
                $this->cleanFormData($formData),
                $submitToken,
                $validationToken,
            );

            if ($response->isSubmittable()) {
                return true;
            }

            // Soumission rejetée par Mosparo (signature invalide, déjà
            // utilisée, expired, IP blacklistée par auto-lockout, etc.)
            Log::warning('Mosparo : soumission refusée', [
                'is_valid' => $response->isValid(),
                'verified_fields' => method_exists($response, 'getVerifiedFields') ? $response->getVerifiedFields() : null,
                'issues' => method_exists($response, 'getIssues') ? $response->getIssues() : null,
            ]);

            return false;

        } catch (MosparoException $e) {
            // Erreur HTTP / réseau / signature côté Mosparo. On suit la
            // policy on_failure pour décider entre fail-open et fail-closed.
            Log::warning('Mosparo : exception API → '.$e->getMessage());

            return $this->failOpen();
        } catch (\Throwable $e) {
            // Imprévu (SDK pas installé ? config corrompue ?). Idem.
            Log::error('Mosparo : exception inattendue → '.$e->getMessage());

            return $this->failOpen();
        }
    }

    /**
     * Retourne true si on doit laisser passer (fail-open) en cas de pépin
     * Mosparo, false sinon. Configuré via MOSPARO_ON_FAILURE.
     */
    protected function failOpen(): bool
    {
        return config('mosparo.on_failure', 'allow') === 'allow';
    }

    /**
     * Construit le client SDK Mosparo. Isolé pour permettre l'injection
     * d'un mock dans les tests Feature.
     *
     * Signature officielle (vendor/mosparo/php-api-client/src/Client.php) :
     *   __construct(string $host, string $publicKey, string $privateKey, array $clientArguments = [])
     *
     * Le 4ème paramètre est un tableau d'options Guzzle (verify SSL, proxy,
     * timeout, etc.). En local on désactive verify SSL pour permettre les
     * certs auto-signés Laragon ; en prod on garde la vérification.
     */
    protected function buildClient(): MosparoClient
    {
        return new MosparoClient(
            config('mosparo.host'),
            config('mosparo.public_key'),
            config('mosparo.private_key'),
            ['verify' => config('app.env') !== 'local'],
        );
    }

    /**
     * Retire des donnees a signer ce que le widget ne signe pas : jetons
     * _mosparo_*, champs de config('mosparo.ignored_fields') (CSRF, pot de miel...).
     */
    protected function cleanFormData(array $formData): array
    {
        $ignored = config('mosparo.ignored_fields', []);
        $propre = [];
        foreach ($formData as $cle => $valeur) {
            if (str_starts_with((string) $cle, '_mosparo_') || in_array($cle, $ignored, true)) {
                continue;
            }
            $valeur = $this->normaliser($valeur);
            if ($valeur !== null) {
                $propre[$cle] = $valeur;
            }
        }

        return $propre;
    }

    /**
     * LES TABLEAUX RESTENT DES TABLEAUX. Le widget signe contacts[demandeur][prenom]
     * champ par champ, et le SDK sait parcourir un tableau imbrique pour produire
     * la meme structure. L'ancien implode(',') ecrasait ces champs en une chaine que
     * mosparo ne pouvait jamais reconnaitre : le formulaire BREEAM d'irice etait
     * refuse a coup sur (diagnostic du 26/09/2026 sur les soumissions mosparo).
     *
     * Les fichiers ne sont jamais signes par le widget : on les retire. Le reste est
     * converti en chaine, null devenant '' comme le fait le widget.
     */
    protected function normaliser(mixed $valeur): array|string|null
    {
        if ($valeur instanceof UploadedFile) {
            return null;
        }
        if (is_array($valeur)) {
            $sortie = [];
            foreach ($valeur as $cle => $element) {
                $element = $this->normaliser($element);
                if ($element !== null) {
                    $sortie[$cle] = $element;
                }
            }

            return $sortie;
        }

        return (string) ($valeur ?? '');
    }
}

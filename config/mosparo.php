<?php

/*
|--------------------------------------------------------------------------
| Mosparo anti-spam — config commune aux 5 sites NORMAXIS
|--------------------------------------------------------------------------
|
| Mosparo est l'instance auto-hébergée sur https://spam.normaxis.group qui
| valide chaque soumission de formulaire public via :
|   1) un widget JS frontend (checkbox "I am not a robot" + proof of work)
|   2) une vérification serveur-à-serveur signée HMAC-SHA256 côté backend
|
| Chaque site a son propre PROJET Mosparo (= sa propre paire de clés). La
| valeur de MOSPARO_PUBLIC_KEY / MOSPARO_PRIVATE_KEY change donc d'un site
| à l'autre, mais ce fichier de config reste identique partout.
|
| La clé publique est exposée dans le HTML (c'est son rôle) ; la clé privée
| ne quitte JAMAIS le backend Laravel — elle sert à signer les requêtes
| de vérification HTTP côté serveur.
|
| Désactivation rapide en cas d'incident (Mosparo down, faux positifs sur
| un volume important, etc.) :
|   MOSPARO_ENABLED=false dans le .env du site
|   → le middleware VerifyMosparo laisse passer toutes les soumissions
|     (le honeypot et la captcha intégrée au navigateur restent actifs).
|     Aucun blocage UX côté visiteur.
*/

return [

    /**
     * URL de base de l'instance Mosparo (sans slash final). Override possible
     * en local (http://spam.normaxis.test) via .env.
     */
    'host' => rtrim(env('MOSPARO_HOST', 'https://spam.normaxis.group'), '/'),

    /**
     * UUID du projet Mosparo. Visible publiquement (injecté dans le widget
     * JS frontend). Sert à identifier le projet côté Mosparo. À récupérer
     * dans Mosparo → Settings du projet → champ "UUID".
     *
     * NOTE : seul le widget JS a besoin de l'UUID. Le SDK PHP de
     * vérification backend (mosparo/php-api-client) utilise uniquement
     * public_key + private_key et n'en a pas besoin.
     */
    'project_uuid' => env('MOSPARO_PROJECT_UUID'),

    /**
     * Clé publique du projet Mosparo. Visible publiquement (injectée dans
     * le HTML via le composant <x-mosparo-widget />). Identifie le projet
     * côté Mosparo et autorise la création d'un submitToken.
     */
    'public_key' => env('MOSPARO_PUBLIC_KEY'),

    /**
     * Clé privée du projet Mosparo. SECRÈTE — utilisée uniquement par le
     * service MosparoVerifier pour signer les requêtes de vérification.
     * NE JAMAIS commiter en clair, NE JAMAIS injecter en JS.
     */
    'private_key' => env('MOSPARO_PRIVATE_KEY'),

    /**
     * Kill-switch global. À false, le middleware VerifyMosparo bypasse la
     * vérification : utile en cas d'incident sur spam.normaxis.group ou
     * en local dev pour ne pas avoir à configurer Mosparo. Le honeypot
     * existant (company_url_secondary) reste actif comme défense.
     */
    'enabled' => filter_var(env('MOSPARO_ENABLED', true), FILTER_VALIDATE_BOOLEAN),

    /**
     * Timeout HTTP du POST de vérification (en secondes). Court, pour ne
     * pas faire patienter l'utilisateur si Mosparo rame.
     */
    'http_timeout' => (int) env('MOSPARO_TIMEOUT', 5),

    /**
     * Comportement quand l'API Mosparo ne répond pas (timeout / 5xx).
     *
     * - 'allow' : on laisse passer la soumission (fail-open). Choix
     *   recommandé pour des leads à forte valeur — mieux vaut un faux
     *   négatif spam qu'un vrai client perdu.
     * - 'block' : on rejette la soumission (fail-closed). À privilégier
     *   si on observe des attaques massives.
     *
     * Dans tous les cas, l'incident est loggué en warning.
     */
    'on_failure' => env('MOSPARO_ON_FAILURE', 'allow'),

    /**
     * Nom des champs cachés posés par le widget JS Mosparo dans le form.
     * Utilisés à la fois côté Blade (pour ne pas les valider Laravel) et
     * côté Verifier (pour les extraire de la Request avant la vérif).
     */
    'fields' => [
        'submit_token' => '_mosparo_submitToken',
        'validation_token' => '_mosparo_validationToken',
    ],

    /**
     * Champs à EXCLURE de la signature backend, car Mosparo (côté frontend)
     * les exclut aussi de la signature. La règle Mosparo est :
     *   "le widget JS ignore automatiquement les types hidden, checkbox,
     *    radio, password et file" (cf. doc Custom integration → Ignored
     *    fields).
     *
     * Si on inclut côté backend un champ que le frontend a ignoré, la
     * signature ne match pas → Mosparo répond "valid: false" → l'utilisateur
     * voit "La vérification anti-spam a échoué" alors que tout va bien.
     *
     * → Maintenir ici la liste de TOUS les champs hidden / checkbox / radio
     *   / password présents dans les 3 formulaires IRICE (contact, breeam,
     *   formations).
     *
     * Astuce : si un nouveau champ hidden est ajouté à un form sans être
     * listé ici, le formulaire commencera à renvoyer "anti-spam invalide"
     * en prod. Le réflexe : ajouter le champ ici et `php artisan config:clear`.
     */
    'ignored_fields' => [
        // ── Hidden inputs (auto-ignorés par le widget Mosparo frontend) ──
        // Cf. doc Mosparo Custom integration → Ignored fields :
        //   "mosparo does not validate field types like checkbox, radio,
        //    password, and hidden."
        '_token',                   // CSRF Laravel (hidden)
        'source_page',              // hidden, page source du lead
        'utm_source',               // hidden, tracking marketing
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'programme',                // hidden, programme demandé (BREEAM)

        // ── Checkboxes (auto-ignorées par le widget Mosparo frontend) ──
        'rgpd_consent',             // consentement RGPD (présent partout)

        // NOTE : ne PAS ajouter ici 'company_url_secondary'.
        // Le honeypot est en type="text" (caché en CSS), donc Mosparo
        // frontend le SIGNE comme un champ normal. Si on le filtre ici,
        // mismatch de signature → "La vérification anti-spam a échoué".
        // Pour humains la valeur est "" → signatures égales, OK.
        // Pour bots qui n'exécutent pas le JS Mosparo : pas de submitToken
        // → bloqués par le middleware avant même la vérif signature.

        // Si tu ajoutes un nouveau champ hidden ou checkbox dans un form,
        // pense à l'ajouter ici. Sinon → "anti-spam invalide" mystérieux.
    ],

];

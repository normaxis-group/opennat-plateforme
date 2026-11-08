<?php

namespace App\Http\Controllers;

use App\Models\Calcul;
use App\Services\PageMoteur;
use App\Services\PointsAccesMoteur;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response as ReponseMoteur;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

/**
 * Relais vers le moteur OpenNAT (utilitaire en mode public), joint sur le réseau interne et jamais exposé.
 *
 * La page de l'outil appelle /statique/* et /api/* : ces routes passent par ici, derrière la connexion d'un compte
 * validé. Chaque calcul lancé est rattaché au compte (table calculs) et seul son propriétaire en ouvre l'état, les
 * résultats et les fichiers ; le moteur reçoit l'identifiant du compte comme adresse du client, ce qui borne à un
 * calcul simultané par compte.
 */
class OutilController extends Controller
{
    public function page(Request $request): Response
    {
        $r = $this->moteur($request, 'GET', '/');
        $html = (new PageMoteur)->adapter($r->body(), csrf_token(), (int) config('opennat.conservation_heures'), route('espace'));

        return response($html, $r->status(), ['Content-Type' => 'text/html; charset=utf-8', 'Cache-Control' => 'no-store']);
    }

    public function statique(Request $request, string $chemin): Response
    {
        $r = $this->moteur($request, 'GET', '/statique/'.$chemin);

        return response($r->body(), $r->status(), [
            'Content-Type' => $r->header('Content-Type') ?: 'application/octet-stream',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    public function api(Request $request, string $chemin): Response
    {
        $acces = new PointsAccesMoteur(explode('/', trim($chemin, '/')));
        $user = $request->user();
        $corps = null;

        if ($acces->porteUnCalcul()) {
            abort_unless($this->appartient($acces->identifiantCalcul(), $user->id), 404);
        } elseif ($acces->estCreation()) {
            $corps = json_decode($request->getContent(), true) ?: [];
            $reprise = $corps['reprendre'] ?? null;
            abort_if($reprise && ! $this->appartient($reprise, $user->id), 404);
        } elseif (! $acces->estLibre()) {
            abort(404);
        }

        $r = $this->moteur($request, $request->method(), $acces->chemin(), true);

        if ($acces->estCreation() && $request->isMethod('POST') && $r->successful()) {
            Calcul::create([
                'user_id' => $user->id,
                'moteur_id' => (string) $r->json('calcul'),
                'projet_identifiant' => mb_substr((string) data_get($corps, 'projet.projet.identifiant'), 0, 160) ?: null,
                'projet_nom' => mb_substr((string) data_get($corps, 'projet.projet.nom'), 0, 255) ?: null,
                'tirages' => is_numeric($corps['tirages'] ?? null) ? (int) $corps['tirages'] : null,
            ]);
        } elseif ($acces->estSuivi() && $request->isMethod('GET') && $r->successful()) {
            $etat = (string) $r->json('etat');
            if ($etat !== '' && $etat !== 'en_cours') {
                Calcul::where('moteur_id', $acces->identifiantCalcul())->where('etat', '!=', $etat)->update(['etat' => $etat]);
            }
        }

        $entetes = ['Content-Type' => $r->header('Content-Type') ?: 'application/json', 'Cache-Control' => 'no-store'];
        if ($r->header('Content-Disposition')) {
            $entetes['Content-Disposition'] = $r->header('Content-Disposition');
        }

        return response($r->body(), $r->status(), $entetes);
    }

    private function appartient(mixed $moteurId, int $userId): bool
    {
        return Calcul::where('moteur_id', $moteurId)->where('user_id', $userId)->exists();
    }

    private function moteur(Request $request, string $methode, string $chemin, bool $avecCorps = false): ReponseMoteur
    {
        $url = config('opennat.moteur_url').$chemin.($request->getQueryString() ? '?'.$request->getQueryString() : '');
        $client = Http::timeout(config('opennat.moteur_delai'))
            ->withHeaders(['X-Forwarded-For' => 'compte-'.$request->user()->id]);

        try {
            if ($avecCorps && ! in_array($methode, ['GET', 'HEAD'], true)) {
                return $client->withBody($request->getContent(), $request->header('Content-Type') ?: 'application/octet-stream')
                    ->send($methode, $url);
            }

            return $client->send($methode, $url);
        } catch (ConnectionException) {
            abort(503, 'Le moteur de calcul ne répond pas. Réessayez dans quelques minutes.');
        }
    }
}

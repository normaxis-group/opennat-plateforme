<?php

namespace App\Http\Controllers;

use App\Models\Calcul;
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
    /** Points d'accès qui portent l'identifiant d'un calcul en deuxième segment. */
    private const PAR_CALCUL = ['calcul', 'resultats', 'fichiers', 'fichier', 'rsnat', 'fiche'];

    /** Points d'accès sans calcul, ouverts à tout compte validé. */
    private const LIBRES = ['referentiel', 'exemple', 'valider', 'rsee', 'materiaux'];

    public function page(Request $request): Response
    {
        $r = $this->moteur($request, 'GET', '/');
        $html = $r->body();

        // La mention de la version anonyme du moteur ne vaut pas ici : la plateforme a des comptes.
        $html = preg_replace('#<p class="edition">Version en ligne.*?</p>#s', '', $html);
        $note = '<p class="edition">Vos projets et leurs résultats sont effacés du serveur '
            .(int) config('opennat.conservation_heures').' heures après le calcul : enregistrez votre fichier projet '
            .'et vos résultats sur votre poste.</p>';
        $html = str_replace('</aside>', $note.'</aside>', $html);
        $html = str_replace('</head>', '<meta name="csrf-token" content="'.e(csrf_token()).'">'."\n</head>", $html);
        $html = str_replace('<script src="/statique/app.js"></script>',
            '<script src="/outil-csrf.js"></script>'."\n".'<script src="/statique/app.js"></script>', $html);
        $html = str_replace('<div class="actions">', '<div class="actions"><a href="'.e(route('espace'))
            .'" style="color:#fff;text-decoration:none;margin-right:12px;font-size:14px">Mon espace</a>', $html);

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
        $parts = explode('/', trim($chemin, '/'));
        $point = $parts[0] ?? '';
        $user = $request->user();

        if (in_array($point, self::PAR_CALCUL, true) && count($parts) >= 2) {
            $id = explode('.', $parts[1])[0];
            abort_unless(Calcul::where('moteur_id', $id)->where('user_id', $user->id)->exists(), 404);
        } elseif ($point === 'calcul' && count($parts) === 1) {
            $corps = json_decode($request->getContent(), true) ?: [];
            $reprise = $corps['reprendre'] ?? null;
            abort_if($reprise && ! Calcul::where('moteur_id', $reprise)->where('user_id', $user->id)->exists(), 404);
        } elseif (! in_array($point, self::LIBRES, true)) {
            abort(404);
        }

        $r = $this->moteur($request, $request->method(), '/api/'.implode('/', $parts), true);

        if ($point === 'calcul' && count($parts) === 1 && $request->isMethod('POST') && $r->successful()) {
            $corps = json_decode($request->getContent(), true) ?: [];
            Calcul::create([
                'user_id' => $user->id,
                'moteur_id' => (string) $r->json('calcul'),
                'projet_identifiant' => mb_substr((string) data_get($corps, 'projet.projet.identifiant'), 0, 160) ?: null,
                'projet_nom' => mb_substr((string) data_get($corps, 'projet.projet.nom'), 0, 255) ?: null,
                'tirages' => is_numeric($corps['tirages'] ?? null) ? (int) $corps['tirages'] : null,
            ]);
        } elseif ($point === 'calcul' && count($parts) === 2 && $request->isMethod('GET') && $r->successful()) {
            $etat = (string) $r->json('etat');
            if ($etat !== '' && $etat !== 'en_cours') {
                Calcul::where('moteur_id', $parts[1])->where('etat', '!=', $etat)->update(['etat' => $etat]);
            }
        }

        $entetes = ['Content-Type' => $r->header('Content-Type') ?: 'application/json', 'Cache-Control' => 'no-store'];
        if ($r->header('Content-Disposition')) {
            $entetes['Content-Disposition'] = $r->header('Content-Disposition');
        }

        return response($r->body(), $r->status(), $entetes);
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

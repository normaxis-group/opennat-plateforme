<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;
use League\CommonMark\GithubFlavoredMarkdownConverter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Recueil de la méthode (zone connectée) : textes du dépôt OpenNAT copiés dans resources/recueil par
 * `php artisan opennat:recueil`, rendus du Markdown en HTML avec un sommaire, et leurs PDF.
 */
class RecueilController extends Controller
{
    public static function dossier(): string
    {
        return resource_path('recueil');
    }

    public function index(): View
    {
        $documents = collect(config('opennat.recueil'))
            ->filter(fn (array $d) => is_file(self::dossier().'/'.$d['fichier']));

        return view('recueil.index', ['documents' => $documents]);
    }

    public function show(string $document): View
    {
        $d = config("opennat.recueil.$document");
        $chemin = $d ? self::dossier().'/'.$d['fichier'] : null;
        abort_unless($chemin && is_file($chemin), 404);

        [$html, $sommaire] = Cache::remember('recueil:'.$document.':'.filemtime($chemin), now()->addDay(),
            fn () => $this->rendre((string) file_get_contents($chemin)));

        return view('recueil.document', [
            'cle' => $document, 'document' => $d, 'html' => $html, 'sommaire' => $sommaire,
            'pdf' => isset($d['pdf']) && is_file(self::dossier().'/pdf/'.$d['pdf']),
        ]);
    }

    public function pdf(string $document): BinaryFileResponse
    {
        $d = config("opennat.recueil.$document");
        $chemin = isset($d['pdf']) ? self::dossier().'/pdf/'.$d['pdf'] : null;
        abort_unless($chemin && is_file($chemin), 404);

        return response()->download($chemin, $d['pdf'], ['Content-Type' => 'application/pdf']);
    }

    /**
     * Markdown vers HTML (tableaux GFM, HTML brut échappé, liens non sûrs retirés), identifiants sur les titres
     * de niveaux 2 et 3, sommaire des titres de niveau 2.
     *
     * @return array{0: string, 1: list<array{id: string, titre: string}>}
     */
    private function rendre(string $markdown): array
    {
        $html = (string) (new GithubFlavoredMarkdownConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]))->convert($markdown);

        $sommaire = [];
        $vus = [];
        $html = preg_replace_callback('#<h([23])>(.*?)</h\1>#s', function (array $m) use (&$sommaire, &$vus) {
            $texte = trim(strip_tags($m[2]));
            $id = Str::slug($texte) ?: 'section';
            $base = $id;
            for ($i = 2; isset($vus[$id]); $i++) {
                $id = $base.'-'.$i;
            }
            $vus[$id] = true;
            if ($m[1] === '2') {
                $sommaire[] = ['id' => $id, 'titre' => html_entity_decode($texte)];
            }

            return '<h'.$m[1].' id="'.$id.'">'.$m[2].'</h'.$m[1].'>';
        }, $html);

        return [$html, $sommaire];
    }
}

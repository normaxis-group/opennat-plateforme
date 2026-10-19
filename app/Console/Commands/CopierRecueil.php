<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

/**
 * Copie le recueil de la méthode depuis le dépôt public OpenNAT (textes Markdown et PDF) dans resources/recueil.
 * À relancer à chaque nouvelle version de la méthode, puis à committer : l'image Coolify sert ce qui est dans le dépôt.
 */
class CopierRecueil extends Command
{
    protected $signature = 'opennat:recueil {--revision= : branche, étiquette ou commit du dépôt OpenNAT}';

    protected $description = 'Copie le recueil de la méthode depuis le dépôt OpenNAT';

    public function handle(): int
    {
        $revision = $this->option('revision') ?: config('opennat.recueil_revision');
        $base = config('opennat.recueil_depot_brut').'/'.$revision.'/';
        $dossier = resource_path('recueil');
        File::ensureDirectoryExists($dossier.'/pdf');

        foreach (config('opennat.recueil') as $cle => $d) {
            foreach ([[$d['source'], $dossier.'/'.$d['fichier']], [$d['pdf_source'] ?? null, isset($d['pdf']) ? $dossier.'/pdf/'.$d['pdf'] : null]] as [$source, $cible]) {
                if (! $source || ! $cible) {
                    continue;
                }
                $r = Http::timeout(120)->get($base.implode('/', array_map('rawurlencode', explode('/', $source))));
                if (! $r->successful()) {
                    $this->error("$cle : $source introuvable ({$r->status()})");

                    return self::FAILURE;
                }
                File::put($cible, $r->body());
                $this->line(sprintf('%-20s %s (%d octets)', $cle, basename($cible), strlen($r->body())));
            }
        }
        File::put($dossier.'/REVISION', $revision.PHP_EOL);
        $this->info("Recueil copié depuis la révision $revision.");

        return self::SUCCESS;
    }
}

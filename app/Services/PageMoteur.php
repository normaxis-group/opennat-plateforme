<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Retouche de la page du moteur pour la plateforme. Chaque retouche s'appuie sur un morceau exact du HTML du moteur
 * (autre dépôt) : si l'un manque, la retouche n'a pas lieu et un avertissement est écrit au journal.
 */
class PageMoteur
{
    public const MENTION_ANONYME = '#<p class="edition">Version en ligne.*?</p>#s';

    public const FIN_ENTETE = '</head>';

    public const FIN_CONTROLES = '</aside>';

    public const SCRIPT_APP = '<script src="/statique/app.js"></script>';

    public const BARRE_ACTIONS = '<div class="actions">';

    public function adapter(string $html, string $jeton, int $heuresConservation, string $urlEspace): string
    {
        // La mention de la version anonyme du moteur ne vaut pas ici : la plateforme a des comptes.
        $html = $this->retirer(self::MENTION_ANONYME, $html);
        $note = '<p class="edition">Vos projets et leurs résultats sont effacés du serveur '
            .$heuresConservation.' heures après le calcul : enregistrez votre fichier projet '
            .'et vos résultats sur votre poste.</p>';
        $html = $this->inserer(self::FIN_CONTROLES, $note.self::FIN_CONTROLES, $html);
        $html = $this->inserer(self::FIN_ENTETE, '<meta name="csrf-token" content="'.e($jeton).'">'."\n".self::FIN_ENTETE, $html);
        $html = $this->inserer(self::SCRIPT_APP, '<script src="/outil-csrf.js"></script>'."\n".self::SCRIPT_APP, $html);

        return $this->inserer(self::BARRE_ACTIONS, self::BARRE_ACTIONS.'<a href="'.e($urlEspace)
            .'" style="color:#fff;text-decoration:none;margin-right:12px;font-size:14px">Mon espace</a>', $html);
    }

    private function retirer(string $motif, string $html): string
    {
        return preg_replace($motif, '', $html);
    }

    private function inserer(string $cherche, string $remplace, string $html): string
    {
        if (! str_contains($html, $cherche)) {
            Log::warning('Page du moteur : repère introuvable, retouche non appliquée', ['repere' => $cherche]);
        }

        return str_replace($cherche, $remplace, $html);
    }
}

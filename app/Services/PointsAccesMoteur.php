<?php

namespace App\Services;

/**
 * Ce que la plateforme suppose des points d'accès /api/* du moteur OpenNAT (autre dépôt, autre application) : la
 * liste est écrite ici et nulle part ailleurs. Un point ajouté côté moteur doit l'être ici, sinon il répond 404.
 */
class PointsAccesMoteur
{
    /** Points d'accès qui portent l'identifiant d'un calcul en deuxième segment. */
    public const PAR_CALCUL = ['calcul', 'resultats', 'fichiers', 'fichier', 'rsnat', 'fiche'];

    /** Points d'accès sans calcul, ouverts à tout compte validé. */
    public const LIBRES = ['referentiel', 'exemple', 'valider', 'rsee', 'materiaux'];

    /** @param  list<string>  $parts  segments du chemin après /api/ */
    public function __construct(private array $parts) {}

    public function point(): string
    {
        return $this->parts[0] ?? '';
    }

    /** Lecture, résultats ou fichiers d'un calcul existant : l'identifiant est le deuxième segment. */
    public function porteUnCalcul(): bool
    {
        return in_array($this->point(), self::PAR_CALCUL, true) && count($this->parts) >= 2;
    }

    public function identifiantCalcul(): string
    {
        return explode('.', $this->parts[1])[0];
    }

    /** Lancement d'un calcul (POST /api/calcul, éventuellement avec reprise). */
    public function estCreation(): bool
    {
        return $this->point() === 'calcul' && count($this->parts) === 1;
    }

    /** Suivi d'état d'un calcul (GET /api/calcul/{id}). */
    public function estSuivi(): bool
    {
        return $this->point() === 'calcul' && count($this->parts) === 2;
    }

    public function estLibre(): bool
    {
        return in_array($this->point(), self::LIBRES, true);
    }

    public function chemin(): string
    {
        return '/api/'.implode('/', $this->parts);
    }
}

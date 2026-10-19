<?php

/*
|--------------------------------------------------------------------------
| OpenNAT, plateforme en ligne (IRICE)
|--------------------------------------------------------------------------
| La plateforme est une façade : comptes, page d'accueil, recueil de la méthode. Le calcul est fait par le moteur
| OpenNAT (dépôt normaxis-group/opennat, utilitaire en mode public), joint sur le réseau interne et jamais exposé.
| Les chiffres affichés viennent d'ici, pas des vues.
*/

return [

    // Adresse interne du moteur (alias réseau Coolify), sans barre finale.
    'moteur_url' => rtrim((string) env('OPENNAT_MOTEUR_URL', 'http://127.0.0.1:8770'), '/'),

    // Délai d'attente des appels au moteur, en secondes (import d'un gros RSEE compris).
    'moteur_delai' => (int) env('OPENNAT_MOTEUR_DELAI', 120),

    // Durée de conservation des résultats dans le moteur (OPENNAT_CONSERVATION_HEURES côté moteur).
    'conservation_heures' => (int) env('OPENNAT_CONSERVATION_HEURES', 24),

    // Destinataires des demandes d'inscription, en plus des administrateurs actifs.
    'alerte_inscriptions' => array_filter(array_map('trim', explode(',', (string) env('OPENNAT_ALERTE_INSCRIPTIONS',
        'hello@irice-certification.com')))),

    'depot_url' => 'https://github.com/normaxis-group/opennat',
    'irice_url' => 'https://irice-certification.com',
    'page_irice_url' => 'https://irice-certification.com/opennat',
    'mentions_legales_url' => 'https://irice-certification.com/mentions-legales',

    // Faits de la méthode (version 1, données 1.2.2), relevés dans le dépôt.
    'faits' => [
        'version_methode' => 'OpenNAT 1',
        'horizon_annees' => 50,
        'types_habitat' => 191,
        'conventions' => 70,
    ],

    /*
    | Recueil de la méthode (zone connectée). Les textes sont copiés du dépôt OpenNAT par
    | `php artisan opennat:recueil` dans resources/recueil ; « fichier » est relatif à ce dossier.
    */
    'recueil' => [
        'methode' => ['titre' => 'Méthode de calcul OpenNAT, version 1', 'fichier' => 'OpenNAT_v1.md',
            'source' => 'methode/OpenNAT_v1.md', 'pdf' => 'Methode_de_calcul_OpenNAT_v1.pdf',
            'pdf_source' => 'livraison/Méthode de calcul OpenNAT v1.pdf',
            'resume' => 'Indicateurs, étapes du calcul, fiches algorithmes et exemple de l\'annexe C.'],
        'conventions' => ['titre' => 'Conventions de méthode', 'fichier' => 'conventions.md',
            'source' => 'methode/conventions.md',
            'resume' => 'Les conventions écrites et numérotées, famille par famille.'],
        'guide-utilisateur' => ['titre' => 'Guide utilisateur', 'fichier' => 'guide_utilisateur.md',
            'source' => 'documentation/guide_utilisateur.md', 'pdf' => 'OpenNAT_guide_utilisateur.pdf',
            'pdf_source' => 'livraison/OpenNAT, guide utilisateur.pdf',
            'resume' => 'Données à réunir, saisie, calcul et lecture des résultats.'],
        'guide-contributeur' => ['titre' => 'Guide du contributeur', 'fichier' => 'guide_contributeur.md',
            'source' => 'documentation/guide_contributeur.md', 'pdf' => 'OpenNAT_guide_contributeur.pdf',
            'pdf_source' => 'livraison/OpenNAT, guide du contributeur.pdf',
            'resume' => 'Proposer une valeur avec sa source.'],
        'bibliographie' => ['titre' => 'Bibliographie', 'fichier' => 'bibliographie.md',
            'source' => 'methode/bibliographie.md',
            'resume' => 'Sources des tables et des modèles.'],
        'journal' => ['titre' => 'Journal des versions', 'fichier' => 'JOURNAL_DES_VERSIONS.md',
            'source' => 'JOURNAL_DES_VERSIONS.md',
            'resume' => 'Versions de la méthode, des données et du moteur.'],
    ],

    // Dépôt et révision d'où le recueil est copié.
    'recueil_depot_brut' => 'https://raw.githubusercontent.com/normaxis-group/opennat',
    'recueil_revision' => env('OPENNAT_RECUEIL_REVISION', 'main'),
];

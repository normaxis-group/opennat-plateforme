# Journal des versions d'OpenNAT

Trois objets ont chacun leur version : le texte de la méthode, les tables de données et le moteur. Un résultat se rattache aux trois : la fiche de synthèse et le RSNat portent la version de la méthode et du moteur, et l'empreinte des tables, qui désigne une version des données ci-dessous. Les règles de numérotation sont dans le guide du contributeur. Méthode en attente de validation du Biodiversity Standard Council ; aucune version n'est publiée.

## Version en vigueur

| Objet | Version | Date |
|---|---|---|
| Méthode | OpenNAT 1, révision du 19 octobre 2026 | 19/10/2026 |
| Données | 1.2.2 | 19/10/2026 |
| Moteur | 0.2.0 | 19/10/2026 |

Empreinte des tables de la version 1.2.2 : `a8eafaca6188b665864b424f480fd3b19a01d3cb244f8adbbb61862aab4ddcbd`

`python -m opennat controle` affiche l'empreinte des tables présentes ; si elle ne figure pas dans ce journal, les tables ont été modifiées sans être versionnées.

## Méthode

| Révision | Lot | Changements | Commit |
|---|---|---|---|
| 18/10/2026 | 1 | Texte de la méthode OpenNAT v1 (indicateurs Cnat, Inat, Snat, Bnat ; étapes E1 à E5 ; modules S, H, C, N, E, T, G), conventions, bibliographie, exemple de l'annexe C vérifié | 63aa634 |
| 18/10/2026 | 1 bis | Édition complète : données conventionnelles (partie 2), 42 fiches algorithmes (partie 3), 191 fiches habitats (partie 4), fiches d'application (partie 5), protocoles de relevé (partie 6), RSNat (partie 7), huit cas de validation (partie 8) | b513b98 |
| 18/10/2026 | 1 bis | Toute unité de valeur non nulle relève d'une tache (fiches A17 et A19) ; annexe C recalculée | d90ec71 |
| 18/10/2026 | 1 ter | Modules dynamiques : climat horaire TRACC et ET0 (A43 à A46), bilan hydrique horaire des sols, toitures et mares (A47 à A50), éclairage horaire (A51 à A53), maturation et gestion (A54 à A56), calendrier du chantier (A57, A58) ; 29 cas dynamiques ; annexe C recalculée (Cnat 3 217,2) | a1a19df, 2f9f9d2 |
| 19/10/2026 | 2 | Fichier projet (partie 7.6) ; RSNat d'exemple écrit par le moteur | a3ac8bd |
| 19/10/2026 | 4 | Unité du facteur de caractérisation du climat corrigée : m²HE par kg CO2 éq. et non « par kg et par an » (fiche A33, équation G3, partie 7) ; relevée par le contrôle dimensionnel ; aucun résultat ne change | ce lot |

## Données

| Version | Date | Lot | Changements | Empreinte |
|---|---|---|---|---|
| 0.1 | 18/10/2026 | 1 | Onze tables sourcées (habitats, sols, contexte, connectivité, nuisances, eau, matériaux, seuils, critères d'état, nomenclature, conventions) ; contrôle de couverture | `0249bab655b9f01412ee82db5e7a144c1fb6acbfc56f547c8087d7a0f5c38565` |
| 0.2 | 18/10/2026 | 1 bis | Délais d'amélioration de la métrique anglaise (onglet G-5), grille d'état des vergers, conventions de relevé | `b11168623de636d3d6311b09f5014676726bd35640e84be2cc2d9b7840af6023` |
| 1.0 | 18/10/2026 | 1 ter | Tables des modules dynamiques : climat (8 stations, normales Météo-France 1991-2020, fichiers horaires prospectifs à +2, +2,7 et +4 °C), hydrique, éclairage, succession, chantier | `d2c917c2b687eea2c0f34d92e403dabe7533e72b2a4062da90c9770f609a4836` |
| 1.1 | 19/10/2026 | 1 ter | Grille d'état de chaque type dans la nomenclature ; fourrés notés sur la grille EF1 et non EL1 | `85500bf80d089f8c998fabc1e4f7d723d569b161f6661a7506fc3a818e34f1db` |
| 1.2 | 19/10/2026 | 2 | Constantes des équations publiées et valeurs de règle lues par le moteur (`constantes.csv`, 70 lignes : FAO-56, ISO 2533, raccourcis d'éclairage, heure légale, contrôles, incertitude) ; bornes des classes d'emprise (`seuils.csv`) ; part de valeurs primaires ou transposées : 92 % | `1e8f76c435feb0332e70096dff7f082f43f3498e4c163bee84a3e6cd2aefada0` |
| 1.2.1 | 19/10/2026 | 4 | `materiaux.csv`, climat, facteur de caractérisation : unité « m²HE par kg CO2 éq. » au lieu de « m²HE par kg CO2 éq. et par an », valeur inchangée (1,807 × 10⁻³). Effet sur les 73 cas du banc : aucun (instantané de non-régression identique hors empreinte) | `b320e8edd30696e070f202454ebece37b96b7b7f38f8bb0c6463f52db555c6de` |
| 1.2.2 | 19/10/2026 | 5 | `conventions.csv` : sept énoncés tronqués complétés (C-H7, C-E1, C-C1, C-N2, C-G2, C-W8, C-L3), le générateur coupant à toute abréviation (« article R. ») ; aucune valeur ne change. Effet sur les 73 cas du banc : aucun | `a8eafaca6188b665864b424f480fd3b19a01d3cb244f8adbbb61862aab4ddcbd` |

## Moteur

| Version | Date | Lot | Changements | Commit |
|---|---|---|---|---|
| 0.1.0 | 19/10/2026 | 2 | Moteur complet : modules E1 à E5, S, H, C, N, E, W, T, G ; incertitude par blocs avec reprise ; journal ; RSNat XML et JSON validés ; reprise du RSEE ; ligne de commande ; banc de 73 cas | f231b6e |
| 0.1.0 | 19/10/2026 | 3 | Utilitaire de calcul (serveur local et page web) ; moteur inchangé | 4e81637 |
| 0.2.0 | 19/10/2026 | 4 | Contrôle dimensionnel automatique (`opennat/dimensions.py`, exigence E12) appelé par `opennat controle` ; banc de non-régression (38 930 grandeurs) ; 20 tests de propriétés ; contrôle d'ordre de grandeur du module G face à l'étude Carbone 4 ; guides utilisateur et contributeur | ce lot |

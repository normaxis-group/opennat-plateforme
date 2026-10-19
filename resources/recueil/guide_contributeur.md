# Guide du contributeur d'OpenNAT

Méthode OpenNAT 1, moteur 0.2.0, données 1.2.1. 19 octobre 2026. Méthode éditée par NORMATYS, en attente de validation du Biodiversity Standard Council.

Ce guide s'adresse à qui veut améliorer OpenNAT : écologue qui dispose d'une meilleure valeur pour un habitat, chercheur qui publie un facteur, bureau d'études qui relève une incohérence, développeur. Il dit surtout comment proposer une nouvelle valeur avec sa source, ce que le cahier des charges demande en premier.

## Trois règles

1. Toute valeur a une source. Chaque nombre employé par le calcul est une ligne d'une table de `donnees/`, avec sa source, sa référence précise, sa licence et son type. Une ligne sans source fait échouer le chargement (exigence E1).
2. Aucune valeur de méthode dans le code. Le moteur lit les tables ; un test parcourt le code et refuse tout nombre qui serait une valeur de méthode (exigence E2). Seules les conversions d'unités vivent dans `opennat/unites.py`.
3. Toute convention a un numéro. Quand aucune source ne donne la valeur, OpenNAT la fixe par une convention numérotée (C-H1, C-G5…) décrite dans `methode/conventions.md`, avec son motif. Les conventions et les seuils sont soumis au Biodiversity Standard Council.

## Le format d'une ligne

Les tables sont des fichiers CSV en UTF-8, séparateur point-virgule, point décimal, treize colonnes :

| Colonne | Contenu | Exemple |
|---|---|---|
| `cle` | objet décrit : code d'habitat, classe, régime, ou `*` pour une valeur générale | `foret` |
| `parametre` | grandeur, en minuscules sans accent | `production_biologique` |
| `valeur` | valeur centrale | `5.4` |
| `unite` | unité, prise dans le catalogue dimensionnel | `m³ par ha et par an` |
| `incertitude_min`, `incertitude_max` | bornes de la loi triangulaire tirée par l'analyse d'incertitude ; égales à la valeur si elle est exacte | `4.0`, `7.5` |
| `source` | auteurs, année, titre | |
| `reference_precise` | page, tableau, équation, ligne ou onglet | |
| `url` | adresse du document ou de la donnée | |
| `licence` | licence de la source, ou motif de reprise | `Licence Ouverte 2.0` |
| `type` | `primaire`, `secondaire`, `transposition` ou `convention` | |
| `convention` | numéro de convention, obligatoire pour une transposition ou une convention | `C-G4` |
| `justification` | une phrase : pourquoi cette valeur pour cet objet | |

Les quatre types :

- primaire : la valeur est lue telle quelle dans la source, pour l'objet décrit ;
- secondaire : la valeur vient d'une compilation ou d'une revue qui cite la source primaire ;
- transposition : la valeur est lue dans une source faite pour un autre objet, un autre pays ou une autre échelle, et convertie selon une convention écrite (par exemple, valeurs de biotope allemandes ramenées entre 0 et 1, convention C-H1) ;
- convention : la valeur est fixée par OpenNAT, faute de source ; la justification dit pourquoi celle-ci.

La valeur doit être comprise entre ses deux bornes. L'unité doit figurer au catalogue de `opennat/dimensions.py` ; une unité nouvelle s'y déclare avec ses dimensions (longueur, masse, temps, température, habitat équivalent), ce qui permet au contrôle dimensionnel de vérifier les équations où elle entre.

## Licences

OpenNAT est diffusé sous licence libre : texte et tables sous CC BY 4.0, moteur sous AGPL. Une valeur n'y entre que si sa reprise est permise. Sont admises les sources sous licence ouverte (Licence Ouverte 2.0, Open Government Licence, CC BY), les textes réglementaires, et la citation d'une valeur publiée avec sa référence. Ne sont pas admises les données dont la licence interdit la réutilisation ou la notation : c'est le cas des fiches INIES, dont la licence du service web interdit tout classement ou notation des déclarations environnementales. En cas de doute, écrire la licence telle qu'on la lit et le signaler dans la proposition.

## Où vit chaque table

Certaines tables s'écrivent à la main ; d'autres sont produites par un script depuis des sources recopiées dans `sources/`. Pour ces dernières, on modifie le script ou la source extraite, jamais le CSV, qui serait écrasé à la reconstruction.

| Table | Contenu | Modifier |
|---|---|---|
| `habitats.csv` | valeurs intrinsèques, délais, difficultés, plafonds par type d'habitat | `outils/construire_habitats.py` et `nomenclature_habitats.csv` |
| `nomenclature_habitats.csv` | types OpenNAT, correspondances EUNIS, BKompV et métrique anglaise, grille d'état | à la main ; colonne `grille_etat` par `outils/construire_grilles.py` |
| `sols.csv` | coefficients de nature de surface | `outils/construire_sols.py` |
| `climat.csv` et `climat/*.csv.gz` | stations, normales de pluie, fichiers horaires prospectifs | `outils/construire_climat.py` |
| `hydrique.csv`, `eclairage.csv`, `succession.csv`, `chantier.csv` | modules dynamiques | `outils/construire_dynamique.py` |
| `constantes.csv` | constantes des équations publiées (FAO-56, ISO 2533), raccourcis, contrôles, incertitude | `outils/construire_constantes.py` |
| `contexte.csv`, `seuils.csv`, `connectivite.csv`, `nuisances.csv`, `eau.csv`, `materiaux.csv`, `etat_criteres.csv` | contexte, seuils, connectivité, nuisances, eau, biodiversité grise, critères d'état | à la main |
| `conventions.csv` | liste des conventions et de leur statut | à la main, avec `methode/conventions.md` |

## Proposer une valeur : la marche à suivre

1. Trouver la ligne. La partie 2 de l'édition complète (données conventionnelles) liste chaque valeur avec sa table, sa clé et son paramètre ; `grep` dans `donnees/` fait le reste.
2. Réunir la source : référence complète, emplacement précis de la valeur, adresse, licence. Pour une transposition, écrire la conversion. Pour les bornes, prendre l'intervalle publié ; à défaut, l'étendue des valeurs de la source ; à défaut, des bornes égales à la valeur et une justification qui le dit.
3. Modifier la ligne, ou le script de construction et le relancer. Ne changer qu'une chose à la fois.
4. Contrôler les tables :

```
python outils/controle_donnees.py
python -m opennat controle
```

Le premier vérifie les treize colonnes, la source, le type, le numéro de convention et les bornes, et réécrit le rapport de couverture (`donnees/rapport_couverture.md`) ; le second recharge les tables et vérifie la cohérence des unités dans les équations.

5. Mesurer l'effet. Le carnet de référence lit les mêmes tables que le moteur, par un code indépendant :

```
python banc/convertir_cas.py
python -m banc.comparer
python -m banc.regression
```

La première commande recalcule les résultats attendus des 73 cas avec le carnet ; la deuxième vérifie que le moteur les retrouve toujours à 0,1 % ; la troisième liste chaque grandeur qui a bougé par rapport à l'instantané de non-régression, et signale le changement d'empreinte des tables.

6. Inscrire la version. Ajouter au `JOURNAL_DES_VERSIONS.md` une version des données avec la date, l'empreinte affichée par `python -m opennat controle`, chaque ligne modifiée (avant, après, source) et l'effet sur les huit cas tests. Puis régénérer l'instantané (`python -m banc.regression --ecrire`) et lancer `python -m pytest`.
7. Soumettre la modification en un seul commit, message en français au format des commits conventionnels, par exemple `fix(donnees): production biologique des forêts tempérées d'après <source>`, avec le journal, l'instantané et le rapport de couverture.

La décision d'intégrer une valeur appartient à l'éditeur de la méthode, NORMATYS. Une valeur primaire qui remplace une convention ou une transposition est le cas le plus simple. Une nouvelle convention, ou un changement de seuil, passe devant le Biodiversity Standard Council.

## Modèle de proposition

Une proposition, qu'elle arrive par un commit ou par écrit, tient en un tableau :

| Rubrique | À remplir |
|---|---|
| Ligne visée | table, clé, paramètre |
| Valeur actuelle | valeur, bornes, type, convention |
| Valeur proposée | valeur, bornes, unité |
| Source | auteurs, année, titre, page ou tableau, adresse |
| Licence | licence lue, ou motif de reprise |
| Type | primaire, secondaire, transposition (avec la conversion) ou convention (avec le motif) |
| Effet mesuré | grandeurs des huit cas tests avant et après (`python -m banc.regression`) |
| Proposé par | nom, organisme, date |

## Ajouter un type d'habitat

Un type nouveau demande une ligne dans `nomenclature_habitats.csv` (code, libellé, correspondances EUNIS, BKompV et métrique anglaise, justification), la reconstruction de `habitats.csv`, la grille d'état (`outils/construire_grilles.py`), puis le contrôle et le banc comme ci-dessus. La partie 4 de l'édition (fiches habitats) se régénère d'elle-même.

## Ajouter un cas au banc

Un cas se décrit dans `outils/edition/cas_validation.py`, `cas_complementaires.py` ou `cas_dynamiques.py`. `python banc/convertir_cas.py` l'écrit en fichier projet dans `banc/cas/` et calcule ses résultats attendus par le carnet ; il entre ensuite dans le banc et dans l'instantané de non-régression (`python -m banc.regression --ecrire`). Un cas qui illustre une propriété (« ajouter de la pleine terre ne dégrade pas Cnat ») a plutôt sa place dans `tests/test_proprietes.py`.

## Contribuer au code

- Chaque fonction de calcul cite sa fiche (`Fiche A33 [G2]`) et chaque équation son numéro (`# (A33-2)`) ; un test le vérifie.
- Aucun nombre de méthode dans le code ; une constante nouvelle va dans `constantes.csv` par `outils/construire_constantes.py`, avec sa source.
- Le même code sert aux valeurs centrales et aux tirages : écrire avec les fonctions de `opennat/numerique.py` (`mini`, `maxi`, `si`, `borne`) plutôt qu'avec `min`, `max` ou `if` sur une valeur tirée.
- `python -m pytest` doit passer en entier, banc et non-régression compris.
- En-tête de licence `SPDX-License-Identifier: AGPL-3.0-or-later` sur chaque fichier.

## Numéros de version

- Méthode : OpenNAT 1 tant que le texte garde ses équations ; une révision change la date et s'inscrit au journal ; une équation modifiée fait OpenNAT 2.
- Données : 1.2.1 aujourd'hui. Le troisième chiffre change pour une correction sans effet sur un résultat (unité, libellé, source) ; le deuxième pour une valeur ou une ligne ajoutée ; le premier quand la structure des tables change. L'empreinte des tables identifie chaque version.
- Moteur : 0.2.0 aujourd'hui, versions sémantiques ; il passera en 1.0.0 à la validation de la méthode.

Le `JOURNAL_DES_VERSIONS.md` tient l'historique des trois.

# Guide utilisateur d'OpenNAT

Outil de calcul OpenNAT édité par IRICE. Méthode OpenNAT 1, moteur 0.2.0, données 1.2.2. 19 octobre 2026. Méthode éditée par NORMATYS, en attente de validation du Biodiversity Standard Council : tant qu'elle n'est pas validée, ses seuils et ses résultats ne sont pas opposables.

Ce guide s'adresse au bureau d'études ou à l'écologue qui calcule un projet. Il dit quelles données réunir, comment les saisir, comment lancer le calcul et comment lire les résultats. Le détail des calculs est dans le texte de la méthode (fiches algorithmes de la partie 3) ; les valeurs employées sont dans les tables de données (partie 2).

## Ce que calcule OpenNAT

OpenNAT compare, sur 50 ans, la valeur écologique du site avant et après le projet, et y ajoute la nature consommée ailleurs par les matériaux et le chantier. Comme la RE2020 pour le carbone, le calcul est conventionnel : il ne prédit pas ce qui arrivera au site, il compare des projets entre eux sous des règles communes.

| Indicateur | Ce qu'il mesure | Unité |
|---|---|---|
| Cnat | Consommation de nature sur le site : valeur du site sans projet intégrée sur 50 ans, moins celle du site avec projet, plus le supplément dû à une destruction en période sensible. Négatif, c'est un gain | m²HE·an |
| Inat | Biodiversité grise : climat (émissions de l'ACV), forêts exploitées pour le bois, carrières | m²HE·an |
| Snat | Solde de nature : -(Cnat + Inat). Positif, le projet rend plus qu'il ne prend | m²HE·an |
| Bnat | Coefficient de biotope de l'état projet, en points (100 points pour un coefficient de 1) | points |
| Niveau | 0, 1 (Cnat nul ou négatif) ou 2 (gain d'au moins 10 % de la valeur initiale intégrée) | |

Un m²HE est un mètre carré d'habitat de qualité 1 : une prairie de valeur 0,5 sur 1 000 m² vaut 500 m²HE. Un m²HE·an est un m²HE pendant un an.

Le calcul suit le climat du site heure par heure (fichiers prospectifs de Météo-France pour la trajectoire de réchauffement de référence, TRACC) : bilan hydrique des sols, des toitures et des mares, éclairage nocturne, maturation des plantations, gestion après livraison, calendrier du chantier. Une analyse d'incertitude (1 000 tirages par défaut) donne l'intervalle de chaque indicateur et les cinq paramètres qui pèsent le plus.

## Installer

OpenNAT demande Python 3.11 ou plus, avec numpy et lxml ; python-docx pour la fiche de synthèse en Word, et LibreOffice pour la fiche en PDF.

```
pip install numpy lxml python-docx
python -m opennat controle
```

La seconde commande contrôle les tables de données et la cohérence des unités ; elle doit finir par « aucune incohérence ».

## Réunir les données

| Donnée | Où la trouver | Obligatoire |
|---|---|---|
| Surface de la parcelle, zone biogéographique, typologie (logement, tertiaire, industrie, aménagement), zone climatique | dossier de permis, RSEE | oui |
| État initial : chaque unité de végétation ou de surface, avec son type, sa surface et son état | relevé de terrain selon les protocoles de la partie 6 | oui |
| État projet : chaque unité du plan masse, avec son type, sa position (pleine terre, dalle, toiture, façade, eau, arbre, haie) et son état prévu | plan masse, carnet paysager | oui |
| Bâtiments : S_ref, emprise, durée du chantier, émissions par phase | RSEE de l'étude RE2020 | pour Inat |
| Bois (volume, régime forestier, pièce justificative) et granulats (tonnes) | métrés, ACV | pour Inat |
| Calendrier : année et mois de début du chantier, mois de la destruction de la végétation | planning | non (défauts) |
| Sol : texture et profondeur | étude de sol ou géotechnique | non (limon, 1 m) |
| Éclairage extérieur : horaires, température de couleur, distance, détection | notice d'éclairage | non |
| Eau : alimentation des milieux humides, bassins versants des mares, ouvrages d'infiltration | notice hydraulique | non |
| Plan de gestion écologique : durée, et devenir des espaces ensuite | engagement du maître d'ouvrage | non (gestion courante) |

L'état d'une unité se note sur la grille de son type (critères de la métrique anglaise, critères dits essentiels signalés) ou, pour un boisement, sur ses indicateurs notés de 1 à 3. Une unité sans grille (voirie, bâti, cultures) n'a pas de note.

## Saisir un projet dans l'utilitaire

```
python -m opennat.utilitaire
```

L'utilitaire s'ouvre dans le navigateur à l'adresse http://127.0.0.1:8770/. Le serveur ne répond qu'à la machine ; rien ne part sur Internet et la page ne charge aucune ressource extérieure. Les options `--port`, `--dossier` (dossier des résultats) et `--sans-navigateur` changent le port, le dossier de travail et l'ouverture automatique.

Le bouton « Exemple » charge l'exemple de l'annexe C de la méthode, un projet complet : chaque écran y est rempli. « Ouvrir » et « Enregistrer » lisent et écrivent le fichier projet (JSON) ; il n'y a ni compte ni base de données.

Les onglets suivent les étapes de la méthode :

- Projet et contexte : identification, zone, typologie, zone climatique, altitude, sol, calendrier du chantier, plan de gestion. Le bouton de reprise d'un RSEE remplit la parcelle, les bâtiments, les indicateurs d'ACV, la zone climatique, le département et l'altitude ; le fichier est lu puis oublié.
- État initial et état projet : une ligne par unité. Le détail d'une unité ouvre sa grille d'état (chaque critère avec son libellé), son éclairage, son eau et son sol, sa gestion et son statut au chantier (conservée, créée, améliorée, avec l'unité initiale liée et sa protection).
- Sol, connectivité, nuisances, eau : taches d'habitat, situation (réservoir, corridor, hors trame), façades vitrées, ouvrages pluviaux.
- Matériaux : bois et granulats, saisis ou importés d'un tableur CSV (modèle téléchargeable ; colonnes `categorie;regime;quantite;remplacements;justification`, catégorie `bois` en m³ ou `granulats` en tonnes). Une ligne refusée est signalée avec son motif.
- Variantes : chaque variante reprend la référence et n'en change que ce qu'on modifie (unités, matériaux, chantier, gestion).

Le panneau de contrôles se met à jour à chaque saisie : conformité au schéma du fichier projet, saisie de chaque unité, fermeture des surfaces. La somme des surfaces au sol de chaque état doit égaler la parcelle, à la tolérance près ; tant qu'un contrôle échoue, le calcul ne se lance pas.

## Lancer le calcul

Le nombre de tirages se règle avant le lancement : 0 pour les valeurs centrales seules (quelques secondes), 1 000 par défaut pour l'analyse d'incertitude (quelques minutes). La graine fixe les tirages : le même projet, avec la même graine et les mêmes tables, redonne les mêmes résultats au chiffre près.

Le suivi allume les étapes de chaque variante (E1 contexte, E2 prétraitement, S sol, H habitats, C connectivité, N nuisances, E eau, W bilan hydrique, T temps, G matériaux, E3 agrégation, E5 conformité), puis la barre des tirages (E4). Un calcul arrêté s'interrompt à la fin du bloc de 100 tirages en cours ; « Reprendre le calcul » le termine dans le même dossier, sans refaire les blocs écrits. La reprise est refusée si le projet ou les tables ont changé entre-temps.

## Lire les résultats

La page de résultats donne, pour chaque variante :

- les indicateurs et le niveau, avec les seuils et la conformité (Bnat minimal selon la typologie et la part d'emprise, pleine terre minimale, coefficient du PLU s'il est saisi) ;
- la trajectoire du site sur 50 ans, face à celle du site sans projet sous le même climat ;
- le détail par unité : valeur intrinsèque, état, facteurs de sol, de connectivité, de nuisances, d'eau et de bilan hydrique, délai de maturation, valeur intégrée ;
- la biodiversité grise par poste (climat, bois, carrières) et par phase, et les indicateurs de pollution du RSEE, affichés à part ;
- l'incertitude : 5e, 50e et 95e centiles de Cnat, Inat, Snat et Bnat, et les cinq paramètres les plus influents (corrélation de rang avec Snat) ;
- le journal : valeurs par défaut appliquées, corrections de saisie, reprises du RSEE.

L'onglet de comparaison place les variantes côte à côte. L'écart entre deux variantes a son propre intervalle, calculé sur les mêmes tirages : un écart dont l'intervalle ne contient pas zéro tient quelle que soit l'incertitude des données.

Pour lire un résultat, partir de la trajectoire. Un Cnat positif s'explique presque toujours par une valeur initiale élevée (prairie, boisement, mare) ou par une maturation lente des plantations ; le détail par unité montre lesquelles pèsent. La dette de maturité (DM) chiffre ce que coûte d'attendre que les plantations atteignent leur valeur.

## Fiche de synthèse et RSNat

Chaque calcul produit le récapitulatif standardisé (RSNat), en XML et en JSON, validé contre ses schémas (`schemas/rsnat.xsd`, `schemas/rsnat.schema.json`). La fiche de synthèse en Word, au papier d'IRICE, éditeur de l'outil, et en PDF reprend les indicateurs, la conformité, l'incertitude et les versions de la méthode, du moteur et des données, avec les empreintes de contrôle des entrées et des tables. Ces empreintes permettent à un tiers de vérifier qu'un résultat a bien été calculé sur le projet et les tables déclarés.

## Ligne de commande

```
python -m opennat valider projet.json
python -m opennat calcul projet.json
python -m opennat calcul projet.json --tirages 0
python -m opennat calcul projet.json --graine 12345 --sortie D:/etudes/resultats
python -m opennat calcul projet.json --reprendre resultats/<projet>/<horodatage>
```

Le fichier projet suit `schemas/projet.schema.json` ; `banc/cas/exemple_de_l_annexe_c.json` en est un exemple complet. Les blocs obligatoires sont `format`, `projet`, `contexte`, `parcelle`, `etat_initial` et `variantes` ; `chantier`, `gestion`, `taches`, `batiments` et `options` sont facultatifs. Un projet peut citer un RSEE (`projet.rsee`) dont la parcelle, les bâtiments et les indicateurs sont repris.

Le calcul écrit un dossier `resultats/<projet>/<horodatage>/` :

| Fichier | Contenu |
|---|---|
| `00_entrees.json` | projet après application des valeurs par défaut |
| `variante_n/01_E1_contexte.json` à `13_E5_conformite.json` | sorties de chaque étape |
| `variante_n/09_T_trajectoires.csv` | trajectoire annuelle de chaque unité |
| `12_E4_tirages.csv`, `12_E4_incertitude.json` | tirages et intervalles |
| `journal.txt` | versions, durée, valeurs par défaut, corrections |
| `empreinte.txt` | empreintes des entrées, des tables et du RSNat |
| `rsnat.xml`, `rsnat.json` | récapitulatif standardisé |

Codes de sortie du calcul : 0 calcul terminé ; 2 calcul arrêté (tables ou projet invalides, fermeture non satisfaite, code inconnu, reprise refusée), avec le motif ; 3 RSNat non conforme à ses schémas. `valider` et `controle` rendent 1 au premier défaut.

## Valeurs par défaut

Une donnée facultative absente prend une valeur conventionnelle, toujours inscrite au journal avec sa convention :

| Donnée absente | Valeur retenue | Convention |
|---|---|---|
| Sol du site | limon de 1 m | C-W4 |
| Début du chantier | janvier de l'année par défaut | C-K3 |
| Mois de destruction | mois de début du chantier | fiche A05-2 |
| Durée du chantier | la plus longue des bâtiments | fiche A05-2 |
| Plan de gestion | aucun : gestion courante dès la livraison | C-M3 |
| Bois sans pièce justificative | coupe rase, quel que soit le régime déclaré | C-G5 |
| Houppier d'un arbre sans surface relevée | surface de sa classe de diamètre | fiche A14 |
| Haie trop étroite | largeur minimale | fiche A15 |

## Messages fréquents

- « fermeture des surfaces de l'état initial (ou projet) » : les surfaces au sol de cet état ne font pas la parcelle. Corriger les surfaces ; les toitures, les façades et les houppiers ne comptent pas dans la fermeture.
- « code d'habitat inconnu » : le type d'habitat n'est pas dans la nomenclature. Le code EUNIS ou un alias est accepté ; à défaut, choisir le type le plus proche dans la liste.
- « reprise refusée » : le projet ou les tables ont changé depuis le calcul interrompu. Relancer un calcul neuf.
- « RSNat non conforme » : un champ sort de son schéma ; le journal dit lequel. C'est une anomalie du moteur, à signaler.

## Limites de la version 1

- La biodiversité grise ne compte pas encore la pollution : les cinq indicateurs du RSEE sont affichés sans conversion (convention C-G8). Convertis par des facteurs ouverts, ils pèseraient de 4 à 13 % du climat ; leur ajout à Inat est proposé au Biodiversity Standard Council (note « Pollution et biodiversité grise »). L'étude Carbone 4, avec un autre modèle de l'écotoxicité des métaux, trouve une part bien plus forte.
- Le bois est fortement pénalisé par les conventions C-G4 et C-G5, premières à recalibrer.
- La biodiversité grise n'est pas donnée par lot de la RE2020.
- La zone climatique n'est pas déduite du département : la saisir ou la reprendre du RSEE.
- Les seuils de Bnat, de pleine terre et de niveau sont en attente de validation du Biodiversity Standard Council ; Inat et Snat n'ont pas de seuil.

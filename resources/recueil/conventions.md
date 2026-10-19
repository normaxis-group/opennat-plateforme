# Conventions de la méthode OpenNAT v1

Une convention est une règle ou une valeur que OpenNAT fixe parce qu'aucune source ne la donne telle quelle. Chacune est numérotée, citée dans le texte de la méthode et dans la colonne `convention` des tables de `donnees/`, et soumise au Biodiversity Standard Council avec la méthode. Toutes sont « proposées, en attente de validation ».

Familles : H habitats, S sols, E contexte, eau et seuils, C connectivité, N nuisances, T temps, G biodiversité grise, R relevés, I incertitude, B Bnat, P attestation au permis de construire, W bilan hydrique et climat, L éclairage, M maturation et gestion, K calendrier du chantier.

Les conventions jouent le rôle des fiches d'application de la méthode Th-BCE : elles disent comment appliquer la méthode là où les sources se taisent. Le registre suit les trois catégories du registre des lectures d'OpenBCE : source muette (aucune source ne traite le point), convention de transposition (une source traite le point dans un autre cadre et la valeur est transposée), valeur de secours (une valeur est posée faute de mieux, avec une plage large).

## Habitats

### C-H1
Catégorie : convention de transposition.
La valeur intrinsèque d'un habitat, en m²HE par m², est la valeur de biotope de l'annexe 2 de la BKompV (ordonnance fédérale allemande de compensation, 2020) divisée par 24, valeur maximale de l'échelle. Le type BKompV est choisi par une correspondance écrite, type par type, dans `donnees/nomenclature_habitats.csv`, avec sa justification. La BKompV est la seule liste qui soit à la fois réglementaire, libre de droits (§ 5 de la loi allemande sur le droit d'auteur), fine en milieu urbain et graduée par stade d'âge (audit, point P2). Chaque correspondance est une lecture : elle peut être contestée ligne par ligne sans toucher à la méthode.

### C-H2
Catégorie : convention de transposition.
Le facteur d'état vaut f = 0,5 + 0,5 x S, où S est le score de la grille de critères, entre 0 et 1. La valeur intrinsèque retenue est celle de la variante en bon état du type BKompV (riche en espèces, exploitée extensivement, avec arbres anciens quand le type le distingue). Le facteur d'état ramène un habitat dégradé vers la moitié de cette valeur, ce qui reproduit l'écart observé dans la BKompV entre variantes riches et pauvres d'un même type (prairie fraîche riche 20, pauvre 8 à 11). Les classes affichées pour l'écologue sont : bon état si S est au moins 0,85, état moyen de 0,65 à 0,85, mauvais état sous 0,65. Ces classes choisissent aussi le délai de maturité de la métrique anglaise.

### C-H3
Catégorie : convention de transposition.
Les types sans équivalent dans la BKompV (toitures végétalisées VB.11 à VB.13, façades VB.15 et VB.16, jardinières VB.23) reçoivent la distinctivité de la métrique statutaire anglaise ramenée à 1 (divisée par 8), multipliée par le rapport médian entre les deux échelles. Ce rapport est calculé par `outils/construire_habitats.py` sur les 175 types de la nomenclature qui ont les deux valeurs : 1,056. Une toiture extensive (distinctivité 2) vaut ainsi 0,264 m²HE par m².

### C-H4
Catégorie : source muette.
La métrique anglaise traite les cours d'eau dans un module linéaire distinct et ne leur donne ni délai ni difficulté surfaciques. Les cours d'eau et sources (C2.1 à C2.6) reçoivent le délai et la difficulté de la mare prioritaire. La noue et le jardin de pluie (VB.19) reçoivent la valeur du fossé proche de l'état naturel de la BKompV (23.05.01a.01), seul type allemand d'ouvrage linéaire en eau végétalisé.

### C-H5
Catégorie : convention de transposition.
Pour les types ligneux, la BKompV distingue trois stades : jeune (J), moyen (M) et âgé (A). La valeur intrinsèque par défaut est celle du stade moyen. L'écologue peut déclarer le stade d'un peuplement existant ; un peuplement créé vise le stade moyen. Pour les arbres isolés (VB.18a, VB.18b, G5.1), le stade se déduit de la classe de diamètre de la métrique anglaise : petit, stade J ; moyen, stade M ; grand et très grand, stade A. Quand le houppier n'est pas relevé, sa surface est celle de la zone de protection racinaire de la classe (onglet G-3 de la métrique anglaise).

### C-H6
Catégorie : convention de transposition.
Les grilles d'état sont celles des fiches d'évaluation de la métrique statutaire anglaise (juillet 2025), traduites et adaptées. Chaque type OpenNAT relève de la grille de son groupe anglais : prairies de faible valeur EP1, prairies de valeur moyenne à très haute EP2, landes EL1, haies EH1, arbres EA1, mares et plans d'eau EM1, fourrés EF1, milieux peu végétalisés ER1, milieux urbains EU1, zones humides EZ1, boisements EB1, vergers EV1. Les surfaces scellées, revêtues ou nues (VB.1 à VB.4, VB.10, VB.25, H5.6), les cultures et les plantations de production (I1.1 à I1.4, FB.1, FB.2, FB.4) n'ont pas de grille (f = 1). La grille de chaque type est écrite dans la colonne `grille_etat` de `donnees/nomenclature_habitats.csv`. Un type relève de la grille du groupe de sa ligne anglaise : prairie de distinctivité 2 au plus, EP1 ; prairie de distinctivité 4 et plus, EP2 ; landes, EL1 ; fourrés, EF1 ; lacs et mares, EM1 ; milieux peu végétalisés, ER1 ; urbain, EU1 ; zones humides et prés salés, EZ1 ; boisements, parcs boisés et coupes, EB1 ; arbres, EA1 ; haies, EH1 ; vergers traditionnels, EV1. Le score vaut S = (nombre de critères satisfaits) / (nombre de critères applicables). L'échec d'un critère essentiel plafonne S à 0,5, ce qui transpose la règle anglaise selon laquelle cet échec interdit le bon et le moyen état. Pour les boisements, chacun des 13 indicateurs est noté de 1 à 3 selon la fiche, et S = (total - 13) / 26.

### C-H7
Catégorie : valeur de secours.
La valeur d'un m² ne peut dépasser 1,15 m²HE, quel que soit le produit des facteurs. 1,15 est le plus fort multiplicateur de la métrique anglaise (importance stratégique ou connectivité élevée).

### C-H8
Catégorie : convention de transposition.
Les critères anglais renvoient aux espèces de l'annexe 9 du Wildlife and Countryside Act 1981 et, pour la grille urbaine, aux autres espèces nuisibles à la faune indigène « selon le jugement professionnel ». OpenNAT leur substitue : les espèces exotiques envahissantes préoccupantes pour l'Union (règlement (UE) n° 1143/2014 et règlements d'exécution qui fixent la liste, reproduite en partie 6), la liste nationale française, et les espèces que les listes régionales des conservatoires botaniques nationaux classent envahissantes avérées. Une espèce absente de la liste de l'Union, comme la renouée du Japon, compte dès qu'une liste régionale la classe envahissante avérée.

## Sols

### C-S1
Catégorie : convention de transposition.
Le coefficient de chaque nature de surface (`donnees/sols.csv`) est la médiane des coefficients publiés par les grilles de coefficient de biotope lues à la source : Berlin (facteurs des plans fixés après décembre 2019), Londres (Urban Greening Factor 2023), Strasbourg (PLU, article 9.2), Grand Annecy (PLUi-HMB), Paris (indice de végétalisation du bâti, 2025) et la Métropole européenne de Lille. La plage d'incertitude est l'étendue des valeurs. Une grille qui distingue plusieurs cas pour une même nature compte pour chacun. Les classes d'épaisseur (15, 30 et 80 cm) suivent l'audit (point P12) : 15 cm est la borne de l'extensif, 25 à 30 cm le seuil au-delà duquel la richesse floristique des toitures cesse de croître (GROOVES), 80 cm la borne au-delà de laquelle les grilles rapprochent la dalle du sol. Montreuil et le guide du SDAGE Seine-Normandie, cités par l'audit, ne sont pas retenus faute d'avoir été relus au lot 1.

### C-S2
Catégorie : convention de transposition.
Dans le calcul de la valeur d'habitat (module H), le coefficient de sol ne s'applique qu'aux unités d'un type d'habitat au sol posées sur dalle : une pelouse sur 40 cm de substrat vaut 0,7 fois une pelouse en pleine terre. Il vaut 1 en pleine terre et pour les types hors-sol (toitures, façades, jardinières), dont la valeur intrinsèque tient déjà compte du support. Dans Bnat, le coefficient de sol s'applique à toutes les surfaces.

## Contexte, eau et seuils

### C-E1
Catégorie : convention de transposition.
Le multiplicateur de situation de la métrique anglaise (importance stratégique 1,15, 1,10 ou 1) s'applique à la trame verte et bleue française : 1,15 dans un réservoir de biodiversité identifié par le SRADDET, le SRCE ou le PLU (article R. 371-19 du code de l'environnement), 1,10 dans un corridor identifié, 1 ailleurs. Il s'applique à l'état initial comme à l'état projet, pour ne jamais récompenser une destruction en réservoir.

### C-E2
Catégorie : source muette.
Aucune source ne module les délais de maturation par zone biogéographique en France. Les délais anglais s'appliquent tels quels dans les quatre zones. Pour les zones méditerranéenne et alpine, la plage d'incertitude du multiplicateur monte à 1,25, sans changer la valeur centrale.

### C-E3
Catégorie : valeur de secours.
La somme des surfaces des unités au sol doit égaler la surface de la parcelle à 0,5 % près, et au moins à 1 m² près. Au-delà, le calcul s'arrête à l'étape E2.

### C-E4
Catégorie : convention de transposition.
Un milieu en eau alimenté artificiellement (réseau d'eau potable, pompage) avec une eau non traitée voit sa valeur multipliée par 0,8. La borne basse vient de l'écart londonien entre eau semi-naturelle (1) et bassin nu ou eau chlorée (0,2), qui relève ici du type VB.21.

### C-E5
Catégorie : convention de transposition.
La part des eaux pluviales infiltrées ou gérées à ciel ouvert est un indicateur complémentaire. Elle entre dans Bnat par le bonus d'infiltration de Berlin (0,2 par m² de surface extérieure dont l'eau est infiltrée en surface).

### C-E6
Catégorie : convention de transposition.
Les seuils de Bnat sont ceux du coefficient de biotope par surface harmonisé du référentiel Effinature 8.1 (critère SOL 01.2.5), appliqués à Bnat / 100 : logements et équipements d'intérêt collectif, 45 si l'emprise du bâti va jusqu'à 37 %, 35 de 38 à 49 %, 25 au-delà ; commerces, bureaux et hébergements avec services, 25. S'y ajoute au moins 15 % de pleine terre végétalisée. Bnat et le CBSh n'emploient pas les mêmes coefficients (ceux du CBSh sont exclus pour leur licence) : l'écart entre les deux sur des projets réels sera mesuré au banc du lot 4.

### C-E7
Catégorie : convention de transposition.
Deux niveaux pour le site : niveau 1, absence de perte nette (Cnat au plus 0) ; niveau 2, gain net d'au moins 10 % de la valeur initiale intégrée sur 50 ans, transposé de l'objectif anglais (Town and Country Planning Act 1990, Schedule 7A, paragraphe 2(3)). Aucun seuil n'est proposé pour Inat en v1 : il sera fixé après le banc du lot 4, comme les seuils de l'Ic construction l'ont été après l'expérimentation E+C-.

## Connectivité

### C-C1
Catégorie : convention de transposition.
Facteur de taille d'une tache d'habitat : k = (A / A_ref)^z, borné entre 0,35 et 1. z = 0,22 (îles d'habitat, Matthews et al. 2016). A_ref = 4,4 ha (Beninde et al. 2015 : seuil moyen sous lequel les espèces adaptées à la ville déclinent). Le plancher 0,35 est la plus petite classe de fragmentation de GLOBIO 3.5 (taches de moins de 1 km²). A est la surface de la tache, augmentée des espaces végétalisés extérieurs au site qui lui sont contigus.

### C-C2
Catégorie : convention de transposition.
Multiplicateur de connectivité de la métrique anglaise : 1,15 pour une tache contiguë à un réservoir, à un corridor ou à un espace naturel extérieur d'au moins 1 ha ; 1,10 si un tel espace est à moins de 200 m, bord à bord, sans obstacle infranchissable ; 1 ailleurs. La distance de 200 m et la surface de 1 ha sont des valeurs de secours.

### C-C3
Catégorie : source muette.
Une tache entièrement close par une clôture sans passage pour la petite faune voit sa valeur multipliée par 0,9. Une clôture est perméable si elle offre au moins un passage de 15 x 15 cm tous les 20 m. Un muret plein de plus de 20 cm de haut est une clôture étanche.

## Nuisances

### C-N1
Catégorie : valeur de secours.
Trois classes d'éclairage par unité : L0, aucun point lumineux à moins de 50 m (facteur 1) ; L1, éclairage conforme à l'arrêté du 27 décembre 2018 et éteint en milieu de nuit (0,9) ; L2, éclairage non conforme ou permanent (0,75). Aucune relation dose-effet n'a été publiée (méta-analyse de Sanders et al. 2021) ; les distances d'évitement des chiroptères (Azam et al. 2018) bornent la distance d'influence.

### C-N2
Catégorie : convention de transposition.
Une unité exposée à un niveau sonore supérieur à 47 dB(A) en milieu ouvert, ou à 42 dB(A) en milieu boisé, voit sa valeur multipliée par 0,7, d'après la baisse de densité d'oiseaux nicheurs d'au moins 30 % au-delà de ces seuils (Reijnen et al. 1997).

### C-N3
Catégorie : source muette.
Une unité végétalisée à moins de 15 m d'une façade qui expose plus de 10 m² de vitrage non traité contre les collisions voit sa valeur multipliée par 0,95. La surface vitrée exposée non traitée est aussi affichée en indicateur.

## Temps

### C-T1
Catégorie : convention de transposition.
Simulation au pas annuel sur la période de référence de 50 ans de l'ACV de la RE2020 (annexe II de l'arrêté du 4 août 2021), avec une sortie complémentaire à 30 ans. Un habitat créé gagne sa valeur de façon linéaire, de 0 à la fin du chantier jusqu'à sa valeur cible au bout de son délai de maturité.

### C-T2
Catégorie : convention de transposition.
Le délai « 30+ » de la métrique anglaise vaut 32 ans : c'est l'année que la table des multiplicateurs temporels de l'onglet G-4 associe à « 30+ ».

### C-T3
Catégorie : convention de transposition.
Les types minéraux et scellés (VB.1 à VB.4, VB.25, H5.6), pour lesquels la métrique anglaise ne prévoit aucun délai, atteignent leur valeur dès la fin du chantier.

### C-T4
Catégorie : valeur de secours.
Une unité conservée perd 25 % de sa valeur pendant la durée du chantier quand aucune mesure de protection (clôture, balisage, interdiction de stockage et de circulation) n'est prévue.

### C-T5
Catégorie : convention de transposition.
Taux d'actualisation nul. Le taux anglais de 3,5 % par an est codé en option d'étude inactive.

### C-T6
Catégorie : valeur de secours.
La réfection d'une unité (toiture végétalisée refaite, par exemple) annule sa valeur pendant un an, puis la maturation reprend depuis 0.

### C-T7
Catégorie : convention de transposition.
La difficulté de création ou d'amélioration de la métrique anglaise (1 ; 0,67 ; 0,33 ; 0,1) multiplie la valeur cible d'un habitat créé ou amélioré : elle exprime le risque d'échec.

## Biodiversité grise

### C-G1
Catégorie : convention de transposition.
Inat porte sur les contributeurs « composants » et « chantier » de l'ACV de la RE2020, tels que le RSEE les donne (branche RSEnv). Les consommations d'énergie et d'eau en exploitation n'y entrent pas.

### C-G2
Catégorie : convention de transposition.
Facteur de caractérisation du climat, en m²HE par kg CO2 éq. (habitat équivalent perdu chaque année tant que le réchauffement dû à ce kg persiste ; multiplié par la durée de persistance en années, il donne des m²HE·an) : CF = S_terres x (1 - p) x (perte de MSA par °C / 2) x TCRE = 1,30e14 x 0,87 x (0,071 / 2) x 4,5e-16 = 1,807e-3. S_terres et p viennent du rapport spécial du GIEC sur les terres émergées (2019), la perte de MSA par °C et le facteur de retard de 2 de GLOBIO 3.5, la TCRE du sixième rapport du GIEC (2021). La perte par °C est la médiane non pondérée des 15 biomes de GLOBIO, faute de surfaces de biomes publiées sous licence libre.

### C-G3
Catégorie : valeur de secours.
Une émission pèse sur le reste de la période de référence : 50 ans pour la production et le chantier (A1 à A5), 25 ans en moyenne pour l'exploitation des composants (B), 0 pour la fin de vie (C). Le module D est exclu, comme pour l'Ic construction.

### C-G4
Catégorie : convention de transposition.
Occupation des sols du bois : 10 000 / P x R m²·an par m³ de produit, avec P = 5,4 m³ par ha et par an (production biologique moyenne des forêts françaises, IGN 2024) et R = 2 m³ de bois rond par m³ de produit (valeur de secours, plage 1,5 à 3,1).

### C-G5
Catégorie : convention de transposition.
La qualité d'habitat de la forêt d'origine dépend du régime déclaré (GLOBIO 3.5, table 2.2) : plantation 0,30, coupe rase 0,50, coupe sélective 0,70, exploitation à faible impact 0,85. La certification de gestion forestière n'apporte pas de coefficient : elle justifie le régime déclaré. Sans justification, le régime est la coupe rase.

### C-G6
Catégorie : valeur de secours.
Occupation des sols des granulats et roches : 0,9 m²·an par tonne (médiane de quatre carrières françaises, plage 0,3 à 1,6), avec une qualité d'habitat de 0,05 (classe la plus artificialisée de GLOBIO, aucune classe carrière n'existant).

### C-G7
Catégorie : source muette.
L'occupation des sols des usines (acier, verre, plastiques, isolants minéraux, plâtre) est négligée en v1, faute d'inventaire ouvert. C'est le principal écart connu du module G.

### C-G8
Catégorie : convention de transposition.
Les indicateurs de pollution du RSEE (acidification 106, eutrophisation 107 à 109, écotoxicité pour l'eau douce 116) sont repris tels quels et affichés à part, en unités de l'annexe II, faute de conversion sourcée vers la MSA.

## Relevés

### C-R1
Catégorie : valeur de secours.
Les critères floristiques se relèvent pendant la période où les espèces caractéristiques sont identifiables : en plaine, d'avril à juillet pour les prairies et pelouses, de mai à septembre pour les zones humides et mégaphorbiaies. Un relevé hors période répond « non » aux critères floristiques, sauf preuve contraire.

### C-R2
Catégorie : valeur de secours.
Les couverts et les nombres d'espèces par m² s'estiment sur au moins 5 quadrats de 1 m² par hectare, répartis sur un transect en W, et au moins 3 par unité de moins d'un hectare.

### C-R3
Catégorie : valeur de secours.
Les indicateurs de la grille des boisements se notent sur placettes de 10 m de rayon, au moins une par hectare et au moins trois par unité.

### C-R4
Catégorie : valeur de secours.
Les jardins privatifs d'une même opération se relèvent par échantillon d'au moins un jardin sur cinq ; les haies, par tronçons homogènes de 100 m au plus.

### C-R5
Catégorie : valeur de secours.
Un état initial dégradé volontairement dans les deux ans précédant le dépôt du permis (débroussaillage, labour, décapage) se saisit avec l'état antérieur documenté, pour ne pas récompenser une destruction préalable.

## Incertitude

### C-I1
Catégorie : valeur de secours.
Quand la source ne donne pas d'incertitude, la plage d'une valeur est de ± 10 % pour un texte réglementaire ou un outil officiel, ± 25 % pour un jugement d'expert publié, ± 50 % pour une convention OpenNAT.

### C-I2
Catégorie : valeur de secours.
L'analyse d'incertitude tire au moins 1 000 jeux de valeurs, chaque valeur suivant une loi triangulaire (borne basse, valeur centrale, borne haute). La graine du générateur est écrite dans le fichier projet et dans le RSNat. Les cinq paramètres les plus influents sont classés par la valeur absolue de leur corrélation de rang (Spearman) avec Snat.

## Bnat

### C-B1
Catégorie : convention de transposition.
Bnat = 100 x (somme des surfaces x coefficient de sol + bonus d'infiltration) / surface de la parcelle. Les surfaces hors-sol (toitures, façades) entrent au numérateur sans entrer au dénominateur, comme à Berlin et à Londres : Bnat peut dépasser 100. Les façades comptent pour 10 m de hauteur au plus.

## Attestation au permis de construire

### C-P1
Catégorie : convention de transposition.
Comme l'attestation de la RE2020 au dépôt du permis de construire, qui ne demande que le Bbio, l'attestation OpenNAT au permis porte sur les seules données du plan masse : Bnat et son seuil, le taux de pleine terre et, quand le PLU impose un coefficient de biotope ou une part de pleine terre, la comparaison avec ce minimum. Si le projet ne respecte pas le minimum du PLU, l'attestation le dit et ne conclut pas à la conformité. Les indicateurs Cnat, Inat et Snat sont attestés à l'achèvement des travaux.

## Bilan hydrique et climat

### C-W1
Catégorie : convention de transposition.
Le climat du calcul est l'année type horaire des fichiers climatiques prospectifs publiés en juin 2025 par l'ADEME, Météo-France, le CSTB et la DHUP (Licence Ouverte 2.0), à la station de référence de la zone climatique RE2020 du projet (H1a Trappes, H1b Nancy, H1c Mâcon, H2a Rennes, H2b Tours, H2c Agen, H2d Carpentras, H3 Marignane). La zone est celle du récapitulatif RE2020. Les fichiers météorologiques conventionnels de la RE2020 ne sont pas utilisés : le ministère en réserve l'usage au calcul réglementaire RE2020. La température est corrigée de l'altitude du site par le gradient de l'atmosphère type (0,0065 K par mètre, norme ISO 2533) et la pression ramenée à cette altitude.

### C-W2
Catégorie : convention de transposition.
L'évapotranspiration de référence horaire suit l'équation 53 de la FAO-56 (gazon de référence, résistance de surface de 70 s/m). Le rayonnement net suit les équations 37 à 40, la hauteur du soleil étant celle du fichier climatique ; la nuit, le rapport du rayonnement global au rayonnement par ciel clair garde la dernière valeur relevée avec un soleil à plus de 10° au-dessus de l'horizon. Contrôle : sur l'année type France +2 °C, l'évapotranspiration annuelle calculée vaut de 0,86 à 1,00 fois l'ETP Penman normale 1991-2020 de Météo-France aux huit stations (0,93 en moyenne), écart attendu entre la formule Penman de Météo-France et celle de Penman-Monteith. L'ETP de Météo-France sert au contrôle et n'entre pas dans le calcul.

### C-W3
Catégorie : valeur de secours.
La note technique des fichiers prospectifs indique que leurs cumuls de pluie sont proches des maxima des simulations, pas des médianes. Les pluies horaires de chaque année type sont donc recalées, mois par mois, sur la normale 1991-2020 de la station (données climatologiques de base mensuelles de Météo-France, Licence Ouverte 2.0) ; la chronologie des averses est conservée. Le cumul de pluie ne varie pas avec le réchauffement, la carte de la TRACC laissant incertain le signe de son évolution sur la plupart du territoire : le réchauffement agit par l'évapotranspiration. Cette convention sous-estime probablement les sécheresses d'été futures ; elle sera revue avec le conseil quand un jeu de pluies médianes par niveau de réchauffement sera publié.

### C-W4
Catégorie : valeur de secours.
La réserve utile d'un sol en pleine terre se calcule avec les teneurs en eau à la capacité au champ et au point de flétrissement du tableau 19 de la FAO-56 (milieu de plage) pour la classe de texture déclarée par le bureau d'études, et avec la profondeur d'enracinement, bornée par la profondeur de sol. À défaut de déclaration, le sol est un limon d'un mètre.

### C-W5
Catégorie : valeur de secours.
L'eau utile d'un substrat de toiture ou de dalle vaut 0,20 m³ par m³ (plage 0,12 à 0,30) quand le fabricant ne la déclare pas. La réserve vaut l'épaisseur du substrat multipliée par cette valeur ; la couche de drainage n'est pas comptée.

### C-W6
Catégorie : convention de transposition.
Chaque unité végétalisée appartient à un groupe hydrique (fiche A47) : gazon, prairie, végétation xérophile, arbustif, arboré, conifères, toiture extensive, milieu humide alimenté par la pluie, mare. Les coefficients culturaux en saison et hors saison, la profondeur racinaire et la fraction de réserve consommée sans réduction de la transpiration viennent des tableaux 12 et 22 de la FAO-56, par la culture la plus proche (gazon de saison fraîche, pâturage extensif, petits fruits arbustifs, vergers avec couvert, conifères, roselière, eau libre de moins de 2 m). Les milieux humides et les cours d'eau alimentés par une nappe ou un cours d'eau, les surfaces minérales, les façades et les bassins à eau traitée n'ont pas de bilan. La toiture extensive reçoit des valeurs de secours, la FAO-56 n'en donnant pas.

### C-W7
Catégorie : convention de transposition.
La saison de végétation est l'ensemble des heures où la moyenne des températures des six jours précédents dépasse 5 °C, d'après l'indice de durée de la saison de croissance de l'ETCCDI. Le coefficient cultural de saison s'applique pendant la saison, celui de repos en dehors.

### C-W8
Catégorie : convention de transposition.
Le stress hydrique d'une unité est l'indice de Granier et al. (1999), établi pour les peuplements forestiers : somme, sur la saison de végétation, de (0,4 - REW) / 0,4 quand la réserve extractible relative REW passe sous 0,4. Il est calculé au pas horaire et exprimé en jours-équivalents. Il est étendu à tous les groupes hydriques.

### C-W9
Catégorie : valeur de secours.
Le facteur hydrique vaut k_W = max(0,5 ; 1 - beta x (IS - IS_ref - 5) / 100), où IS_ref est l'indice du même groupe dans la situation de référence de la zone : limon d'un mètre (substrat de 10 cm pour une toiture extensive), sans arrosage ni apport, année type France +2 °C. La végétation d'une zone est adaptée à son climat : seul compte le stress que le projet ajoute par ses choix (substrat mince, sol pauvre ou peu profond) et que le réchauffement ajoute au fil de la période. La sensibilité beta va de 0,3 (végétation xérophile, toiture extensive) à 1,0 (milieu humide alimenté par la pluie) par 100 jours-équivalents ; la tolérance est de 5 jours-équivalents ; le plancher de 0,5 est celui du facteur d'état (C-H2).

### C-W10
Catégorie : valeur de secours.
Une unité déclarée arrosée reçoit chaque nuit, en saison de végétation, l'eau qui ramène sa réserve à la capacité au champ dès que la réserve extractible relative passe sous 0,5. Le volume d'arrosage annuel moyen de la période, en m³, est un indicateur complémentaire du RSNat ; il n'entre ni dans Cnat ni dans Snat.

### C-W11
Catégorie : valeur de secours.
Une mare a par défaut 0,8 m de profondeur et un fond argileux qui laisse s'infiltrer 0,3 mm par jour (fond étanche : 0 ; fond naturel : 1 mm par jour). Elle reçoit la pluie tombée sur elle, 90 % de celle tombée sur les surfaces imperméables raccordées et 10 % de celle tombée sur les sols perméables raccordés ; elle perd par évaporation 1,05 fois l'évapotranspiration de référence (eau libre de moins de 2 m, FAO-56). La noue et le jardin de pluie reçoivent les mêmes apports dans leur réserve en eau.

### C-W12
Catégorie : valeur de secours.
Une mare à sec à un moment quelconque entre le 1er mars et le 30 juin manque la reproduction des amphibiens de l'année : sa valeur est multipliée par 0,5 pour l'année type concernée. Un assec de fin d'été n'est pas pénalisé, il reproduit le fonctionnement des mares temporaires.

### C-W13
Catégorie : convention de transposition.
Chaque année civile de la période reçoit le niveau de réchauffement de la trajectoire de référence pour l'adaptation au changement climatique (TRACC, arrêté du 23 janvier 2026) : +2 °C en 2030, +2,7 °C en 2050, +4 °C en 2100, interpolation linéaire entre ces jalons, +2 °C avant 2030. Les grandeurs calculées sur les trois années types (facteur hydrique, arrosage) sont interpolées linéairement en niveau de réchauffement. La période commence l'année de début du chantier (C-K3). Le climat agit sur l'état initial comme sur le projet : la référence sans projet subit le même réchauffement.

## Éclairage

### C-L1
Catégorie : valeur de secours.
Le facteur d'éclairage d'une unité vaut k_L = 1 - 0,25 x x_nuit x s x g, où x_nuit est la part des heures de nuit de la saison d'activité des chiroptères pendant lesquelles l'éclairage est allumé, s le facteur spectral et g le facteur de distance. La perte maximale de 0,25 reproduit le facteur de 0,75 de l'éclairage non conforme de la v1 (C-N1) : un éclairage froid allumé toute la nuit au pied de l'unité. Un éclairage conforme à l'arrêté du 27 décembre 2018 (3 000 K, extinction à 1 h) donne 0,916, contre 0,9 en v1. Une détection de présence multiplie x_nuit par la part de temps allumé déclarée.

### C-L2
Catégorie : valeur de secours.
La saison d'activité des chiroptères va d'avril à octobre. Une heure est de nuit quand le soleil est sous l'horizon à la station de référence (hauteur du soleil du fichier climatique). L'heure légale vaut l'heure universelle plus deux heures d'avril à octobre et plus une heure le reste de l'année, à une semaine près des dates du changement d'heure.

### C-L3
Catégorie : convention de transposition.
Le facteur de distance vaut 1 jusqu'à 10 m du luminaire le plus proche, 0 au-delà de 50 m, et décroît linéairement entre les deux, d'après les distances d'attraction et d'évitement mesurées par Azam et al. (2018) sur six espèces et deux genres de chiroptères.

### C-L4
Catégorie : valeur de secours.
Le facteur spectral croît avec la température de couleur : 0,5 en ambre (1 800 K), 0,6 à 2 200 K, 0,7 à 2 400 K, 0,8 à 2 700 K, 0,9 à 3 000 K, 1 à 4 000 K et au-delà, interpolé entre ces points. Les points de 2 400, 2 700 et 3 000 K sont les plafonds de l'arrêté du 27 décembre 2018 ; la croissance suit le constat de Longcore et al. (2018), qui attribuent plus d'effets aux sources riches en bleu ; l'échelonnement est conventionnel.

## Maturation et gestion

### C-M1
Catégorie : convention de transposition.
La maturation d'un milieu créé suit une courbe de Chapman-Richards (Richards, 1959 ; Pienaar et Turnbull, 1973) ramenée à 1 au délai de maturité d de la métrique anglaise : g(tau) = [(1 - e^(-3 tau / d)) / (1 - e^(-3))]^c. L'exposant c vaut 1 pour un milieu herbacé (courbe concave : une prairie semée donne vite la plus grande part de sa valeur), 2 pour un milieu arbustif, 3 pour un milieu arboré (courbe en S : un boisement planté vaut peu les premières années). Cette courbe remplace la droite de la première rédaction ; le délai et la difficulté restent ceux de la métrique anglaise.

### C-M2
Catégorie : convention de transposition.
Un milieu herbacé dont l'entretien cesse prend, après deux ans, la valeur de sa variante abandonnée dans l'annexe 2 de la BKompV (rapport du type « brachgefallen » au type exploité, onze couples de 0,73 à 0,91, moyenne appliquée aux types sans couple), puis devient après quinze ans une friche arbustive (VB.9) en état moyen (score 0,75). Les milieux ligneux et aquatiques ne sont pas soumis à cette succession dans la présente version.

### C-M3
Catégorie : valeur de secours.
Le plan de gestion écologique déclaré couvre un nombre d'années compté depuis la livraison. Pendant le plan, chaque unité garde sa valeur. Ensuite, sauf déclaration d'un autre régime, l'unité passe en gestion courante : une prairie ou une pelouse sèche est tondue comme une pelouse d'agrément et prend la valeur intrinsèque de VB.5 ; une haie est taillée chaque année et garde la valeur du stade jeune de la BKompV ; les jardins, les pelouses, les toitures et les autres milieux ne changent pas. L'état initial garde sa gestion actuelle sur toute la période.

## Calendrier du chantier

### C-K1
Catégorie : valeur de secours.
La destruction d'une unité de l'état initial pendant une période sensible ajoute à Cnat sa valeur pendant une année, multipliée par la sensibilité du mois de destruction : oiseaux nicheurs (ligneux, prairies et friches) du 16 mars au 15 août, d'après la BCAE 8 de la conditionnalité de la PAC ; chiroptères (arbres âgés, gîtes déclarés) de novembre à février (hibernation) et de mai à août (mise bas) ; amphibiens (mares, fossés, milieux humides) de février à juin. Le mois de destruction est celui déclaré, à défaut le premier mois du chantier.

### C-K2
Catégorie : valeur de secours.
Les plantations et les semis se font de novembre à mars, au plus tôt deux mois avant la fin du chantier : la maturation d'un milieu planté ou semé commence au premier mois favorable. Un milieu sans délai de maturité (revêtement, jardin d'un an et moins) est livré à la fin du chantier.

### C-K3
Catégorie : valeur de secours.
La période de 50 ans commence au premier mois du chantier, déclaré par son année et son mois (à défaut, janvier 2027). Les trajectoires se calculent au pas mensuel puis se moyennent par année.

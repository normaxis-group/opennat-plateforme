# Bibliographie de la méthode OpenNAT v1

Sources dont une valeur entre dans `donnees/` ou dont une règle fonde une équation. Les documents ont été lus le 18 octobre 2026 ; leurs copies de travail sont conservées hors dépôt (`sources/`). Les sources de l'audit du lot 0 qui ne fournissent aucune valeur à la v1 restent décrites dans `audit/audit.md` et `audit/fiches/`.

## Textes réglementaires et documents officiels

| Référence | Ce qui en est tiré | Lien | Licence |
|---|---|---|---|
| Verordnung über die Vermeidung und die Kompensation von Eingriffen in Natur und Landschaft im Zuständigkeitsbereich der Bundesverwaltung (BKompV), 14 juin 2020, BGBl. I 2020, 1088, annexe 2 (Biotopwertliste) | 668 valeurs de biotopes de 0 à 24, extraites dans `sources/biotopwert_de/bkompv_anlage2.csv` | https://www.gesetze-im-internet.de/bkompv/anlage_2.html | texte réglementaire, § 5 UrhG |
| Defra et Natural England, The Statutory Biodiversity Metric, calculation tool 1.0.4 (version sans macro) | distinctivités (G-1), difficultés et multiplicateurs (G-3), délais de maturité (G-4), haies (G-6), surfaces des arbres (G-3) | https://assets.publishing.service.gov.uk/media/6867e62810d550c668de3b4e/The_Statutory_Metric_Macro_Disabled_1.0.4.xlsx | Open Government Licence v3.0 |
| Defra et Natural England, Statutory Biodiversity Metric Condition Assessments, juillet 2025 | grilles d'état 4A à 24A | https://assets.publishing.service.gov.uk/media/689c5f19d2a1b0d5d1bb1254/Statutory_Biodiversity_Metric_Condition_Assessments_-_July_2025.xlsx | Open Government Licence v3.0 |
| Environment Act 2021, Schedule 14 (Schedule 7A du Town and Country Planning Act 1990), paragraphes 2(3) et 9(3) | gain net de 10 %, durée de 30 ans | https://www.legislation.gov.uk/ukpga/2021/30/schedule/14 | Open Government Licence v3.0 |
| Arrêté du 4 août 2021 relatif aux exigences de performance énergétique et environnementale des constructions de bâtiments, annexe II | période d'étude de référence de 50 ans, liste et ordre des indicateurs de l'ACV | https://rt-re-batiment.developpement-durable.gouv.fr/IMG/pdf/annexeii_arrete_4_aout_2021.pdf | Licence Ouverte 2.0 |
| Arrêté du 27 décembre 2018 relatif à la prévention, à la réduction et à la limitation des nuisances lumineuses, modifié | horaires d'extinction (article 2), plafonds de température de couleur de 3 000, 2 700 et 2 400 K (articles 3 et 4), raccourci L1 | https://aida.ineris.fr/reglementation/arrete-271218-relatif-a-prevention-a-reduction-a-limitation-nuisances-lumineuses | Licence Ouverte 2.0 |
| Senatsverwaltung Berlin, Biotopflächenfaktor, BFF-Berechnung | coefficients de sol, bonus d'infiltration, eau de pluie | https://www.berlin.de/sen/uvk/natur-und-gruen/landschaftsplanung/bff-biotopflaechenfaktor/bff-berechnung/ | texte administratif public |
| Greater London Authority, London Plan Guidance, Urban Greening Factor, février 2023, table 3.1 | coefficients de sol, eau | https://www.london.gov.uk/sites/default/files/2023-02/London%20Plan%20Guidance%20-%20Urban%20Greening%20Factor.pdf | Open Government Licence v3.0 |
| Eurométropole de Strasbourg, PLU, règlement écrit, dispositions applicables à toutes les zones, modification n° 4 (mai 2024), article 9 | coefficients de sol | https://sig.strasbourg.eu/datastrasbourg/plu_media/reglement_actuel.pdf | document d'urbanisme public |
| Grand Annecy, PLUi-HMB, fiche d'aide à l'instruction des coefficients de biotope et de pleine terre | coefficients de sol | https://plui-hmb-approuve-ga.grandannecy.fr/04_REGLEMENT/FICHE_AIDE_instruction_CBS.pdf | document d'urbanisme public |
| Ville de Paris, guide des règles environnementales du PLU bioclimatique, juillet 2025, règle UG.4 (p. 63) | coefficients de l'indice de végétalisation du bâti | https://cdn.paris.fr/paris/2025/07/17/250715_guideenvironnemental-tfz6.pdf | document d'urbanisme public |
| Métropole européenne de Lille, PLU, tableau du coefficient de biotope par surface (extrait du Pacte Lille Bas Carbone, 18/10/2022) | coefficients de sol | https://www.nature-en-ville.com/sites/nature-en-ville/files/document/2025-06/Lille_CBS_calcul_tableau.pdf | document d'urbanisme public |
| Arrêté du 23 janvier 2026 et décret n° 2026-23 relatifs à la trajectoire de réchauffement de référence pour l'adaptation au changement climatique (TRACC) | jalons +2 °C en 2030, +2,7 °C en 2050, +4 °C en 2100 (fiche A46) | https://www.ecologie.gouv.fr/sites/default/files/documents/27012026_DP_TRACC.pdf | Licence Ouverte 2.0 |
| ADEME, Météo-France, CSTB, DHUP, Données climatiques prospectives France +2 °C, +2,7 °C et +4 °C, juin 2025, et note technique détaillée version 3.0 du 23 mai 2025 | années types horaires des huit stations de référence, stations, limites des pluies (fiches A43 à A45) | https://data.ademe.fr/datasets/donnees-climatologiques-prospectives-france-2c | Licence Ouverte 2.0 |
| Météo-France, Données climatologiques de base mensuelles, fichiers MENSQ des départements 13, 35, 37, 47, 54, 71, 78 et 84 | normales de pluie 1991-2020, ETP Penman de contrôle (`climat.csv`) | https://www.data.gouv.fr/datasets/donnees-climatologiques-de-base-mensuelles | Licence Ouverte 2.0 |
| Ministère de l'Agriculture, conditionnalité de la PAC 2023-2027, BCAE 8 | interdiction de tailler haies et arbres du 16 mars au 15 août (fiche A57) | https://agriculture.gouv.fr/telecharger/135138 | Licence Ouverte 2.0 |
| NORMATYS, référentiel Effinature 8.1, critère SOL 01.2.5 | seuils du coefficient de biotope et de la pleine terre | non public | propriété de NORMATYS |

## Modèles et données scientifiques

| Référence | Ce qui en est tiré | Lien | Licence |
|---|---|---|---|
| PBL Netherlands Environmental Assessment Agency, 2016, GLOBIO 3.5 Technical model description, publication 2369 | MSA par usage des sols (table 2.2), perte de MSA par °C (table 3.1) et facteur de retard (§ 3.3), fragmentation (table 6.1) | https://www.pbl.nl/downloads/pbl-publication-2369pdf | reproduction autorisée avec mention de la source |
| GIEC, 2021, Climate Change 2021, The Physical Science Basis, résumé à l'intention des décideurs, D.1.1 | réponse transitoire du climat aux émissions cumulées (0,45 °C par 1 000 GtCO2) | https://www.ipcc.ch/report/ar6/wg1/downloads/report/IPCC_AR6_WGI_SPM.pdf | citation de valeur |
| GIEC, 2019, Climate Change and Land, résumé à l'intention des décideurs, figure SPM.1 | surface terrestre libre de glace (130 Mkm²), parts des cultures et des infrastructures | https://www.ipcc.ch/site/assets/uploads/sites/4/2022/11/SRCCL_SPM.pdf | citation de valeur |
| IGN, Mémento de l'inventaire forestier, édition 2024, p. 38 et 39 | production biologique des forêts françaises (5,4 m³/ha/an) | https://www.ign.fr/publications-de-l-ign/institut/kiosque/publications/docs_thematiques/memento-2024.pdf | publication publique |
| Beninde J., Veith M., Hochkirch A., 2015, Biodiversity in cities needs space, Ecology Letters 18, 581-592 | seuil de 4,4 ha pour les espèces adaptées à la ville | https://www.uni-trier.de/fileadmin/fb6/prof/BIO/Hochkirch/Beninde_et_al-2015-Ecology_Letters.pdf | article, citation |
| PatriNat, HabRef v7 (07/08/2025) | libellés français et présence en France des habitats EUNIS 2012 de niveau 3 | https://assets.patrinat.fr/files/referentiel/HABREF.zip | licence non lue (serveurs de l'INPN hors service) |

| Allen R. G., Pereira L. S., Raes D., Smith M. (1998), Crop evapotranspiration, Guidelines for computing crop water requirements, FAO Irrigation and Drainage Paper 56 | équation de Penman-Monteith horaire (53), rayonnement (37 à 40), flux de chaleur du sol (45, 46), vent (47), coefficient de stress (84), coefficients culturaux (tableau 12), teneurs en eau des sols (tableau 19), profondeurs racinaires et fractions p (tableau 22) | https://www.fao.org/4/x0490e/x0490e00.htm | reproduction autorisée à des fins non commerciales, valeurs citées |
| Granier A., Bréda N., Biron P., Villette S. (1999), A lumped water balance model to evaluate duration and intensity of drought constraints in forest stands, Ecological Modelling 116, 269-283 | seuil de réserve extractible relative de 0,4 et indice de stress (fiche A49) | https://doi.org/10.1016/S0304-3800(98)00205-1 | méthode citée |
| ETCCDI, liste des 27 indices climatiques, indice GSL | seuil de 5 °C sur six jours de la saison de végétation | https://etccdi.pacificclimate.org/list_27_indices.shtml | définition citée |
| Azam C., Le Viol I., Bas Y., Zissis G., Vernet A., Julien J.-F., Kerbiriou C. (2018), Evidence for distance and illuminance thresholds in the effects of artificial lighting on bat activity, Landscape and Urban Planning 175, 123-135 | distances de 10 et 50 m (fiche A52) ; lu par son résumé | https://doi.org/10.1016/j.landurbplan.2018.02.011 | résultat cité |
| Longcore T., Rodríguez A., Witherington B., Penniman J. F., Herf L., Herf M. (2018), Rapid assessment of lamp spectrum to quantify ecological effects of light at night, Journal of Experimental Zoology A 329, 511-521 | sens du facteur spectral (fiche A52) ; lu par son résumé | https://doi.org/10.1002/jez.2184 | résultat cité |
| Richards F. J. (1959), A flexible growth function for empirical use, Journal of Experimental Botany 10, 290-300 | fonction de croissance (fiche A54) | https://doi.org/10.1093/jxb/10.2.290 | méthode citée |
| Pienaar L. V., Turnbull K. J. (1973), The Chapman-Richards generalization of Von Bertalanffy's growth model for basal area growth and yield in even-aged stands, Forest Science 19, 2-22 | exposant de forme (fiche A54) | https://doi.org/10.1093/forestscience/19.1.2 | méthode citée |

## Sources secondaires à remplacer par la source primaire

Valeurs lues au lot 0 dans l'audit et non relues au lot 1 ; chacune est marquée « secondaire » dans les tables.

| Référence | Valeur | Raison |
|---|---|---|
| Matthews T. J. et al., 2016, On the form of species-area relationships in habitat islands and true islands, Global Ecology and Biogeography 25 | exposant z = 0,22 (îles d'habitat) | dépôt de l'université de Birmingham refusé (erreur 403) le 18/10/2026 |
| Azam C. et al., 2018, et Rowse E. G. et al., 2018 (via EUROBATS) | distances d'évitement des chiroptères | articles non téléchargés |
| Reijnen R. et al., 1997, Disturbance by traffic of breeding birds | seuils de bruit, baisse de densité | article non téléchargé |
| Dossiers d'enquête publique de quatre carrières (Pouzilhac, Valliguières, Montreuil-au-Houlme, Saint-Colomban) | occupation par tonne | lus dans des extraits de recherche |
| FCBA, Mémento 2025-2026 | borne haute du rapport bois rond / produit | lu dans un extrait de recherche |

## Sources écartées pour leur licence

Global Biodiversity Score (CC BY-NC), grille CBSh du CSTB (usage commercial interdit), webservice INIES (interdiction de tout classement ou notation des déclarations). Voir l'audit, § 5, point P14.

# CLAUDE.md

Plateforme en ligne d'OpenNAT, éditée par IRICE : `https://opennat.irice-certification.com`. Laravel 13, Livewire 4 et
Volt, Filament 5, Tailwind 3 (PostCSS, comme BPS), PHPUnit. Dépôt `normaxis-group/opennat-plateforme` (public,
AGPL-3.0-or-later, aucun secret dans le dépôt).

## Ce que fait l'application

- **Public** : accueil (`resources/views/accueil.blade.php`), conditions d'utilisation, confidentialité, connexion,
  demande de compte, mot de passe oublié.
- **Comptes** : inscription libre, adresse à confirmer (lien signé, sans session : `VerifyEmailController`), compte
  inactif tant qu'IRICE ne l'a pas validé dans l'administration (`/admin`, Filament, action « Valider » qui prévient le
  titulaire). `EnsureAccountIsActive` éjecte un compte désactivé à la requête suivante ; `LoginForm` refuse une adresse
  non confirmée ou un compte en attente. L'inscription n'ouvre que si mosparo est configuré (projet OpenNAT sur
  `spam.normaxis.com`, variables `MOSPARO_*`) ; sans les trois valeurs, le formulaire est remplacé par un message.
- **Zone connectée** (`auth`, `verified`, `actif`) : espace et historique des calculs, recueil de la méthode
  (`/methode`, Markdown rendu avec sommaire et PDF), outil de calcul (`/outil`).
- **Outil** : le moteur OpenNAT (dépôt `normaxis-group/opennat`, utilitaire en mode public `OPENNAT_PUBLIC=1`) tourne
  dans une autre application Coolify, sans domaine, jointe par `OPENNAT_MOTEUR_URL` (alias réseau). `OutilController`
  relaie `/outil`, `/statique/*` et `/api/*` : chaque calcul est enregistré dans `calculs` au nom du compte, seul son
  propriétaire en ouvre l'état, les résultats et les fichiers (404 sinon), le moteur reçoit `compte-<id>` comme client
  (un calcul simultané par compte). `public/outil-csrf.js` ajoute le jeton CSRF aux appels de la page du moteur.

## Règles

- Contenu en français ; aucun chiffre en dur dans une vue : `config('opennat.*')`.
- Outil hors de la portée d'accréditation d'IRICE : **aucune** mention d'accréditation ni de Cofrac, rien d'écrit à la
  place (règle 12 du groupe). Directive (UE) 2024/825 : un niveau du calcul se cite avec la version de la méthode, le
  périmètre et les valeurs de Cnat et d'Inat. Le Biodiversity Standard Council est précisé à sa première occurrence
  (« comité scientifique et technique des référentiels NORMATYS »). Le nom social de NORMATYS ne s'écrit jamais.
- Recueil : `php artisan opennat:recueil [--revision=…]` copie les textes et PDF du dépôt OpenNAT dans
  `resources/recueil`, puis on committe ; l'image sert ce qui est dans le dépôt.
- `public/build` est versionné (l'image ne lance pas npm) : `npm run build` puis `git add public/build` d'un bloc.

## Commandes

```bash
php artisan test            # PHPUnit, SQLite en mémoire, moteur simulé (Http::fake)
npm run build
./vendor/bin/pint
php artisan opennat:recueil
```

En local, le moteur se lance depuis le dépôt OpenNAT :
`OPENNAT_PUBLIC=1 python -m opennat.utilitaire --sans-navigateur --port 8771` et `OPENNAT_MOTEUR_URL=http://127.0.0.1:8771`.

## Déploiement

Skill global `deployer-site`. Base MariaDB partagée de SERV2 (base `opennat`), mails par le SMTP d'IRICE (variables
reprises de BPS), migrations jouées à la main après déploiement.

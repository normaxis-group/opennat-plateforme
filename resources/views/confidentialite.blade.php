<x-layouts.public titre="Confidentialité | OpenNAT">
    <article class="mx-auto max-w-3xl px-4 py-12 prose-recueil">
        <h1>Politique de confidentialité</h1>
        <p>Version du 20 octobre 2026. Responsable du traitement : IRICE, 10 rue du Lieutenant Parayre, 13290 Aix-en-Provence (SIREN 835 324 112), hello@irice-certification.com.</p>

        <h2>Données traitées</h2>
        <ul>
            <li><strong>Compte</strong> : prénom, nom, adresse électronique, organisation, fonction et téléphone s'ils sont renseignés, mot de passe (conservé sous forme chiffrée), dates de la demande, de la confirmation de l'adresse et de la validation.</li>
            <li><strong>Historique des calculs</strong> : identifiant et nom du projet, nombre de tirages, date et état de chaque calcul.</li>
            <li><strong>Projets envoyés à l'outil</strong> et leurs résultats : effacés du serveur {{ config('opennat.conservation_heures') }} heures après le calcul.</li>
            <li><strong>Données techniques</strong> : journaux du serveur (adresse IP, date, page demandée), cookies de session et de sécurité indispensables à la connexion. Aucune mesure d'audience, aucun cookie publicitaire.</li>
        </ul>

        <h2>Finalités et bases légales</h2>
        <ul>
            <li>Ouvrir et gérer les comptes, donner accès à l'outil et au recueil : exécution des conditions d'utilisation.</li>
            <li>Protéger le formulaire de demande contre les envois automatisés (anti-spam mosparo, instance du groupe) et sécuriser la plateforme : intérêt légitime d'IRICE.</li>
        </ul>

        <h2>Destinataires</h2>
        <p>Les données sont traitées par IRICE et hébergées sur ses serveurs. Elles ne sont ni vendues ni cédées.</p>

        <h2>Durées de conservation</h2>
        <p>Les données du compte et l'historique des calculs sont conservés pendant la durée du compte et supprimés à sa fermeture. Les projets envoyés à l'outil et leurs résultats le sont {{ config('opennat.conservation_heures') }} heures après le calcul. Les données du formulaire de demande vérifiées par l'anti-spam mosparo y sont conservées sous forme chiffrée pendant 14 jours.</p>

        <h2>Vos droits</h2>
        <p>Vous disposez d'un droit d'accès, de rectification, d'effacement, de limitation, d'opposition et de portabilité : écrivez à hello@irice-certification.com. Vous pouvez aussi saisir la CNIL (www.cnil.fr).</p>
    </article>
</x-layouts.public>

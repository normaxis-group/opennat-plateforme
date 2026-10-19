<x-layouts.public titre="Conditions d'utilisation | OpenNAT">
    <article class="mx-auto max-w-3xl px-4 py-12 prose-recueil">
        <h1>Conditions d'utilisation de la plateforme OpenNAT</h1>
        <p>Version du 20 octobre 2026. La plateforme opennat.irice-certification.com est éditée par IRICE (10 rue du Lieutenant Parayre, 13290 Aix-en-Provence, SIREN 835 324 112).</p>

        <h2>Objet</h2>
        <p>La plateforme donne accès, sur compte, à l'outil de calcul OpenNAT édité par IRICE et au recueil de la méthode OpenNAT éditée par NORMATYS.</p>

        <h2>Compte</h2>
        <ul>
            <li>Le compte est personnel. Son titulaire garde son mot de passe secret et répond de l'usage qui en est fait.</li>
            <li>L'accès est ouvert après confirmation de l'adresse et validation de la demande par IRICE, qui peut refuser une demande ou désactiver un compte, notamment en cas d'usage contraire aux présentes conditions.</li>
            <li>Le titulaire peut demander la fermeture de son compte à hello@irice-certification.com.</li>
        </ul>

        <h2>Calculs et résultats</h2>
        <ul>
            <li>L'utilisateur répond des données qu'il saisit ou importe. Les projets envoyés et leurs résultats sont effacés du serveur {{ config('opennat.conservation_heures') }} heures après le calcul ; l'utilisateur enregistre ses fichiers sur son poste.</li>
            <li>La méthode OpenNAT est en attente de validation du Biodiversity Standard Council : ses seuils sont des propositions et ses résultats ont une valeur indicative.</li>
            <li>Un résultat OpenNAT est le calcul d'un projet à une version donnée de la méthode et des données. La certification d'une opération relève d'un organisme de certification, selon le référentiel qu'il applique. Un niveau du calcul repris dans une communication s'accompagne de la version de la méthode, du périmètre et des valeurs de Cnat et d'Inat.</li>
        </ul>

        <h2>Disponibilité</h2>
        <p>IRICE fait ses meilleurs efforts pour tenir la plateforme disponible, sans garantie de continuité. Le nombre de calculs simultanés et le nombre de tirages par calcul sont limités.</p>

        <h2>Propriété intellectuelle</h2>
        <p>Le texte de la méthode et les tables sont publiés sous licence Creative Commons Attribution 4.0, le moteur et l'outil de calcul sous licence GNU Affero General Public License, version 3 ou ultérieure. Le nom OpenNAT, les logos et la présentation de la plateforme ne sont pas couverts par ces licences.</p>

        <h2>Données personnelles</h2>
        <p>Voir la <a href="{{ route('confidentialite') }}">politique de confidentialité</a>.</p>
    </article>
</x-layouts.public>

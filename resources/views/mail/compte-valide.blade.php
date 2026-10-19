<x-mail::message>
# Votre compte OpenNAT est ouvert

Bonjour {{ $user->first_name }},

IRICE a validé votre compte. Vous pouvez vous connecter à la plateforme OpenNAT pour utiliser l'outil de calcul et consulter le recueil de la méthode.

<x-mail::button :url="route('login')">
Se connecter
</x-mail::button>

Les projets envoyés à l'outil et leurs résultats sont effacés du serveur {{ config('opennat.conservation_heures') }} heures après le calcul : enregistrez votre fichier projet et vos résultats sur votre poste.

IRICE, éditeur de l'outil de calcul OpenNAT
</x-mail::message>

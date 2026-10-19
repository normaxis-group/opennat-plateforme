<?php

// Messages de validation employés par la plateforme OpenNAT (sous-ensemble des règles utilisées).
return [
    'accepted' => 'Le champ :attribute doit être accepté.',
    'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
    'email' => 'Le champ :attribute doit être une adresse électronique valide.',
    'lowercase' => 'Le champ :attribute doit être en minuscules.',
    'max' => ['string' => 'Le champ :attribute ne doit pas dépasser :max caractères.'],
    'min' => ['string' => 'Le champ :attribute doit contenir au moins :min caractères.'],
    'required' => 'Le champ :attribute est obligatoire.',
    'string' => 'Le champ :attribute doit être un texte.',
    'unique' => 'Cette :attribute est déjà utilisée.',
    'password' => [
        'letters' => 'Le mot de passe doit contenir au moins une lettre.',
        'mixed' => 'Le mot de passe doit contenir au moins une majuscule et une minuscule.',
        'numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
        'symbols' => 'Le mot de passe doit contenir au moins un symbole.',
        'uncompromised' => 'Ce mot de passe figure dans une fuite de données : choisissez-en un autre.',
    ],
    'attributes' => [
        'first_name' => 'prénom',
        'last_name' => 'nom',
        'email' => 'adresse électronique',
        'organisation' => 'organisation',
        'fonction' => 'fonction',
        'phone' => 'téléphone',
        'password' => 'mot de passe',
        'form.email' => 'adresse électronique',
        'form.password' => 'mot de passe',
    ],
];

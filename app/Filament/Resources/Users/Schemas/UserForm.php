<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')->label('Prénom')->required()->maxLength(120),
                TextInput::make('last_name')->label('Nom')->required()->maxLength(120),
                TextInput::make('email')->label('Adresse électronique')->email()->required()->disabled(),
                TextInput::make('organisation')->label('Organisation')->maxLength(160),
                TextInput::make('fonction')->label('Fonction')->maxLength(120),
                TextInput::make('phone')->label('Téléphone')->maxLength(30),
                Toggle::make('is_active')->label('Compte actif')
                    ->helperText('Utiliser plutôt l\'action « Valider » de la liste : elle prévient le titulaire.'),
                Toggle::make('is_admin')->label('Administrateur IRICE')
                    ->helperText('Ouvre l\'administration des comptes.'),
            ]);
    }
}

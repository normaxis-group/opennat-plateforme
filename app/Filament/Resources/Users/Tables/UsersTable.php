<?php

namespace App\Filament\Resources\Users\Tables;

use App\Mail\CompteValide;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')->label('Nom')->searchable(['first_name', 'last_name', 'name'])->sortable(),
                TextColumn::make('email')->label('Adresse')->searchable()->copyable(),
                TextColumn::make('organisation')->label('Organisation')->searchable()->wrap(),
                TextColumn::make('fonction')->label('Fonction')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')->label('Téléphone')->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('email_verified_at')->label('Adresse confirmée')->boolean()
                    ->getStateUsing(fn (User $u) => $u->email_verified_at !== null)->alignCenter(),
                IconColumn::make('is_active')->label('Actif')->boolean()->alignCenter(),
                IconColumn::make('is_admin')->label('Admin')->boolean()->alignCenter(),
                TextColumn::make('calculs_count')->label('Calculs')->counts('calculs')->alignCenter()->sortable(),
                TextColumn::make('created_at')->label('Demande')->dateTime('d/m/Y H:i', 'Europe/Paris')->sortable(),
                TextColumn::make('validated_at')->label('Validé le')->dateTime('d/m/Y H:i', 'Europe/Paris')->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('en_attente')->label('Demandes en attente')
                    ->query(fn (Builder $q) => $q->where('is_active', false)->whereNull('validated_at')),
                TernaryFilter::make('is_active')->label('Actif'),
                TernaryFilter::make('email_verified_at')->label('Adresse confirmée')->nullable(),
            ])
            ->recordActions([
                Action::make('valider')->label('Valider')->icon('heroicon-o-check-circle')->color('success')
                    ->visible(fn (User $u) => ! $u->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Valider ce compte ?')
                    ->modalDescription(fn (User $u) => $u->email_verified_at
                        ? 'Le titulaire reçoit un message et accède à l\'outil et au recueil.'
                        : 'Attention : l\'adresse n\'est pas encore confirmée. Le titulaire ne pourra se connecter qu\'après l\'avoir confirmée.')
                    ->action(function (User $u): void {
                        $u->forceFill(['is_active' => true, 'validated_at' => now(), 'validated_by' => auth()->id()])->save();
                        try {
                            Mail::to($u->email)->send(new CompteValide($u));
                        } catch (\Throwable $e) {
                            Log::warning('OpenNAT : échec du mail de validation : '.$e->getMessage());
                        }
                        Notification::make()->title('Compte validé, titulaire prévenu')->success()->send();
                    }),
                Action::make('desactiver')->label('Désactiver')->icon('heroicon-o-no-symbol')->color('danger')
                    ->visible(fn (User $u) => $u->is_active && $u->id !== auth()->id())
                    ->requiresConfirmation()
                    ->modalHeading('Désactiver ce compte ?')
                    ->modalDescription('La session en cours est coupée à la requête suivante ; l\'historique des calculs est conservé.')
                    ->action(fn (User $u) => $u->forceFill(['is_active' => false])->save()),
                EditAction::make(),
            ]);
    }
}

<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Password::defaults(fn () => Password::min(12)->letters()->mixedCase()->numbers());

        VerifyEmail::toMailUsing(fn ($notifiable, string $url) => (new MailMessage)
            ->subject('OpenNAT : confirmez votre adresse')
            ->greeting('Bonjour '.($notifiable->first_name ?? '').',')
            ->line('Confirmez votre adresse électronique pour poursuivre votre demande de compte OpenNAT.')
            ->action('Confirmer mon adresse', $url)
            ->line('Ce lien est valable 60 minutes. Si vous n\'avez rien demandé, ignorez ce message.')
            ->salutation('IRICE, éditeur de l\'outil de calcul OpenNAT'));

        ResetPassword::toMailUsing(fn ($notifiable, string $token) => (new MailMessage)
            ->subject('OpenNAT : nouveau mot de passe')
            ->line('Vous avez demandé un nouveau mot de passe pour votre compte OpenNAT.')
            ->action('Choisir un nouveau mot de passe', url(route('password.reset', [
                'token' => $token, 'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('Ce lien est valable 60 minutes. Si vous n\'avez rien demandé, ignorez ce message.')
            ->salutation('IRICE, éditeur de l\'outil de calcul OpenNAT'));
    }
}

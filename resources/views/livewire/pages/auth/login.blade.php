<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('espace', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __("Connexion") }}</h2>
        <p class="text-sm text-gray-600 mt-2">Outil de calcul et recueil de la méthode OpenNAT</p>
    </div>

    @if(session('registration-success'))
        <div class="mb-4 rounded-lg bg-bps-50 border border-bps-200 px-4 py-3 text-sm text-bps-800">
            <strong>{{ __("Demande de création de compte envoyée.") }}</strong><br>
            {{ __("Votre compte est en attente de validation par un administrateur. Vous recevrez un email dès qu'il sera activé.") }}
        </div>
    @endif

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        {{-- Adresse e-mail --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __("Adresse e-mail") }}</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm">
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        {{-- Mot de passe --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __("Mot de passe") }}</label>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm">
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        {{-- Se souvenir + Mot de passe oublié --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                       class="rounded border-gray-300 text-bps-600 shadow-sm focus:ring-bps-500">
                <span class="ms-2 text-sm text-gray-600">{{ __("Se souvenir de moi") }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-bps-700 hover:text-bps-900" href="{{ route('password.request') }}" wire:navigate>
                    {{ __("Mot de passe oublié ?") }}
                </a>
            @endif
        </div>

        {{-- Bouton Connexion --}}
        <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-bps-600 hover:bg-bps-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bps-500 transition">
            {{ __("Se connecter") }}
        </button>

        {{-- Lien conversion --}}
        <p class="text-center text-sm text-gray-600 pt-2">
            {{ __("Pas encore inscrit ?") }} <a href="{{ route('register') }}" class="text-bps-700 hover:text-bps-900 font-medium" wire:navigate>Demander un compte</a>
        </p>
    </form>
</div>

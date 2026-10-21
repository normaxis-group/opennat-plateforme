<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __("Mot de passe oublié") }}</h2>
        <p class="text-sm text-gray-600 mt-2">{{ __("Entrez votre adresse e-mail pour recevoir un lien de réinitialisation.") }}</p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-bps-50 p-3 text-sm text-bps-800 border border-bps-200">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="space-y-4" data-sans-mesure>
        {{-- Adresse e-mail --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __("Adresse e-mail") }}</label>
            <input wire:model="email" id="email" type="email" name="email" required autofocus
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Bouton --}}
        <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-bps-600 hover:bg-bps-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bps-500 transition">
            {{ __("Envoyer le lien de réinitialisation") }}
        </button>

        {{-- Retour login --}}
        <p class="text-center text-sm pt-2">
            <a href="{{ route('login') }}" class="text-bps-700 hover:text-bps-900 font-medium" wire:navigate>{{ __("Retour à la connexion") }}</a>
        </p>
    </form>
</div>

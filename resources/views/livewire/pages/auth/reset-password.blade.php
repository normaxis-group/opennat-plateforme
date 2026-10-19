<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __("Réinitialiser le mot de passe") }}</h2>
        <p class="text-sm text-gray-600 mt-2">{{ __("Choisissez un nouveau mot de passe sécurisé.") }}</p>
    </div>

    <form wire:submit="resetPassword" class="space-y-4">
        {{-- Adresse e-mail --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __("Adresse e-mail") }}</label>
            <input wire:model="email" id="email" type="email" name="email" required autofocus autocomplete="username"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Nouveau mot de passe --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __("Nouveau mot de passe") }}</label>
            <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirmation --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __("Confirmer le nouveau mot de passe") }}</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Bouton --}}
        <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-bps-600 hover:bg-bps-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bps-500 transition">
            {{ __("Réinitialiser le mot de passe") }}
        </button>
    </form>
</div>

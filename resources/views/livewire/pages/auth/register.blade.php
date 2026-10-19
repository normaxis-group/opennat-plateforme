<?php

use App\Mail\InscriptionRecue;
use App\Mail\NouvelleInscription;
use App\Models\User;
use App\Services\MosparoVerifier;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

/*
 * Inscription OpenNAT, sur le modèle de BPS : le compte naît inactif, l'adresse doit être confirmée, IRICE valide
 * la demande dans l'administration. L'inscription n'ouvre que si mosparo est configuré (projet OpenNAT sur
 * spam.normaxis.com) : sans anti-spam, le formulaire ne s'affiche pas.
 */
new #[Layout('layouts.guest')] class extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $organisation = '';
    public string $fonction = '';
    public ?string $phone = null;
    public string $password = '';
    public string $password_confirmation = '';
    public bool $conditions = false;

    public ?string $mosparoSubmitToken = null;
    public ?string $mosparoValidationToken = null;

    public bool $envoye = false;

    public function inscriptionsOuvertes(): bool
    {
        return config('mosparo.enabled')
            && filled(config('mosparo.project_uuid'))
            && filled(config('mosparo.public_key'))
            && filled(config('mosparo.private_key'));
    }

    public function register(): void
    {
        if (! $this->inscriptionsOuvertes()) {
            abort(403);
        }

        $valide = app(MosparoVerifier::class)->verify(
            submitToken: $this->mosparoSubmitToken,
            validationToken: $this->mosparoValidationToken,
            formData: [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'organisation' => $this->organisation,
                'fonction' => $this->fonction,
                'phone' => $this->phone ?? '',
            ],
        );
        if (! $valide) {
            $this->addError('mosparo', 'Vérification anti-spam échouée. Rechargez la page et cochez de nouveau la case « Je ne suis pas un robot ».');

            return;
        }

        $v = $this->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'organisation' => ['required', 'string', 'max:160'],
            'fonction' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'conditions' => ['accepted'],
        ], [
            'conditions.accepted' => 'Acceptez les conditions d\'utilisation pour envoyer votre demande.',
        ]);

        $user = User::create([
            'name' => trim($v['first_name'].' '.$v['last_name']),
            'first_name' => $v['first_name'],
            'last_name' => $v['last_name'],
            'email' => $v['email'],
            'organisation' => $v['organisation'],
            'fonction' => $v['fonction'] ?: null,
            'phone' => $v['phone'] ?: null,
            'password' => $v['password'],
            'is_active' => false,
            'conditions_acceptees_le' => now(),
        ]);

        // Lien de confirmation de l'adresse (notification standard, route sans connexion).
        event(new Registered($user));

        try {
            Mail::to($user->email)->send(new InscriptionRecue($user));
        } catch (\Throwable $e) {
            Log::warning('OpenNAT : échec du mail de demande enregistrée : '.$e->getMessage());
        }

        $destinataires = User::where('is_admin', true)->where('is_active', true)->pluck('email')
            ->merge(config('opennat.alerte_inscriptions'))->unique()->values();
        foreach ($destinataires as $adresse) {
            try {
                Mail::to($adresse)->send(new NouvelleInscription($user));
            } catch (\Throwable $e) {
                Log::warning("OpenNAT : échec de l'alerte d'inscription à {$adresse} : ".$e->getMessage());
            }
        }

        $this->reset(['password', 'password_confirmation']);
        $this->envoye = true;
    }
}; ?>

<div>
    <h1 class="text-xl font-semibold text-encre">Demander un compte</h1>

    @if ($envoye)
        <div class="mt-4 rounded-md bg-emerald-50 border-l-4 border-emerald-600 p-4 text-sm text-slate-800 space-y-2">
            <p><strong>Votre demande est enregistrée.</strong></p>
            <p>Confirmez votre adresse par le lien que nous venons de vous envoyer. IRICE valide ensuite votre compte et vous prévient par un message ; vous pourrez alors vous connecter.</p>
        </div>
    @elseif (! $this->inscriptionsOuvertes())
        <div class="mt-4 rounded-md bg-amber-50 border-l-4 border-amber-400 p-4 text-sm text-slate-800">
            Les demandes de compte ouvrent prochainement. Pour un accès dès maintenant, écrivez à
            <a href="mailto:hello@irice-certification.com" class="underline">hello@irice-certification.com</a>.
        </div>
    @else
        <p class="mt-2 text-sm text-slate-600">L'accès à l'outil de calcul et au recueil de la méthode est ouvert après confirmation de votre adresse et validation de votre demande par IRICE.</p>

        <form wire:submit="register" class="mt-6 space-y-4" id="mosparo-register-form">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom *</label>
                    <input wire:model="first_name" name="first_name" id="first_name" type="text" required autocomplete="given-name"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-bps-500 focus:ring-bps-500">
                    @error('first_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Nom *</label>
                    <input wire:model="last_name" name="last_name" id="last_name" type="text" required autocomplete="family-name"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-bps-500 focus:ring-bps-500">
                    @error('last_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Adresse électronique professionnelle *</label>
                <input wire:model="email" name="email" id="email" type="email" required autocomplete="email"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-bps-500 focus:ring-bps-500">
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="organisation" class="block text-sm font-medium text-gray-700">Organisation *</label>
                    <input wire:model="organisation" name="organisation" id="organisation" type="text" required autocomplete="organization"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-bps-500 focus:ring-bps-500">
                    @error('organisation') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="fonction" class="block text-sm font-medium text-gray-700">Fonction</label>
                    <input wire:model="fonction" name="fonction" id="fonction" type="text" autocomplete="organization-title"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-bps-500 focus:ring-bps-500">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input wire:model="phone" name="phone" id="phone" type="tel" autocomplete="tel"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-bps-500 focus:ring-bps-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe *</label>
                    <input wire:model="password" id="password" type="password" required autocomplete="new-password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-bps-500 focus:ring-bps-500">
                    @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmation *</label>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-bps-500 focus:ring-bps-500">
                </div>
            </div>

            <label class="flex items-start gap-2 text-sm text-gray-700">
                <input wire:model="conditions" type="checkbox" class="mt-1 rounded border-gray-300 text-bps-600 focus:ring-bps-500">
                <span>J'accepte les <a href="{{ route('conditions') }}" target="_blank" class="underline">conditions d'utilisation</a> et j'ai pris connaissance de la <a href="{{ route('confidentialite') }}" target="_blank" class="underline">politique de confidentialité</a>. *</span>
            </label>
            @error('conditions') <p class="text-xs text-red-600">{{ $message }}</p> @enderror

            <x-mosparo-widget id="mosparo-register-form" :livewire="true" />

            @error('mosparo')
                <div class="rounded-md bg-red-50 border-l-4 border-red-500 p-4 text-sm text-red-800">{{ $message }}</div>
            @enderror

            <button type="submit" wire:loading.attr="disabled"
                    class="w-full rounded-md bg-marine px-4 py-2 text-sm font-medium text-white hover:bg-encre disabled:opacity-50">
                Envoyer ma demande
            </button>
        </form>
    @endif

    <p class="mt-6 text-center text-sm text-gray-500">
        Déjà un compte ? <a href="{{ route('login') }}" class="text-bps-700 hover:underline" wire:navigate>Se connecter</a>
    </p>
</div>

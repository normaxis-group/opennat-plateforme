{{--
    Composant Mosparo widget - à insérer juste avant le bouton de soumission
    de chaque formulaire public.

    Usage classique (form HTTP standard avec middleware verify.mosparo) :
        <x-mosparo-widget />
        <x-mosparo-widget id="mosparo-contact" />

    Usage Livewire (form wire:submit - le middleware HTTP ne s'applique pas) :
        <x-mosparo-widget id="mosparo-register" :livewire="true" />

    En mode Livewire, le widget synchronise automatiquement les 2 tokens
    Mosparo vers des wire properties `mosparoSubmitToken` et
    `mosparoValidationToken` du composant Livewire parent (que le composant
    DOIT déclarer en public ?string avec valeur par défaut null). La vérif
    backend doit ensuite être appelée explicitement dans la méthode du
    composant via `app(MosparoVerifier::class)->verify(...)`.
--}}

@props([
    'id' => 'mosparo-box',
    'design' => null,
    'locale' => 'fr',
    'livewire' => false,
])

@if(config('mosparo.enabled') && filled(config('mosparo.public_key')) && filled(config('mosparo.project_uuid')))
    {{-- wire:ignore : empêche Livewire d'écraser (morph) le widget Mosparo injecté en JS. --}}
    <div id="{{ $id }}" class="mt-4" wire:ignore></div>

    <script defer src="{{ config('mosparo.host') }}/build/mosparo-frontend.js"></script>

    <script>
        window.addEventListener('load', function () {
            if (typeof mosparo === 'undefined') {
                console.warn('[Mosparo] widget JS non chargé. Vérifier ' + @js(config('mosparo.host') . '/build/mosparo-frontend.js'));
                return;
            }

            new mosparo(
                @js($id),
                @js(config('mosparo.host')),
                @js(config('mosparo.project_uuid')),
                @js(config('mosparo.public_key')),
                {
                    loadCssResource: true,
                    @if($design) designMode: @js($design), @endif
                    @if($livewire)
                    onCheckForm: function (valid) {
                        if (!valid) return;
                        var box = document.getElementById(@js($id));
                        if (!box || typeof Livewire === 'undefined') return;
                        // Le wire:id est porté par la RACINE du composant Livewire
                        // (un <div>), pas par le <form> imbriqué - d'où closest('[wire:id]').
                        var root = box.closest('[wire\\:id]');
                        if (!root) return;
                        var wireId = root.getAttribute('wire:id');
                        if (!wireId) return;
                        // Les tokens Mosparo sont injectés dans le <form> englobant.
                        var scope = box.closest('form') || root;
                        var sub  = (scope.querySelector('[name=_mosparo_submitToken]')     || {}).value || '';
                        var val  = (scope.querySelector('[name=_mosparo_validationToken]') || {}).value || '';
                        var component = Livewire.find(wireId);
                        if (!component) return;
                        // 3e arg = false : on met à jour les propriétés SANS aller-retour
                        // serveur (différé). Un round-trip ici re-render le composant et
                        // efface le widget Mosparo (la case « disparaît »). Les tokens
                        // partent avec la soumission du formulaire.
                        component.set('mosparoSubmitToken',     sub, false);
                        component.set('mosparoValidationToken', val, false);
                    },
                    @endif
                }
            );
        });
    </script>
@endif

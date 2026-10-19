<x-layouts.app titre="La méthode">
    <h1 class="text-2xl font-semibold text-encre">Recueil de la méthode OpenNAT</h1>
    <p class="mt-2 max-w-3xl text-sm text-slate-600">
        Textes de la méthode éditée par NORMATYS, proposée au Biodiversity Standard Council, comité scientifique et technique
        des référentiels NORMATYS. Tant qu'elle n'est pas validée, ses seuils sont des propositions et ses résultats ont une
        valeur indicative. Les tables de données sont publiées avec le moteur sur
        <a href="{{ config('opennat.depot_url') }}" class="text-bps-700 underline" target="_blank" rel="noopener">GitHub</a>.
    </p>

    <div class="mt-8 grid gap-4 md:grid-cols-2">
        @foreach ($documents as $cle => $d)
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <a href="{{ route('recueil.document', $cle) }}" class="text-lg font-semibold text-encre hover:text-marine">{{ $d['titre'] }}</a>
                <p class="mt-1 text-sm text-slate-600">{{ $d['resume'] }}</p>
                <p class="mt-3 text-sm">
                    <a href="{{ route('recueil.document', $cle) }}" class="text-bps-700 underline">Lire en ligne</a>
                    @if (isset($d['pdf']) && is_file(\App\Http\Controllers\RecueilController::dossier().'/pdf/'.$d['pdf']))
                        · <a href="{{ route('recueil.pdf', $cle) }}" class="text-bps-700 underline">PDF</a>
                    @endif
                </p>
            </div>
        @endforeach
    </div>
</x-layouts.app>

<x-layouts.app titre="Mon espace">
    <h1 class="text-2xl font-semibold text-encre">Bonjour {{ auth()->user()->first_name }}</h1>
    <p class="mt-1 text-sm text-slate-600">{{ auth()->user()->organisation }}</p>

    <div class="mt-8 grid gap-6 md:grid-cols-2">
        <a href="{{ route('outil') }}" class="block rounded-xl bg-marine p-6 text-white hover:bg-encre">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-300">Calculer</p>
            <p class="mt-2 text-xl font-semibold">Outil de calcul OpenNAT</p>
            <p class="mt-2 text-sm text-white/80">Saisie ou reprise du RSEE, calcul des variantes, résultats, RSNat et fiche de synthèse.</p>
        </a>
        <a href="{{ route('recueil') }}" class="block rounded-xl border border-slate-200 bg-white p-6 hover:border-marine">
            <p class="text-xs uppercase tracking-[0.2em] text-bps-700">Lire</p>
            <p class="mt-2 text-xl font-semibold text-encre">Recueil de la méthode</p>
            <p class="mt-2 text-sm text-slate-600">Texte de la méthode, conventions, guides, bibliographie, journal des versions.</p>
        </a>
    </div>

    <h2 class="mt-12 text-lg font-semibold text-encre">Mes calculs</h2>
    <p class="mt-1 text-sm text-slate-600">Les résultats restent sur le serveur {{ config('opennat.conservation_heures') }} heures ; enregistrez vos fichiers sur votre poste.</p>
    @if ($calculs->isEmpty())
        <p class="mt-4 rounded-md border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-600">Aucun calcul pour l'instant.</p>
    @else
        <div class="mt-4 overflow-x-auto rounded-md border border-slate-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-encre text-white">
                    <tr>
                        <th class="px-3 py-2 text-left">Date</th>
                        <th class="px-3 py-2 text-left">Projet</th>
                        <th class="px-3 py-2 text-center">Tirages</th>
                        <th class="px-3 py-2 text-center">État</th>
                        <th class="px-3 py-2 text-left">Fichiers</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($calculs as $c)
                        <tr class="border-t border-slate-200">
                            <td class="px-3 py-2 whitespace-nowrap">{{ $c->created_at->timezone('Europe/Paris')->format('d/m/Y H:i') }}</td>
                            <td class="px-3 py-2">{{ $c->projet_nom ?: $c->projet_identifiant }}</td>
                            <td class="px-3 py-2 text-center">{{ $c->tirages ?? 'défaut' }}</td>
                            <td class="px-3 py-2 text-center">{{ ['en_cours' => 'en cours', 'termine' => 'terminé', 'interrompu' => 'interrompu', 'erreur' => 'erreur'][$c->etat] ?? $c->etat }}</td>
                            <td class="px-3 py-2">
                                @if ($c->etat === 'termine' && $c->resultatsDisponibles())
                                    <a class="text-bps-700 underline" href="{{ url('/api/fiche/'.$c->moteur_id.'.docx') }}">Fiche Word</a> ·
                                    <a class="text-bps-700 underline" href="{{ url('/api/rsnat/'.$c->moteur_id.'.xml') }}">RSNat XML</a> ·
                                    <a class="text-bps-700 underline" href="{{ url('/api/rsnat/'.$c->moteur_id.'.json') }}">RSNat JSON</a>
                                @elseif ($c->etat === 'termine')
                                    <span class="text-slate-500">effacés du serveur</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>

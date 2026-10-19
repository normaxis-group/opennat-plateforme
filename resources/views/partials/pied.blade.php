{{-- Pied commun. Outil hors de la portée d'accréditation : aucune mention d'accréditation (règle 12, rien d'écrit à la place). --}}
<footer class="bg-marine text-white/80">
    <div class="mx-auto max-w-6xl px-4 py-10 grid gap-8 md:grid-cols-3 text-sm">
        <div>
            <img src="{{ asset('images/irice-fond-sombre.svg') }}" alt="IRICE" class="h-8 w-auto">
            <p class="mt-3 font-serif italic text-white/70">La preuve, pas la promesse.</p>
        </div>
        <div class="space-y-1">
            <p>Outil de calcul OpenNAT édité par IRICE.</p>
            <p>Méthode, tables et moteur édités par NORMATYS, proposés au Biodiversity Standard Council.</p>
        </div>
        <ul class="space-y-1">
            <li><a href="{{ route('conditions') }}" class="hover:text-white">Conditions d'utilisation</a></li>
            <li><a href="{{ route('confidentialite') }}" class="hover:text-white">Confidentialité</a></li>
            <li><a href="{{ config('opennat.mentions_legales_url') }}" class="hover:text-white" target="_blank" rel="noopener">Mentions légales</a></li>
            <li><a href="{{ config('opennat.depot_url') }}" class="hover:text-white" target="_blank" rel="noopener">Code et tables (GitHub)</a></li>
        </ul>
    </div>
</footer>

<x-layouts.app :titre="$document['titre']">
    <p class="text-sm"><a href="{{ route('recueil') }}" class="text-bps-700 underline">Recueil de la méthode</a></p>
    <div class="mt-4 grid gap-8 lg:grid-cols-12">
        <aside class="lg:col-span-3">
            <div class="lg:sticky lg:top-6 rounded-xl border border-slate-200 bg-white p-4 text-sm">
                @if ($pdf)
                    <a href="{{ route('recueil.pdf', $cle) }}" class="mb-4 inline-flex rounded-md bg-marine px-3 py-2 font-medium text-white hover:bg-encre">Télécharger le PDF</a>
                @endif
                @if ($sommaire)
                    <p class="font-semibold text-encre">Sommaire</p>
                    <ol class="mt-2 space-y-1 max-h-[70vh] overflow-y-auto">
                        @foreach ($sommaire as $s)
                            <li><a href="#{{ $s['id'] }}" class="text-slate-700 hover:text-marine">{{ $s['titre'] }}</a></li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </aside>
        <article class="lg:col-span-9 rounded-xl border border-slate-200 bg-white p-6 md:p-10 prose-recueil">
            {!! $html !!}
        </article>
    </div>
</x-layouts.app>

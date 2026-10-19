{{-- Accueil public d'OpenNAT. Outil hors de la portée d'accréditation : aucune mention d'accréditation (règle 12).
     Directive (UE) 2024/825 : les niveaux du calcul se citent avec leur version, leur périmètre et leurs valeurs. --}}
@php $f = config('opennat.faits'); @endphp
<x-layouts.public
    titre="OpenNAT, le bilan nature des projets | IRICE"
    description="OpenNAT mesure la nature qu'un projet de construction consomme sur son site et par ses matériaux, en mètres carrés d'habitat équivalent sur {{ $f['horizon_annees'] }} ans. Outil de calcul édité par IRICE, méthode NORMATYS."
>
    <section class="relative overflow-hidden bg-marine text-white">
        <div class="mx-auto max-w-6xl px-4 py-16 md:py-24 grid lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-emerald-300">Méthode proposée au Biodiversity Standard Council</p>
                <h1 class="mt-5 font-semibold text-4xl md:text-5xl leading-tight">La nature consommée par un projet, mesurée.</h1>
                <p class="mt-6 text-lg text-white/85 leading-relaxed">
                    OpenNAT compte ce qu'un projet de construction prend à la nature, sur son site et par ses matériaux,
                    en mètres carrés d'habitat équivalent sur {{ $f['horizon_annees'] }} ans. Comme la RE2020 pour le carbone :
                    un calcul conventionnel, qui compare les projets sous des règles communes.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    @auth
                        <a href="{{ route('outil') }}" class="inline-flex justify-center rounded-md bg-white px-6 py-3 font-medium text-marine hover:bg-slate-100">Ouvrir l'outil de calcul</a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex justify-center rounded-md bg-white px-6 py-3 font-medium text-marine hover:bg-slate-100">Demander un compte</a>
                        <a href="{{ route('login') }}" class="inline-flex justify-center rounded-md border border-white/50 px-6 py-3 font-medium text-white hover:bg-white/10">Se connecter</a>
                    @endauth
                </div>
            </div>
            <div class="lg:col-span-5">
                <img src="{{ asset('images/opennat-logo-fond-sombre.svg') }}" alt="OpenNAT, le bilan nature des projets" class="w-full max-w-md" width="4528" height="1024">
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16 grid gap-12 lg:grid-cols-2">
        <div>
            <h2 class="text-2xl font-semibold text-encre">Ce que mesure OpenNAT</h2>
            <p class="mt-4 text-slate-700 leading-relaxed">
                Un projet consomme de la nature à deux endroits. Sur son site, il remplace une prairie, une friche ou un
                boisement par des bâtiments et des espaces plantés qui mettront des années à valoir ce qu'ils remplacent.
                Ailleurs, il prélève des forêts pour son bois et des carrières pour ses granulats, et ses émissions pèsent
                sur le climat. OpenNAT compte les deux dans une même unité, le mètre carré d'habitat équivalent (m²HE).
            </p>
            <dl class="mt-6 grid gap-3 text-sm">
                @foreach ([
                    ['Cnat', 'consommation de nature sur le site, en m²HE·an'],
                    ['Inat', 'biodiversité grise des matériaux et du chantier (climat, forêts, carrières), en m²HE·an'],
                    ['Snat', 'solde de nature du projet : Snat = -(Cnat + Inat)'],
                    ['Bnat', 'coefficient de biotope de l\'état projet, comparable à celui du PLU'],
                ] as [$sigle, $sens])
                    <div class="flex gap-3"><dt class="w-14 shrink-0 font-semibold text-marine">{{ $sigle }}</dt><dd class="text-slate-700">{{ $sens }}</dd></div>
                @endforeach
            </dl>
        </div>
        <div>
            <h2 class="text-2xl font-semibold text-encre">Ce que vous trouvez dans votre espace</h2>
            <ul class="mt-4 space-y-4 text-slate-700">
                <li><strong class="text-encre">L'outil de calcul.</strong> Saisie du projet ou reprise du RSEE de l'étude RE2020, calcul de chaque variante, incertitude, récapitulatif standardisé (RSNat) et fiche de synthèse en Word.</li>
                <li><strong class="text-encre">Le recueil de la méthode.</strong> Texte de la méthode, {{ $f['conventions'] }} conventions numérotées, guides, bibliographie et journal des versions, à lire en ligne ou en PDF.</li>
                <li><strong class="text-encre">L'historique de vos calculs.</strong> Les projets envoyés et leurs résultats sont effacés du serveur {{ config('opennat.conservation_heures') }} heures après le calcul : vous gardez vos fichiers sur votre poste.</li>
            </ul>
            <p class="mt-6 text-sm text-slate-600">L'accès est ouvert après confirmation de votre adresse et validation de votre demande par IRICE.</p>
        </div>
    </section>

    <section class="bg-slate-50 border-y border-slate-200">
        <div class="mx-auto max-w-6xl px-4 py-14 grid gap-10 md:grid-cols-3 text-sm text-slate-700">
            <div>
                <h3 class="text-lg font-semibold text-encre">Une méthode qui se vérifie</h3>
                <p class="mt-3">Chaque coefficient porte sa source et sa licence ; {{ $f['types_habitat'] }} types d'habitat ; un moteur dont les résultats sont retrouvés par un carnet de calcul écrit à part. Code et tables sont publics sur <a href="{{ config('opennat.depot_url') }}" class="underline" target="_blank" rel="noopener">GitHub</a>.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-encre">Qui fait quoi</h3>
                <p class="mt-3">NORMATYS édite la méthode, ses tables et son moteur. La méthode est soumise au Biodiversity Standard Council, comité scientifique et technique des référentiels NORMATYS. IRICE édite l'outil de calcul.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-encre">Lire un résultat</h3>
                <p class="mt-3">Tant que le Biodiversity Standard Council n'a pas validé la méthode, ses seuils sont des propositions et ses résultats ont une valeur indicative. Un niveau « absence de perte nette » ou « gain net » se cite avec la version de la méthode, le périmètre et les valeurs de Cnat et d'Inat.</p>
            </div>
        </div>
    </section>
</x-layouts.public>

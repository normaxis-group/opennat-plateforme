@props(['titre' => null])
<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.tete', ['titre' => ($titre ? $titre.' | ' : '').'OpenNAT', 'noindex' => true])
</head>
<body class="font-sans text-encre antialiased min-h-screen flex flex-col bg-slate-50">
    <header class="bg-marine text-white">
        <div class="mx-auto max-w-6xl px-4 h-16 flex items-center justify-between gap-4">
            <a href="{{ route('espace') }}"><img src="{{ asset('images/opennat-logo-sans-signature-fond-sombre.svg') }}" alt="OpenNAT" class="h-8 w-auto"></a>
            <nav class="flex flex-wrap items-center gap-1 text-sm">
                @foreach ([['espace', 'Mon espace'], ['recueil', 'La méthode'], ['outil', 'Outil de calcul']] as [$route, $libelle])
                    <a href="{{ route($route) }}"
                       class="px-3 py-2 rounded-md {{ request()->routeIs($route.'*') ? 'bg-white/15 text-white' : 'text-white/80 hover:text-white' }}">{{ $libelle }}</a>
                @endforeach
                @if (auth()->user()?->is_admin)
                    <a href="{{ url('/admin') }}" class="px-3 py-2 text-white/80 hover:text-white">Administration</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 text-white/80 hover:text-white">Déconnexion</button>
                </form>
            </nav>
        </div>
    </header>
    <main class="flex-1">
        <div class="mx-auto max-w-6xl px-4 py-8">
            {{ $slot }}
        </div>
    </main>
    @include('partials.pied')
</body>
</html>

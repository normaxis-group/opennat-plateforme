@props(['titre' => null, 'description' => null])
<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.tete', ['titre' => $titre, 'description' => $description])
</head>
<body class="font-sans text-encre antialiased bg-white min-h-screen flex flex-col">
    <header class="bg-marine text-white">
        <div class="mx-auto max-w-6xl px-4 h-16 flex items-center justify-between gap-4">
            <a href="{{ route('accueil') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/opennat-logo-sans-signature-fond-sombre.svg') }}" alt="OpenNAT" class="h-8 w-auto">
            </a>
            <nav class="flex items-center gap-3 text-sm">
                @auth
                    <a href="{{ route('espace') }}" class="rounded-md bg-white px-4 py-2 font-medium text-marine hover:bg-slate-100">Mon espace</a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 hover:text-white/80">Se connecter</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-white px-4 py-2 font-medium text-marine hover:bg-slate-100">Demander un compte</a>
                @endauth
            </nav>
        </div>
    </header>
    <main class="flex-1">
        {{ $slot }}
    </main>
    @include('partials.pied')
</body>
</html>

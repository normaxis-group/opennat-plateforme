<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.tete', ['titre' => 'OpenNAT, connexion', 'noindex' => true])
</head>
<body class="font-sans text-encre antialiased min-h-screen flex flex-col bg-slate-50">
    <header class="bg-marine">
        <div class="mx-auto max-w-6xl px-4 h-16 flex items-center">
            <a href="{{ route('accueil') }}"><img src="{{ asset('images/opennat-logo-sans-signature-fond-sombre.svg') }}" alt="OpenNAT" class="h-8 w-auto"></a>
        </div>
    </header>
    <main class="flex-1 flex items-start justify-center px-4 py-10">
        <div class="w-full sm:max-w-lg bg-white px-6 py-6 shadow-lg sm:rounded-xl">
            {{ $slot }}
        </div>
    </main>
    @include('partials.pied')
</body>
</html>

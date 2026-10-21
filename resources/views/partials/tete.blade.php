<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $titre ?? 'OpenNAT, le bilan nature des projets' }}</title>
@isset($description)<meta name="description" content="{{ $description }}">@endisset
@if (! empty($noindex))<meta name="robots" content="noindex, nofollow">@endif
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=jost:400,500,600&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
{{-- Mesure d'audience (Matomo, site 18) et bandeau de consentement : les trois layouts passent par ce partial. --}}
@include('partials.matomo')

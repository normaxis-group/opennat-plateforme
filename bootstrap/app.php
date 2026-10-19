<?php

use App\Http\Middleware\EnsureAccountIsActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Derrière Traefik (Coolify) : sans proxy de confiance, les URL sortent en http:// et Livewire ne
        // s'attache plus (incident BPS du 01/10/2026).
        $middleware->trustProxies(at: '*');

        // « actif » : compte validé par IRICE ; un compte désactivé est éjecté à la requête suivante.
        $middleware->alias(['actif' => EnsureAccountIsActive::class]);
        $middleware->appendToGroup('web', [EnsureAccountIsActive::class]);

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('espace'));

        // mosparo signe chaque champ : ne rien réécrire d'une soumission qui porte son jeton.
        $signee = fn (Request $request) => $request->isMethod('POST')
            && $request->has(config('mosparo.fields.submit_token', '_mosparo_submitToken'));
        TrimStrings::skipWhen($signee);
        ConvertEmptyStringsToNull::skipWhen($signee);

        // Les appels de l'outil vers /api passent leur corps tel quel au moteur.
        TrimStrings::skipWhen(fn (Request $request) => $request->is('api/*'));
        ConvertEmptyStringsToNull::skipWhen(fn (Request $request) => $request->is('api/*'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

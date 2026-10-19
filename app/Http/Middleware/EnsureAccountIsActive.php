<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Coupe l'accès d'un compte désactivé à la requête suivante.
 *
 * Le contrôle de LoginForm ne joue qu'à la connexion : un compte désactivé
 * par le staff gardait sa session ouverte (8 h) et son cookie « se souvenir
 * de moi ». La désactivation doit valoir tout de suite, sinon elle ne retire
 * rien à quelqu'un qui est déjà dans l'outil.
 */
class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Comparaison stricte : seul un compte explicitement désactivé est
        // éjecté (un attribut non chargé vaut null, pas false).
        if ($user && $user->is_active === false) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Requête Livewire ou JSON : 401, le client recharge vers /login.
            if ($request->hasHeader('X-Livewire') || $request->expectsJson()) {
                abort(401);
            }

            return redirect()->route('login')
                ->with('status', __("Votre compte a été désactivé. Contactez IRICE pour rétablir l'accès."));
        }

        return $next($request);
    }
}

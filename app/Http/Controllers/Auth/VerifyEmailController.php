<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;

/**
 * Confirmation de l'adresse par le lien signé du message, sans connexion préalable : à ce stade le compte est
 * encore inactif (validation IRICE à venir) et ne peut pas ouvrir de session. La signature de l'URL et
 * l'empreinte de l'adresse suffisent à prouver que le lien vient du message envoyé.
 */
class VerifyEmailController extends Controller
{
    public function __invoke(string $id, string $hash): RedirectResponse
    {
        $user = User::findOrFail($id);
        abort_unless(hash_equals(sha1($user->getEmailForVerification()), $hash), 403);

        if (! $user->hasVerifiedEmail() && $user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('login')->with('status', $user->is_active
            ? 'Adresse confirmée. Vous pouvez vous connecter.'
            : 'Adresse confirmée. IRICE valide maintenant votre demande et vous préviendra par un message.');
    }
}

<?php

use App\Http\Controllers\EspaceController;
use App\Http\Controllers\OutilController;
use App\Http\Controllers\RecueilController;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| OpenNAT, plateforme en ligne (IRICE)
|--------------------------------------------------------------------------
| Public : accueil, conditions d'utilisation, confidentialité, authentification.
| Connecté, adresse confirmée et compte validé par IRICE (« actif ») : espace, recueil de la méthode, outil de calcul.
| L'outil est le moteur OpenNAT, joint sur le réseau interne par OutilController ; ses routes /api et /statique sont
| celles que sa page appelle, toutes derrière la connexion.
*/

Route::view('/', 'accueil')->name('accueil');
Route::view('/conditions-d-utilisation', 'conditions')->name('conditions');
Route::view('/confidentialite', 'confidentialite')->name('confidentialite');

Route::middleware(['auth', 'verified', 'actif'])->group(function () {
    Route::get('/espace', EspaceController::class)->name('espace');

    Route::get('/methode', [RecueilController::class, 'index'])->name('recueil');
    Route::get('/methode/{document}', [RecueilController::class, 'show'])->name('recueil.document');
    Route::get('/methode/{document}/pdf', [RecueilController::class, 'pdf'])->name('recueil.pdf');

    Route::get('/outil', [OutilController::class, 'page'])->name('outil');
    Route::get('/statique/{chemin}', [OutilController::class, 'statique'])->where('chemin', '.*');
    Route::any('/api/{chemin}', [OutilController::class, 'api'])->where('chemin', '.*');

    Route::post('/deconnexion', function (Logout $logout) {
        $logout();

        return redirect()->route('accueil');
    })->name('logout');
});

require __DIR__.'/auth.php';

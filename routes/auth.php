<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('inscription', 'pages.auth.register')->name('register');
    Volt::route('connexion', 'pages.auth.login')->name('login');
    Volt::route('mot-de-passe-oublie', 'pages.auth.forgot-password')->name('password.request');
    Volt::route('nouveau-mot-de-passe/{token}', 'pages.auth.reset-password')->name('password.reset');
});

// Lien du message de confirmation : signé, sans session (le compte n'est pas encore validé).
Route::get('confirmation-adresse/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::middleware('auth')->group(function () {
    Volt::route('adresse-a-confirmer', 'pages.auth.verify-email')->name('verification.notice');
    Volt::route('confirmer-le-mot-de-passe', 'pages.auth.confirm-password')->name('password.confirm');
});

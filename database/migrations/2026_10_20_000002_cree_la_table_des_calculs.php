<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Historique des calculs : chaque calcul lancé dans l'outil appartient à un compte.
 *
 * moteur_id est l'identifiant aléatoire rendu par le moteur ; l'application s'en sert pour n'ouvrir les résultats
 * qu'à leur propriétaire. Le moteur efface résultats et fichiers après 24 heures : la ligne reste comme historique.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('moteur_id', 64)->unique();
            $table->string('projet_identifiant', 160)->nullable();
            $table->string('projet_nom', 255)->nullable();
            $table->unsignedInteger('tirages')->nullable();
            $table->string('etat', 20)->default('en_cours');
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculs');
    }
};

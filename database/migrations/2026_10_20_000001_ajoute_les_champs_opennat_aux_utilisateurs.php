<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Comptes OpenNAT : identité, organisation, validation par IRICE.
 *
 * Un compte naît inactif. Il n'ouvre l'outil et le recueil de la méthode qu'une fois son adresse vérifiée et sa
 * demande validée par un administrateur IRICE (is_active, validated_at, validated_by).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 120)->nullable()->after('name');
            $table->string('last_name', 120)->nullable()->after('first_name');
            $table->string('organisation', 160)->nullable()->after('email');
            $table->string('fonction', 120)->nullable()->after('organisation');
            $table->string('phone', 30)->nullable()->after('fonction');
            $table->boolean('is_active')->default(false)->after('phone');
            $table->boolean('is_admin')->default(false)->after('is_active');
            $table->timestamp('validated_at')->nullable()->after('is_admin');
            $table->foreignId('validated_by')->nullable()->after('validated_at')->constrained('users')->nullOnDelete();
            $table->timestamp('conditions_acceptees_le')->nullable()->after('validated_by');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('validated_by');
            $table->dropColumn(['first_name', 'last_name', 'organisation', 'fonction', 'phone', 'is_active', 'is_admin',
                'validated_at', 'conditions_acceptees_le']);
        });
    }
};

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Définition unique du compte en attente et de sa validation. */
class CycleCompteTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_selection_des_demandes_en_attente_suit_estEnAttente(): void
    {
        $attente = User::factory()->create(['is_active' => false, 'validated_at' => null]);
        $actif = User::factory()->create(['is_active' => true, 'validated_at' => now()]);
        $desactive = User::factory()->create(['is_active' => false, 'validated_at' => now()]);

        $this->assertSame([$attente->id], User::enAttente()->pluck('id')->all());
        $this->assertTrue($attente->estEnAttente());
        $this->assertFalse($actif->estEnAttente());
        $this->assertFalse($desactive->estEnAttente());
    }

    public function test_valider_pose_les_trois_champs_et_desactiver_garde_la_trace(): void
    {
        $admin = User::factory()->create();
        $u = User::factory()->create(['is_active' => false, 'validated_at' => null]);

        $u->valider($admin);
        $u->refresh();
        $this->assertTrue($u->is_active);
        $this->assertNotNull($u->validated_at);
        $this->assertSame($admin->id, $u->validated_by);

        $u->desactiver();
        $u->refresh();
        $this->assertFalse($u->is_active);
        $this->assertNotNull($u->validated_at);
        $this->assertFalse($u->estEnAttente());
    }
}

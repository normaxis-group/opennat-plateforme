<?php

namespace Tests\Feature;

use App\Models\Calcul;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as RequeteMoteur;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Relais vers le moteur : chaque calcul est rattaché à son compte, seul son propriétaire l'ouvre, le moteur reçoit
 * l'identifiant du compte comme client, les points d'accès inconnus ne sont pas relayés.
 */
class RelaisMoteurTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake([
            'moteur.test/api/calcul' => Http::response(['calcul' => 'abc123'], 200),
            'moteur.test/api/calcul/abc123' => Http::response(['etat' => 'termine', 'messages' => []], 200),
            'moteur.test/api/resultats/*' => Http::response(['variantes' => []], 200),
            'moteur.test/api/referentiel' => Http::response(['public' => true], 200),
            'moteur.test/' => Http::response('<html><head></head><body><aside id="controles"><p class="edition">Version en ligne : x</p></aside><div class="actions"></div><script src="/statique/app.js"></script></body></html>', 200, ['Content-Type' => 'text/html']),
        ]);
    }

    public function test_un_calcul_lance_est_rattache_au_compte_et_suivi(): void
    {
        $u = User::factory()->create();
        $this->actingAs($u)->postJson('/api/calcul', ['projet' => ['projet' => ['identifiant' => 'essai', 'nom' => 'Essai']], 'tirages' => 20])
            ->assertOk()->assertJson(['calcul' => 'abc123']);
        $c = Calcul::firstOrFail();
        $this->assertSame([$u->id, 'abc123', 'essai', 'Essai', 20, 'en_cours'],
            [$c->user_id, $c->moteur_id, $c->projet_identifiant, $c->projet_nom, $c->tirages, $c->etat]);

        Http::assertSent(fn (RequeteMoteur $r) => $r->url() === 'http://moteur.test/api/calcul'
            && $r->header('X-Forwarded-For')[0] === 'compte-'.$u->id);

        $this->actingAs($u)->getJson('/api/calcul/abc123')->assertOk();
        $this->assertSame('termine', $c->fresh()->etat);
    }

    public function test_un_autre_compte_ne_voit_pas_le_calcul(): void
    {
        $proprietaire = User::factory()->create();
        Calcul::create(['user_id' => $proprietaire->id, 'moteur_id' => 'abc123']);
        $autre = User::factory()->create();

        foreach (['/api/calcul/abc123', '/api/resultats/abc123', '/api/fichiers/abc123', '/api/rsnat/abc123.xml', '/api/fiche/abc123.docx'] as $chemin) {
            $this->actingAs($autre)->getJson($chemin)->assertNotFound();
        }
        $this->actingAs($autre)->postJson('/api/calcul', ['projet' => [], 'reprendre' => 'abc123'])->assertNotFound();
        $this->actingAs($proprietaire)->getJson('/api/resultats/abc123')->assertOk();
    }

    public function test_un_point_d_acces_inconnu_n_est_pas_relaye(): void
    {
        $this->actingAs(User::factory()->create())->getJson('/api/nimporte/quoi')->assertNotFound();
        Http::assertNothingSent();
    }

    public function test_la_page_de_l_outil_porte_le_jeton_et_la_mention_de_la_plateforme(): void
    {
        $html = $this->actingAs(User::factory()->create())->get('/outil')->assertOk()->getContent();
        $this->assertStringContainsString('name="csrf-token"', $html);
        $this->assertStringContainsString('/outil-csrf.js', $html);
        $this->assertStringNotContainsString('Version en ligne', $html);
        $this->assertStringContainsString('effacés du serveur', $html);
        $this->assertStringContainsString('Mon espace', $html);
    }
}

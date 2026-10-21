<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Mesure d'audience Matomo (site 18), socle CNIL du groupe : traceur chargé seulement après « Accepter »,
 * bandeau sur les pages publiques et connectées, aucun pixel <noscript>, section cookies dans la politique.
 */
class MesureAudienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_pages_publiques_portent_le_traceur_et_le_bandeau(): void
    {
        foreach (['/', '/conditions-d-utilisation', '/confidentialite', '/connexion', '/inscription', '/mot-de-passe-oublie'] as $chemin) {
            $this->get($chemin)->assertOk()
                ->assertSee("_paq.push(['setSiteId', '18'])", false)
                ->assertSee('mesure_choix=ok', false)
                ->assertSee('id="mesure-bandeau-modele"', false)
                ->assertDontSee('<noscript', false)
                ->assertDontSee('matomo.php?idsite', false);
        }
    }

    public function test_la_zone_connectee_porte_aussi_le_traceur(): void
    {
        $u = User::factory()->create();
        $this->actingAs($u)->get('/espace')->assertOk()->assertSee("_paq.push(['setSiteId', '18'])", false);
        $this->actingAs($u)->get('/methode')->assertOk()->assertSee('id="mesure-bandeau-modele"', false);
    }

    public function test_la_politique_presente_les_cookies_et_le_reglage(): void
    {
        $this->get('/confidentialite')->assertOk()
            ->assertSee('Cookies et traceurs')
            ->assertSee('Contabo GmbH')
            ->assertSee("pendant la durée d'exploitation du site", false)
            ->assertSee('data-mesure="refuser"', false)
            ->assertSee('_pk_id')
            ->assertDontSee('accrédit', false)
            ->assertDontSee('Cofrac');
    }
}

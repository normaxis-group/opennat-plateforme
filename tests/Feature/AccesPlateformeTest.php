<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Livewire\Volt\Volt;
use Tests\TestCase;

/**
 * L'outil et le recueil ne s'ouvrent qu'à un compte connecté, à l'adresse confirmée et validé par IRICE ;
 * l'accueil et les pages légales restent publics.
 */
class AccesPlateformeTest extends TestCase
{
    use RefreshDatabase;

    private const ZONE = ['/espace', '/methode', '/methode/methode', '/outil', '/api/referentiel', '/statique/app.js'];

    public function test_les_pages_publiques_repondent_sans_compte(): void
    {
        foreach (['/', '/conditions-d-utilisation', '/confidentialite', '/connexion', '/inscription', '/mot-de-passe-oublie'] as $chemin) {
            $this->get($chemin)->assertOk();
        }
        $this->get('/')->assertSee('Demander un compte')->assertDontSee('accrédit', false)->assertDontSee('Cofrac');
    }

    public function test_la_zone_connectee_renvoie_un_visiteur_vers_la_connexion(): void
    {
        foreach (self::ZONE as $chemin) {
            $r = $this->get($chemin);
            $this->assertTrue($r->isRedirect(route('login')) || $r->status() === 401, $chemin.' : '.$r->status());
        }
    }

    public function test_un_compte_en_attente_est_ejecte(): void
    {
        $u = User::factory()->enAttente()->create();
        $this->actingAs($u)->get('/espace')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_une_adresse_non_confirmee_ne_passe_pas(): void
    {
        $u = User::factory()->unverified()->create();
        $this->actingAs($u)->get('/methode')->assertRedirect(route('verification.notice'));
    }

    public function test_un_compte_valide_ouvre_l_espace_et_le_recueil(): void
    {
        $u = User::factory()->create();
        $this->actingAs($u)->get('/espace')->assertOk()->assertSee('Mes calculs');
        $this->actingAs($u)->get('/methode')->assertOk()->assertSee('Recueil de la méthode OpenNAT');
        $this->actingAs($u)->get('/methode/conventions')->assertOk()->assertSee('Sommaire');
        $this->actingAs($u)->get('/methode/methode/pdf')->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->actingAs($u)->get('/methode/inconnu')->assertNotFound();
    }

    public function test_la_connexion_refuse_un_compte_en_attente(): void
    {
        $u = User::factory()->enAttente()->create();
        Volt::test('pages.auth.login')
            ->set('form.email', $u->email)->set('form.password', 'password')
            ->call('login')
            ->assertHasErrors('form.email');
        $this->assertGuest();
    }

    public function test_l_inscription_reste_fermee_sans_cles_mosparo(): void
    {
        $this->get('/inscription')->assertOk()->assertSee('ouvrent prochainement')->assertDontSee('Envoyer ma demande');
    }

    public function test_le_lien_de_confirmation_confirme_l_adresse_sans_connexion(): void
    {
        $u = User::factory()->enAttente()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addHour(),
            ['id' => $u->id, 'hash' => sha1($u->email)]);
        $this->get($url)->assertRedirect(route('login'));
        $this->assertNotNull($u->fresh()->email_verified_at);
        $this->assertFalse($u->fresh()->is_active);
        $this->get(URL::temporarySignedRoute('verification.verify', now()->addHour(),
            ['id' => $u->id, 'hash' => sha1('autre@exemple.fr')]))->assertForbidden();
    }

    public function test_seul_un_administrateur_actif_ouvre_l_administration(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
        $this->actingAs(User::factory()->admin()->create())->get('/admin/users')->assertOk();
    }
}

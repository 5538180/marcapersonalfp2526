<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Idioma;

class IdiomasApiTest extends TestCase
{
    use RefreshDatabase;

    private function idiomaPayload(array $overrides = [])
    {
        return array_merge([
            'alpha2' => 'c3',
            'alpha3t' => 'c_3',
            'alpha3b' => 'c_3',
            'english_name' => 'Spanish Carlos III',
            'native_name' => 'Español Carlos III',
        ], $overrides);
    }

    public function test_can_create_idioma()
    {
        $payload = $this->idiomaPayload();

        $response = $this->postJson('/api/v1/idiomas', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('idiomas', ['alpha2' => $payload['alpha2'], 'english_name' => $payload['english_name']]);
    }

    public function test_can_list_idiomas()
    {
        // create one idioma first
        $this->postJson('/api/v1/idiomas', $this->idiomaPayload());

        $response = $this->getJson('/api/v1/idiomas');

        $response->assertOk();
        $response->assertJsonStructure(["data"]);
    }

    public function test_can_show_idioma()
    {
        $create = $this->postJson('/api/v1/idiomas', $this->idiomaPayload());
        $create->assertCreated();

        $id = $create->json('data.id') ?: $create->json('id');
        $this->assertNotNull($id);

        $response = $this->getJson("/api/v1/idiomas/{$id}");
        $response->assertOk();
        $response->assertJsonFragment(['alpha2' => 'c3', 'native_name' => 'Español Carlos III']);
    }

    public function test_can_update_idioma()
    {
        $create = $this->postJson('/api/v1/idiomas', $this->idiomaPayload());
        $create->assertCreated();

        $id = $create->json('data.id') ?: $create->json('id');
        $this->assertNotNull($id);

        $response = $this->putJson("/api/v1/idiomas/{$id}", ['english_name' => 'Spanish (updated)']);
        $response->assertOk();

        $this->assertDatabaseHas('idiomas', ['id' => $id, 'english_name' => 'Spanish (updated)']);
    }

    public function test_can_delete_idioma()
    {
        $create = $this->postJson('/api/v1/idiomas', $this->idiomaPayload());
        $create->assertCreated();

        $id = $create->json('data.id') ?: $create->json('id');
        $this->assertNotNull($id);

        $response = $this->deleteJson("/api/v1/idiomas/{$id}");
        $response->assertNoContent();

        $this->assertDatabaseMissing('idiomas', ['id' => $id]);
    }

    // ----- User <-> Idiomas relation tests -----

    public function test_can_attach_idioma_to_user_and_relations_are_present()
    {

        $create = $this->postJson('/api/v1/idiomas', $this->idiomaPayload(['alpha2' => 'fr']));
        $create->assertCreated();

        $idiomaId = $create->json('data.id') ?: $create->json('id');
        $this->assertNotNull($idiomaId);

        // attach via API
        $attach = $this->postJson("/api/v1/users/{$user->id}/idiomas", ['idioma_id' => $idiomaId]);
        $attach->assertCreated();

        // reload models and assert relationships
        $user->refresh();
        $idioma = Idioma::find($idiomaId);

        $this->assertTrue($user->idiomas->contains('id', $idioma->id));
        $this->assertTrue($idioma->users->contains('id', $user->id));
    }

    public function test_can_list_user_idiomas_via_api()
    {

        $create = $this->postJson('/api/v1/idiomas', $this->idiomaPayload(['alpha2' => 'it']));
        $create->assertCreated();

        $idiomaId = $create->json('data.id') ?: $create->json('id');

        $this->postJson("/api/v1/users/{$user->id}/idiomas", ['idioma_id' => $idiomaId])->assertCreated();

        $response = $this->getJson("/api/v1/users/{$user->id}/idiomas");
        $response->assertOk();
        $response->assertJsonFragment(['alpha2' => 'it']);
    }

    public function test_can_detach_idioma_from_user_and_relations_are_removed()
    {

        $create = $this->postJson('/api/v1/idiomas', $this->idiomaPayload(['alpha2' => 'pt']));
        $create->assertCreated();

        $idiomaId = $create->json('data.id') ?: $create->json('id');

        $this->postJson("/api/v1/users/{$user->id}/idiomas", ['idioma_id' => $idiomaId])->assertCreated();

        $response = $this->deleteJson("/api/v1/users/{$user->id}/idiomas/{$idiomaId}");
        $response->assertNoContent();

        $user->refresh();
        $idioma = Idioma::find($idiomaId);

        $this->assertFalse($user->idiomas->contains('id', $idiomaId));

        if ($idioma) {
            $this->assertFalse($idioma->users->contains('id', $user->id));
        }
    }
}

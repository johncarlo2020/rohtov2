<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_visiting_register_creates_a_user_with_a_generated_code(): void
    {
        $response = $this->get('/register?id=external-id');

        $user = User::firstOrFail();

        $response->assertRedirect(route('landing'));
        $this->assertNotSame('external-id', $user->code);
        $this->assertNotEmpty($user->code);
        $this->assertNotNull($user->id);
        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => Role::where('name', 'client')->value('id'),
            'model_id' => $user->id,
            'model_type' => User::class,
        ]);
        $this->assertTrue($user->hasRole('client'));
        $this->assertAuthenticated();
    }
}

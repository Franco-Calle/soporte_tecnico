<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_tecnico_no_accede_a_gestion_de_usuarios(): void
    {
        $tecnico = User::factory()->tecnico()->create();

        $this->actingAs($tecnico)->get('/usuarios')->assertForbidden();
    }

    public function test_admin_si_accede_a_gestion_de_usuarios(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/usuarios')->assertOk();
    }

    public function test_invitado_es_redirigido_al_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}

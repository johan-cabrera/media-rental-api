<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_cannot_create_users(): void
    {
        // Prueba para usuario normal
        $regularUser = User::factory()->create([
            'role' => 'user',
        ]);

        // Tratar de enviar un post a endpoint de administracion
        $response = $this->actingAs($regularUser, 'sanctum')->postJson('/api/admin/users', [
            'name'     => 'Usuario Ilegitimo',
            'email'    => 'no_permitido@test.com',
            'password' => 'password123',
            'role'     => 'user',
        ]);

        $response->assertStatus(403);

        // Verificar registro en la base de datos
        $this->assertDatabaseMissing('users', [
            'email' => 'no_permitido@test.com',
        ]);
    }

    public function test_admin_can_create_users(): void
    {
        // Prueba para usuario administrador
        $adminUser = User::factory()->create([
            'role' => 'admin',
        ]);

        // Tratar de enviar un post a endpoint de administracion
        $response = $this->actingAs($adminUser, 'sanctum')->postJson('/api/admin/users', [
            'name'     => 'Nuevo Empleado',
            'email'    => 'empleado@test.com',
            'password' => 'password123',
            'role'     => 'user',
        ]);

        $response->assertStatus(201);
    }
}
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryIntegrationTest extends TestCase
{
    // Reseta o banco de dados a cada teste para garantir ambiente limpo
    use RefreshDatabase;

    public function test_authenticated_user_can_create_category()
    {
        // 1. Preparação (Arrange): Cria um usuário
        $user = User::factory()->create();

        // 2. Ação (Act): Usuário faz login e envia POST para criar categoria
        $response = $this->actingAs($user)->post(route('categories.store'), [
            'name' => 'Categoria Teste Integration',
            'description' => 'Descrição automática'
        ]);

        // 3. Verificação (Assert):
        // Verifica se redirecionou (geralmente para index)
        $response->assertStatus(302);
        
        // Verifica se o dado realmente foi salvo no banco
        $this->assertDatabaseHas('categories', [
            'name' => 'Categoria Teste Integration'
        ]);
    }
}
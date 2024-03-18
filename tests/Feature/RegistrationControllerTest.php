<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Aluno;

// Classe de teste para o RegistrationController
class RegistrationControllerTest extends TestCase
{
    // Utiliza o trait RefreshDatabase para resetar o banco de dados após cada teste
    use RefreshDatabase;

    // Teste para o método store do RegistrationController
    public function testStore()
    {
        // Dados de entrada para o teste
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'cpf' => '12345678901',
            'password' => 'password',
            'type' => 'Aluno',
            'account_id' => 1,
        ];

        // Faz uma requisição POST para a rota de registro
        $response = $this->post('/register', $userData);

        // Verifica se o usuário é redirecionado para a rota de login
        $response->assertRedirect('login');
        // Verifica se a sessão contém a mensagem de sucesso
        $response->assertSessionHas('success', 'Usuario registrado com sucesso');

        // Verifica se os dados do usuário foram salvos corretamente no banco de dados
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'cpf' => '12345678901',
        ]);

        // Recupera o usuário do banco de dados
        $user = User::where('email', 'test@example.com')->first();
        // Verifica se os dados do aluno foram salvos corretamente no banco de dados
        $this->assertDatabaseHas('alunos', [
            'user_id' => $user->id,
            'name' => 'Test User',
            'cpf' => '12345678901',
        ]);
    }
}

<?php

namespace Tests\Unit;

use Tests\TestCase; 
use App\Utils\CpfValidation;
use App\Rules\ContainerAmount;
use App\Models\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BusinessLogicTest extends TestCase
{
    // Essa trait reseta o banco de dados a cada teste, garantindo limpeza
    use RefreshDatabase;

    /**
     * Teste 1: CPF Válido
     */
    public function test_cpf_validation_returns_true_for_valid_cpf()
    {
        $cpfValidator = new CpfValidation();
        // Esse CPF é gerado matematicamente válido para testes
        $isValid = $cpfValidator->validate(null, '52998224725', null, null);
        
        $this->assertTrue($isValid, 'O validador deveria aceitar um CPF válido.');
    }

    /**
     * Teste 2: CPF Inválido
     */
    public function test_cpf_validation_returns_false_for_invalid_cpf()
    {
        $cpfValidator = new CpfValidation();
        $isValid = $cpfValidator->validate(null, '11111111111', null, null);
        
        $this->assertFalse($isValid, 'O validador deveria rejeitar um CPF inválido.');
    }

    /**
     * Teste 3: Regra de Quantidade do Container
     */
    public function test_container_amount_rule_validates_correctly()
    {
        // 1. Criar um container no banco virtual com capacidade total de 100
        $container = Container::factory()->create([
            'total_amount' => 100
        ]);

        // Cenário A: Tentar adicionar 50 (Deve PASSAR, pois 50 <= 100)
        // Passamos a quantidade (50) e o ID do container criado ($container->id)
        $rule = new ContainerAmount(50, $container->id);
        
        $this->assertTrue($rule->passes('amount', 50), 'Deveria permitir adicionar valor dentro do limite.');

        // Cenário B: Tentar adicionar 150 (Deve FALHAR, pois 150 > 100)
        $ruleOverflow = new ContainerAmount(150, $container->id);
        
        $this->assertFalse($ruleOverflow->passes('amount', 150), 'Deveria bloquear valor acima do limite.');
    }
}
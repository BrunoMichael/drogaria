<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder responsável por popular as tabelas 'users' e 'pessoas' com um usuário administrador inicial.
 *
 * Este seeder cria um usuário padrão com perfil de administrador e um registro associado na tabela 'pessoas'
 * marcando-o como vendedor habilitado.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Executa o seed para criação do usuário administrador e pessoa associada.
     *
     * @return void
     */
    public function run()
    {
        // Criação do usuário administrador com email verificado e senha criptografada.
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@drogaria.com.br',
            'password' => Hash::make('admin'), // Senha criptografada por segurança.
            'email_verified_at' => now(),      // Marca o email como verificado.
        ]);

        // Criação do registro de pessoa associada ao usuário, habilitado como vendedor.
        Pessoa::create([
            'nome' => $user->name,
            'email' => $user->email,
            'eh_vendedor' => true,  // Define que a pessoa é um vendedor.
            'eh_cliente' => false,  // Define que a pessoa não é cliente.
        ]);
    }
}

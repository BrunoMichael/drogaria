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
        $pessoa = Pessoa::create([
            'nome' => 'Admin',
            'email' => 'admin@drogaria.com.br',
            'eh_vendedor' => true,
            'eh_cliente' => false,
        ]);

        User::create([
            'name' => $pessoa->nome,
            'email' => $pessoa->email,
            'password' => Hash::make('admin'),
            'permission' => 'gestor',
            'email_verified_at' => now(),
            'pessoa_id' => $pessoa->id,
        ]);
    }
}

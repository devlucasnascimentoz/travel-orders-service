<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Usuário admin para testes
        User::create([
            'name' => 'Admin Teste',
            'email' => 'admin@teste.com',
            'password' => Hash::make('senha123'),
            'email_verified_at' => now(),
        ]);

        // Usuário comum para testes
        User::create([
            'name' => 'Usuário Comum',
            'email' => 'user@teste.com',
            'password' => Hash::make('senha123'),
            'email_verified_at' => now(),
        ]);

        // Você pode adicionar mais usuários conforme necessário
    }
}

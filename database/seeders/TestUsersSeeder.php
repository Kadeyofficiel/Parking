<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un nouvel administrateur de test
        User::create([
            'name' => 'Test Admin',
            'email' => 'testadmin@example.com',
            'password' => Hash::make('testadmin123'),
            'role' => 'administrateur',
        ]);

        // Création d'un nouvel utilisateur de test
        User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('testuser123'),
            'role' => 'utilisateur',
        ]);

        $this->command->info('Utilisateurs de test créés avec succès :');
        $this->command->info('Admin: testadmin@example.com / testadmin123');
        $this->command->info('User: testuser@example.com / testuser123');
    }
} 
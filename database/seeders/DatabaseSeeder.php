<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\User;
use App\Models\WaitingList;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création d'un administrateur
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'administrateur',
        ]);

        // Création d'utilisateurs
        $user1 = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => Hash::make('password'),
            'role' => 'utilisateur',
        ]);

        $user2 = User::create([
            'name' => 'Marie Martin',
            'email' => 'marie@example.com',
            'password' => Hash::make('password'),
            'role' => 'utilisateur',
        ]);

        $user3 = User::create([
            'name' => 'Pierre Durand',
            'email' => 'pierre@example.com',
            'password' => Hash::make('password'),
            'role' => 'utilisateur',
        ]);

        // Création de places
        $place1 = Place::create([
            'numero' => 'A1',
            'statut' => 'occupée',
            'user_id' => $user1->id,
        ]);

        $place2 = Place::create([
            'numero' => 'A2',
            'statut' => 'disponible',
        ]);

        $place3 = Place::create([
            'numero' => 'A3',
            'statut' => 'disponible',
        ]);

        $place4 = Place::create([
            'numero' => 'B1',
            'statut' => 'disponible',
        ]);

        $place5 = Place::create([
            'numero' => 'B2',
            'statut' => 'disponible',
        ]);

        // Création de demandes dans la liste d'attente
        WaitingList::create([
            'user_id' => $user2->id,
            'position' => 1,
            'date_demande' => now()->subDays(5),
        ]);

        WaitingList::create([
            'user_id' => $user3->id,
            'position' => 2,
            'date_demande' => now()->subDays(2),
        ]);
    }
}

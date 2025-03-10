<?php

use App\Models\Place;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $user1 = User::where('email', 'jean@example.com')->first();
        $place1 = Place::where('numero', 'A1')->first();

        if ($user1 && $place1) {
            // Réservation active
            Reservation::create([
                'user_id' => $user1->id,
                'place_id' => $place1->id,
                'date_debut' => now()->subDays(30),
                'statut' => 'active',
            ]);

            // Réservation terminée
            Reservation::create([
                'user_id' => $user1->id,
                'place_id' => $place1->id,
                'date_debut' => now()->subDays(90),
                'date_fin' => now()->subDays(60),
                'statut' => 'terminée',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Reservation::truncate();
    }
};

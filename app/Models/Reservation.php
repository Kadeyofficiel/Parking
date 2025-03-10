<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'place_id',
        'date_debut',
        'date_fin',
        'statut',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Relation avec l'utilisateur qui a fait la réservation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la place réservée
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * Vérifie si la réservation est active
     */
    public function isActive(): bool
    {
        return $this->statut === 'active';
    }
}

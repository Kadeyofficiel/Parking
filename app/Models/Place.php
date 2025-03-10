<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero',
        'statut',
        'user_id',
    ];

    /**
     * Relation avec l'utilisateur qui occupe la place
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les réservations
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Vérifie si la place est disponible
     */
    public function isAvailable(): bool
    {
        return $this->statut === 'disponible';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitingList extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'waiting_lists';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'position',
        'date_demande',
        'place_id',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_demande' => 'date',
    ];

    /**
     * Relation avec l'utilisateur qui est sur la liste d'attente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la place demandée
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}

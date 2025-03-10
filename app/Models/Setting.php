<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Récupère la valeur d'un paramètre par sa clé
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Définit la valeur d'un paramètre
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $description
     * @return void
     */
    public static function setValue(string $key, $value, ?string $description = null)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update([
                'value' => $value,
                'description' => $description ?? $setting->description,
            ]);
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
                'description' => $description,
            ]);
        }
    }
} 
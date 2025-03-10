<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Formate une durée en jours, heures, minutes et secondes
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return string
     */
    public static function formatDuration(Carbon $startDate, Carbon $endDate = null): string
    {
        $endDate = $endDate ?? now();
        
        // Calculer la différence en secondes
        $diffInSeconds = $startDate->diffInSeconds($endDate);
        
        // Calculer les jours, heures, minutes et secondes
        $days = floor($diffInSeconds / (60 * 60 * 24));
        $diffInSeconds -= $days * 60 * 60 * 24;
        
        $hours = floor($diffInSeconds / (60 * 60));
        $diffInSeconds -= $hours * 60 * 60;
        
        $minutes = floor($diffInSeconds / 60);
        $seconds = $diffInSeconds - ($minutes * 60);
        
        // Construire la chaîne de format
        $formattedDuration = '';
        
        if ($days > 0) {
            $formattedDuration .= $days . ' jour' . ($days > 1 ? 's' : '') . ' ';
        }
        
        if ($hours > 0 || $days > 0) {
            $formattedDuration .= $hours . 'h ';
        }
        
        if ($minutes > 0 || $hours > 0 || $days > 0) {
            $formattedDuration .= $minutes . 'min ';
        }
        
        $formattedDuration .= $seconds . 's';
        
        return $formattedDuration;
    }
} 
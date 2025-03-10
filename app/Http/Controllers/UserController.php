<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Reservation;
use App\Models\WaitingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Affiche le tableau de bord de l'utilisateur
     */
    public function dashboard()
    {
        $user = Auth::user();
        $currentPlace = Place::where('user_id', $user->id)->first();
        $reservations = Reservation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $waitingList = WaitingList::where('user_id', $user->id)->first();
        
        $position = null;
        if ($waitingList) {
            $position = $waitingList->position;
        }

        return view('user.dashboard', compact('user', 'currentPlace', 'reservations', 'position'));
    }

    /**
     * Affiche le formulaire de demande de réservation
     */
    public function showReservationForm()
    {
        return view('user.reservation-form');
    }

    /**
     * Traite la demande de réservation
     */
    public function requestReservation(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a déjà une place
        if (Place::where('user_id', $user->id)->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous avez déjà une place attribuée.');
        }
        
        // Vérifier si l'utilisateur est déjà sur la liste d'attente
        if (WaitingList::where('user_id', $user->id)->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous êtes déjà sur la liste d\'attente.');
        }
        
        // Ajouter l'utilisateur à la liste d'attente
        $lastPosition = WaitingList::max('position') ?? 0;
        
        WaitingList::create([
            'user_id' => $user->id,
            'position' => $lastPosition + 1,
            'date_demande' => now(),
        ]);
        
        return redirect()->route('dashboard')
            ->with('success', 'Votre demande de réservation a été enregistrée. Vous êtes maintenant sur la liste d\'attente.');
    }

    /**
     * Affiche l'historique des réservations
     */
    public function reservationHistory()
    {
        $reservations = Auth::user()->reservations()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('user.reservation-history', compact('reservations'));
    }
}

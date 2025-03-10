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
        // Récupérer toutes les places de parking
        $places = Place::orderBy('numero')->get();
        
        // Calculer le temps d'attente estimé (position dans la file d'attente)
        $waitingCount = WaitingList::count();
        
        return view('user.reservation-form', compact('places', 'waitingCount'));
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
        
        // Valider la demande
        $request->validate([
            'place_id' => 'required|exists:places,id',
        ]);
        
        $place = Place::findOrFail($request->place_id);
        
        // Si la place est disponible, l'attribuer directement à l'utilisateur
        if ($place->isAvailable()) {
            // Mettre à jour le statut de la place
            $place->update([
                'statut' => 'occupée',
                'user_id' => $user->id,
            ]);
            
            // Créer une nouvelle réservation
            Reservation::create([
                'user_id' => $user->id,
                'place_id' => $place->id,
                'date_debut' => now(),
                'statut' => 'active',
            ]);
            
            return redirect()->route('dashboard')
                ->with('success', 'La place de parking n°' . $place->numero . ' vous a été attribuée avec succès.');
        } else {
            // Ajouter l'utilisateur à la liste d'attente pour cette place spécifique
            $lastPosition = WaitingList::max('position') ?? 0;
            
            WaitingList::create([
                'user_id' => $user->id,
                'position' => $lastPosition + 1,
                'date_demande' => now(),
                'place_id' => $place->id, // Stocker la place demandée
            ]);
            
            return redirect()->route('dashboard')
                ->with('success', 'Votre demande pour la place n°' . $place->numero . ' a été enregistrée. Vous êtes maintenant sur la liste d\'attente.');
        }
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

    /**
     * Ferme une réservation active
     */
    public function closeReservation(Reservation $reservation)
    {
        // Vérifier si la réservation appartient à l'utilisateur
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous n\'êtes pas autorisé à fermer cette réservation.');
        }

        // Vérifier si la réservation est active
        if ($reservation->statut !== 'active') {
            return redirect()->route('dashboard')
                ->with('error', 'Cette réservation n\'est pas active.');
        }

        // Mettre à jour la réservation
        $reservation->update([
            'statut' => 'terminée',
            'date_fin' => now(),
        ]);

        // Libérer la place
        $place = $reservation->place;
        $place->update([
            'statut' => 'disponible',
            'user_id' => null,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Votre réservation a été fermée avec succès.');
    }
}

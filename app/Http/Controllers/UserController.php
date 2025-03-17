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

    /**
     * Affiche le profil de l'utilisateur
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Met à jour le profil de l'utilisateur
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        return redirect()->route('user.profile')
            ->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    /**
     * Affiche les notifications de l'utilisateur
     */
    public function notifications()
    {
        // Ici, vous pourriez implémenter un système de notifications réel
        // Pour l'instant, nous allons simuler des notifications
        $notifications = [
            [
                'id' => 1,
                'type' => 'info',
                'title' => 'Bienvenue sur SmartParking',
                'message' => 'Merci d\'utiliser notre application de gestion de parking.',
                'date' => now()->subDays(1),
                'read' => false
            ],
            [
                'id' => 2,
                'type' => 'warning',
                'title' => 'Rappel de renouvellement',
                'message' => 'N\'oubliez pas de renouveler votre demande de place si nécessaire.',
                'date' => now()->subDays(2),
                'read' => false
            ],
            [
                'id' => 3,
                'type' => 'success',
                'title' => 'Nouvelle fonctionnalité',
                'message' => 'Découvrez les nouvelles statistiques disponibles sur votre tableau de bord.',
                'date' => now()->subDays(3),
                'read' => true
            ],
        ];
        
        return view('user.notifications', ['notifications' => collect($notifications)]);
    }

    /**
     * Marque les notifications comme lues
     */
    public function markNotificationsAsRead(Request $request)
    {
        // Ici, vous implémenteriez la logique pour marquer les notifications comme lues
        // Pour l'instant, nous simulons simplement une réponse réussie
        
        return redirect()->route('user.notifications')
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}

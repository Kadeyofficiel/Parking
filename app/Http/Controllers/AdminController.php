<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\User;
use App\Models\WaitingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administrateur
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPlaces = Place::count();
        $availablePlaces = Place::where('statut', 'disponible')->count();
        $waitingListCount = WaitingList::count();

        return view('admin.dashboard', compact('totalUsers', 'totalPlaces', 'availablePlaces', 'waitingListCount'));
    }

    /**
     * Affiche la liste des utilisateurs
     */
    public function usersList()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un utilisateur
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:utilisateur,administrateur'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Met à jour un utilisateur
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:utilisateur,administrateur'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Réinitialise le mot de passe d'un utilisateur
     */
    public function resetPassword(User $user)
    {
        $password = 'password123'; // Mot de passe par défaut
        
        $user->update([
            'password' => Hash::make($password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Mot de passe réinitialisé avec succès. Nouveau mot de passe: ' . $password);
    }

    /**
     * Supprime un utilisateur
     */
    public function deleteUser(User $user)
    {
        // Vérifier si l'utilisateur a une place attribuée
        $place = Place::where('user_id', $user->id)->first();
        if ($place) {
            // Libérer la place
            $place->update([
                'statut' => 'disponible',
                'user_id' => null
            ]);
        }

        // Vérifier si l'utilisateur a des réservations actives
        $activeReservations = Reservation::where('user_id', $user->id)
            ->where('statut', 'active')
            ->get();
            
        // Annuler les réservations actives
        foreach ($activeReservations as $reservation) {
            $reservation->update(['statut' => 'annulée']);
        }

        // Supprimer l'utilisateur de la liste d'attente s'il y est
        WaitingList::where('user_id', $user->id)->delete();

        // Supprimer l'utilisateur
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Affiche la liste des places
     */
    public function placesList()
    {
        $places = Place::with('user')->paginate(10);
        return view('admin.places.index', compact('places'));
    }

    /**
     * Affiche le formulaire de création d'une place
     */
    public function createPlace()
    {
        return view('admin.places.create');
    }

    /**
     * Enregistre une nouvelle place
     */
    public function storePlace(Request $request)
    {
        $request->validate([
            'numero' => ['required', 'string', 'max:10', 'unique:places'],
        ]);

        Place::create([
            'numero' => $request->numero,
            'statut' => 'disponible',
        ]);

        return redirect()->route('admin.places.index')
            ->with('success', 'Place créée avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'une place
     */
    public function editPlace(Place $place)
    {
        $users = User::all();
        return view('admin.places.edit', compact('place', 'users'));
    }

    /**
     * Met à jour une place
     */
    public function updatePlace(Request $request, Place $place)
    {
        $request->validate([
            'numero' => ['required', 'string', 'max:10', 'unique:places,numero,' . $place->id],
            'statut' => ['required', 'in:disponible,occupée,réservée'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $place->update([
            'numero' => $request->numero,
            'statut' => $request->statut,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('admin.places.index')
            ->with('success', 'Place mise à jour avec succès.');
    }

    /**
     * Supprime une place de parking
     */
    public function deletePlace(Place $place)
    {
        // Vérifier si la place est occupée ou a des réservations
        if ($place->statut === 'occupée') {
            return redirect()->route('admin.places.index')
                ->with('error', 'Impossible de supprimer une place occupée. Veuillez d\'abord libérer la place.');
        }

        // Vérifier s'il y a des réservations actives pour cette place
        $activeReservations = $place->reservations()->where('statut', 'active')->count();
        if ($activeReservations > 0) {
            return redirect()->route('admin.places.index')
                ->with('error', 'Impossible de supprimer une place avec des réservations actives.');
        }

        // Supprimer la place
        $place->delete();

        return redirect()->route('admin.places.index')
            ->with('success', 'Place supprimée avec succès.');
    }

    /**
     * Affiche la liste d'attente
     */
    public function waitingList()
    {
        $waitingList = WaitingList::with('user')
            ->orderBy('position')
            ->paginate(10);
            
        return view('admin.waiting-list.index', compact('waitingList'));
    }

    /**
     * Affiche le formulaire d'édition de la position dans la liste d'attente
     */
    public function editWaitingListPosition(WaitingList $waitingList)
    {
        $maxPosition = WaitingList::count();
        return view('admin.waiting-list.edit', compact('waitingList', 'maxPosition'));
    }

    /**
     * Met à jour la position dans la liste d'attente
     */
    public function updateWaitingListPosition(Request $request, WaitingList $waitingList)
    {
        $request->validate([
            'position' => ['required', 'integer', 'min:1'],
        ]);

        $oldPosition = $waitingList->position;
        $newPosition = $request->position;

        if ($oldPosition < $newPosition) {
            // Déplacer vers le bas
            WaitingList::where('position', '>', $oldPosition)
                ->where('position', '<=', $newPosition)
                ->decrement('position');
        } else if ($oldPosition > $newPosition) {
            // Déplacer vers le haut
            WaitingList::where('position', '<', $oldPosition)
                ->where('position', '>=', $newPosition)
                ->increment('position');
        }

        $waitingList->update([
            'position' => $newPosition,
        ]);

        return redirect()->route('admin.waiting-list')
            ->with('success', 'Position mise à jour avec succès.');
    }

    /**
     * Supprime un utilisateur de la liste d'attente
     */
    public function deleteFromWaitingList(WaitingList $waitingList)
    {
        $userName = $waitingList->user->name;
        $position = $waitingList->position;
        
        // Supprimer l'entrée
        $waitingList->delete();
        
        // Réajuster les positions des autres utilisateurs
        WaitingList::where('position', '>', $position)->decrement('position');
        
        return redirect()->route('admin.waiting-list')
            ->with('success', "L'utilisateur {$userName} a été retiré de la liste d'attente.");
    }

    /**
     * Attribue une place à un utilisateur
     */
    public function assignPlace(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'place_id' => ['required', 'exists:places,id'],
        ]);

        $user = User::find($request->user_id);
        $place = Place::find($request->place_id);

        // Vérifier si la place est disponible
        if ($place->statut !== 'disponible') {
            return back()->with('error', 'Cette place n\'est pas disponible.');
        }

        // Vérifier si l'utilisateur a déjà une place
        $currentPlace = Place::where('user_id', $user->id)->first();
        if ($currentPlace) {
            $currentPlace->update([
                'user_id' => null,
                'statut' => 'disponible',
            ]);
        }

        // Attribuer la nouvelle place
        $place->update([
            'user_id' => $user->id,
            'statut' => 'occupée',
        ]);

        // Créer une réservation
        Reservation::create([
            'user_id' => $user->id,
            'place_id' => $place->id,
            'date_debut' => now(),
            'statut' => 'active',
        ]);

        // Supprimer l'utilisateur de la liste d'attente s'il y est
        $waitingList = WaitingList::where('user_id', $user->id)->first();
        if ($waitingList) {
            $position = $waitingList->position;
            $waitingList->delete();
            
            // Réorganiser les positions
            WaitingList::where('position', '>', $position)
                ->decrement('position');
        }

        return redirect()->route('admin.places.index')
            ->with('success', 'Place attribuée avec succès.');
    }

    /**
     * Affiche l'historique des attributions de places
     */
    public function reservationHistory()
    {
        $reservations = Reservation::with(['user', 'place'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.reservations.history', compact('reservations'));
    }

    /**
     * Affiche la page des paramètres
     */
    public function settings()
    {
        $reservationDuration = Setting::getValue('reservation_duration', 30);
        return view('admin.settings', compact('reservationDuration'));
    }

    /**
     * Met à jour les paramètres
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'reservation_duration' => ['required', 'integer', 'min:1'],
        ]);

        Setting::setValue('reservation_duration', $request->reservation_duration);

        return redirect()->route('admin.settings')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }

    /**
     * Ferme une réservation active
     */
    public function closeReservation(Reservation $reservation)
    {
        // Vérifier si la réservation est active
        if ($reservation->statut !== 'active') {
            return redirect()->route('admin.reservations.history')
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

        return redirect()->route('admin.reservations.history')
            ->with('success', 'La réservation a été fermée avec succès.');
    }
}

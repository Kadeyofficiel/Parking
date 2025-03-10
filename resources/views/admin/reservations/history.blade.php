@extends('layouts.app')

@section('title', 'Historique des réservations')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Historique des réservations</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
        </a>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Toutes les réservations</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Place</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Durée</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->id }}</td>
                                <td>{{ $reservation->user->name }}</td>
                                <td>{{ $reservation->place->numero }}</td>
                                <td>{{ $reservation->date_debut->format('d/m/Y') }}</td>
                                <td>{{ $reservation->date_fin ? $reservation->date_fin->format('d/m/Y') : 'En cours' }}</td>
                                <td>
                                    @if($reservation->date_fin)
                                        {{ \App\Helpers\DateHelper::formatDuration($reservation->date_debut, $reservation->date_fin) }}
                                    @else
                                        {{ \App\Helpers\DateHelper::formatDuration($reservation->date_debut) }} (en cours)
                                    @endif
                                </td>
                                <td>
                                    @if($reservation->statut == 'active')
                                        <span class="badge bg-success">Active</span>
                                        <form action="{{ route('admin.reservations.close', $reservation) }}" method="POST" class="d-inline mt-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir fermer cette réservation ?')">
                                                <i class="fas fa-times-circle"></i> Fermer
                                            </button>
                                        </form>
                                    @elseif($reservation->statut == 'terminée')
                                        <span class="badge bg-secondary">Terminée</span>
                                    @else
                                        <span class="badge bg-danger">Annulée</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
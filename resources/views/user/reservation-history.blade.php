@extends('layouts.app')

@section('title', 'Historique des réservations')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Historique des réservations</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
        </a>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Vos réservations</h5>
        </div>
        <div class="card-body">
            @if($reservations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
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
                                    <td>{{ $reservation->place->numero }}</td>
                                    <td>{{ $reservation->date_debut->format('d/m/Y') }}</td>
                                    <td>{{ $reservation->date_fin ? $reservation->date_fin->format('d/m/Y') : 'En cours' }}</td>
                                    <td>
                                        @if($reservation->date_fin)
                                            {{ $reservation->date_debut->diffInDays($reservation->date_fin) }} jours
                                        @else
                                            {{ $reservation->date_debut->diffInDays(now()) }} jours (en cours)
                                        @endif
                                    </td>
                                    <td>
                                        @if($reservation->statut == 'active')
                                            <span class="badge bg-success">Active</span>
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
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Vous n'avez pas encore de réservations.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 
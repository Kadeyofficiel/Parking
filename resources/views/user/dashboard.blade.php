@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Tableau de bord</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Votre place de parking</h5>
                </div>
                <div class="card-body">
                    @if($currentPlace)
                        <div class="alert alert-success">
                            <h4 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Place attribuée</h4>
                            <p>Vous avez actuellement la place <strong>{{ $currentPlace->numero }}</strong>.</p>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Aucune place attribuée</h4>
                            <p>Vous n'avez pas de place de parking attribuée actuellement.</p>
                            
                            @if($position)
                                <hr>
                                <p class="mb-0">Vous êtes sur la liste d'attente en position <strong>{{ $position }}</strong>.</p>
                            @else
                                <hr>
                                <p class="mb-0">Vous pouvez faire une demande de place en cliquant sur le bouton ci-dessous.</p>
                                <div class="mt-3">
                                    <a href="{{ route('reservation.form') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>Demander une place
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dernières réservations</h5>
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
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations->take(5) as $reservation)
                                        <tr>
                                            <td>{{ $reservation->place->numero }}</td>
                                            <td>{{ $reservation->date_debut->format('d/m/Y') }}</td>
                                            <td>{{ $reservation->date_fin ? $reservation->date_fin->format('d/m/Y') : 'En cours' }}</td>
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
                        <div class="mt-3">
                            <a href="{{ route('reservation.history') }}" class="btn btn-outline-primary">
                                <i class="fas fa-history me-2"></i>Voir tout l'historique
                            </a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Vous n'avez pas encore de réservations.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
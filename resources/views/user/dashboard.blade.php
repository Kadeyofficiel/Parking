@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Tableau de bord utilisateur</h1>
        <div>
            <a href="{{ route('reservation.form') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Nouvelle réservation
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Ma place actuelle</h5>
                </div>
                <div class="card-body">
                    @if($currentPlace)
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle p-3 me-3 text-white">
                                <i class="fas fa-parking fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">Place n° {{ $currentPlace->numero }}</h4>
                                <p class="text-muted mb-0">Attribuée depuis le {{ now()->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Statut: <span class="badge bg-success">Occupée</span></span>
                            @if($reservations && $reservations->where('statut', 'active')->first())
                                <form action="{{ route('reservation.close', $reservations->where('statut', 'active')->first()->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir libérer cette place?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-sign-out-alt me-1"></i> Libérer ma place
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('reservation.close', ['reservation' => $currentPlace->reservations()->where('statut', 'active')->first()->id ?? 0]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir libérer cette place?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-sign-out-alt me-1"></i> Libérer ma place
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-car-side fa-4x text-muted"></i>
                            </div>
                            <h5>Vous n'avez pas de place attribuée</h5>
                            <p class="text-muted">Faites une demande de réservation pour obtenir une place de parking.</p>
                            <a href="{{ route('reservation.form') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Demander une place
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Liste d'attente</h5>
                </div>
                <div class="card-body">
                    @if($position)
                        <div class="text-center py-4">
                            <div class="position-relative mb-4">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ (1 / $position) * 100 }}%" aria-valuenow="{{ $position }}" aria-valuemin="0" aria-valuemax="10"></div>
                                </div>
                            </div>
                            <h3 class="mb-3">Position: <span class="text-primary">{{ $position }}</span></h3>
                            <p class="text-muted">Vous êtes actuellement sur la liste d'attente. Vous serez notifié lorsqu'une place sera disponible.</p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-clipboard-list fa-4x text-muted"></i>
                            </div>
                            <h5>Vous n'êtes pas sur la liste d'attente</h5>
                            <p class="text-muted">Si toutes les places sont occupées, vous pouvez vous inscrire sur la liste d'attente.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes réservations récentes</h5>
                    <a href="{{ route('reservation.history') }}" class="btn btn-sm btn-outline-primary">
                        Voir tout l'historique
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($reservations) && $reservations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Place</th>
                                        <th>Date de début</th>
                                        <th>Date de fin</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations->take(5) as $reservation)
                                        <tr>
                                            <td>{{ $reservation->place->numero ?? 'N/A' }}</td>
                                            <td>{{ $reservation->date_debut ? $reservation->date_debut->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>{{ $reservation->date_fin ? $reservation->date_fin->format('d/m/Y H:i') : 'En cours' }}</td>
                                            <td>
                                                @if($reservation->statut == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($reservation->statut == 'terminée')
                                                    <span class="badge bg-secondary">Terminée</span>
                                                @else
                                                    <span class="badge bg-warning">{{ $reservation->statut }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($reservation->statut == 'active')
                                                    <form action="{{ route('reservation.close', $reservation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir libérer cette place?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-sign-out-alt"></i> Libérer
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-check"></i> Terminée
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-history fa-4x text-muted"></i>
                            </div>
                            <h5>Aucune réservation</h5>
                            <p class="text-muted">Vous n'avez pas encore effectué de réservation.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
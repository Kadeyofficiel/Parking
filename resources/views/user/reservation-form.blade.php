@extends('layouts.app')

@section('title', 'Demande de place')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Demande de place de parking</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Choisissez une place de parking parmi celles disponibles ci-dessous. Si la place est disponible, elle vous sera attribuée immédiatement. Sinon, vous serez ajouté à la liste d'attente.
                    </div>
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Numéro</th>
                                    <th>Statut</th>
                                    <th>Temps d'attente estimé</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($places as $place)
                                <tr>
                                    <td>{{ $place->numero }}</td>
                                    <td>
                                        @if($place->isAvailable())
                                            <span class="badge bg-success">Disponible</span>
                                        @else
                                            <span class="badge bg-danger">Occupée</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($place->isAvailable())
                                            <span class="text-success">Immédiat</span>
                                        @else
                                            <span class="text-warning">{{ $waitingCount }} personne(s) en attente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('reservation.request') }}">
                                            @csrf
                                            <input type="hidden" name="place_id" value="{{ $place->id }}">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                @if($place->isAvailable())
                                                    Réserver maintenant
                                                @else
                                                    Rejoindre la file d'attente
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
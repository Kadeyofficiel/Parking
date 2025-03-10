@extends('layouts.app')

@section('title', 'Liste d\'attente')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste d'attente</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
        </a>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Utilisateurs en attente</h5>
        </div>
        <div class="card-body">
            @if($waitingList->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Date de demande</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waitingList as $item)
                                <tr>
                                    <td>{{ $item->position }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->date_demande->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.waiting-list.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Modifier position
                                            </a>
                                            
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                            
                                            <!-- Modal de confirmation de suppression -->
                                            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">Confirmer la suppression</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Êtes-vous sûr de vouloir retirer <strong>{{ $item->user->name }}</strong> de la liste d'attente ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <form action="{{ route('admin.waiting-list.delete', $item) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if(\App\Models\Place::where('statut', 'disponible')->exists())
                                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#assignModal{{ $item->id }}">
                                                    <i class="fas fa-check-circle"></i> Attribuer place
                                                </button>
                                                
                                                <!-- Modal d'attribution de place -->
                                                <div class="modal fade" id="assignModal{{ $item->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $item->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="assignModalLabel{{ $item->id }}">Attribuer une place à {{ $item->user->name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('admin.assign-place') }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="user_id" value="{{ $item->user->id }}">
                                                                    <div class="mb-3">
                                                                        <label for="place_id" class="form-label">Sélectionner une place</label>
                                                                        <select id="place_id" name="place_id" class="form-select" required>
                                                                            @foreach(\App\Models\Place::where('statut', 'disponible')->get() as $place)
                                                                                <option value="{{ $place->id }}">{{ $place->numero }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-success">Attribuer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $waitingList->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>La liste d'attente est vide.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 
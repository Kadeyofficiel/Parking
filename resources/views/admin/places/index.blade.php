@extends('layouts.app')

@section('title', 'Gestion des places')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des places</h1>
        <a href="{{ route('admin.places.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Ajouter une place
        </a>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Attribution manuelle de place</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.assign-place') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-5">
                    <label for="user_id" class="form-label">Utilisateur</label>
                    <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">Sélectionner un utilisateur</option>
                        @foreach(\App\Models\User::where('role', 'utilisateur')->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-5">
                    <label for="place_id" class="form-label">Place</label>
                    <select id="place_id" name="place_id" class="form-select @error('place_id') is-invalid @enderror" required>
                        <option value="">Sélectionner une place</option>
                        @foreach(\App\Models\Place::where('statut', 'disponible')->get() as $place)
                            <option value="{{ $place->id }}">{{ $place->numero }}</option>
                        @endforeach
                    </select>
                    @error('place_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check-circle me-2"></i>Attribuer
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Liste des places</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Numéro</th>
                            <th>Statut</th>
                            <th>Occupée par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($places as $place)
                            <tr>
                                <td>{{ $place->id }}</td>
                                <td>{{ $place->numero }}</td>
                                <td>
                                    @if($place->statut == 'disponible')
                                        <span class="badge bg-success">Disponible</span>
                                    @elseif($place->statut == 'occupée')
                                        <span class="badge bg-danger">Occupée</span>
                                    @else
                                        <span class="badge bg-warning">Réservée</span>
                                    @endif
                                </td>
                                <td>
                                    @if($place->user)
                                        {{ $place->user->name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.places.edit', $place) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.places.delete', $place) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette place ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $places->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
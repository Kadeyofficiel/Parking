@extends('layouts.app')

@section('title', 'Modifier une place')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Modifier une place</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.places.update', $place) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="numero" class="form-label">Numéro de place</label>
                            <input id="numero" type="text" class="form-control @error('numero') is-invalid @enderror" name="numero" value="{{ old('numero', $place->numero) }}" required autofocus>
                            @error('numero')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select id="statut" class="form-select @error('statut') is-invalid @enderror" name="statut" required>
                                <option value="disponible" {{ (old('statut', $place->statut) == 'disponible') ? 'selected' : '' }}>Disponible</option>
                                <option value="occupée" {{ (old('statut', $place->statut) == 'occupée') ? 'selected' : '' }}>Occupée</option>
                                <option value="réservée" {{ (old('statut', $place->statut) == 'réservée') ? 'selected' : '' }}>Réservée</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Utilisateur</label>
                            <select id="user_id" class="form-select @error('user_id') is-invalid @enderror" name="user_id">
                                <option value="">Aucun utilisateur</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('user_id', $place->user_id) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.places.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
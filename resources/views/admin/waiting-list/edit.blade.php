@extends('layouts.app')

@section('title', 'Modifier la position')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Modifier la position dans la liste d'attente</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Vous modifiez la position de <strong>{{ $waitingList->user->name }}</strong> dans la liste d'attente.
                    </div>
                    
                    <form method="POST" action="{{ route('admin.waiting-list.update', $waitingList) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="position" class="form-label">Position actuelle</label>
                            <input type="text" class="form-control" value="{{ $waitingList->position }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Nouvelle position</label>
                            <input id="position" type="number" class="form-control @error('position') is-invalid @enderror" name="position" value="{{ old('position', $waitingList->position) }}" min="1" max="{{ $maxPosition }}" required>
                            @error('position')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                La position doit être comprise entre 1 et {{ $maxPosition }}.
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.waiting-list') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
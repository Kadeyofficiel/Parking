@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Paramètres de l'application</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
        </a>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Configuration générale</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="reservation_duration" class="form-label">Durée par défaut des réservations (en jours)</label>
                    <input type="number" class="form-control @error('reservation_duration') is-invalid @enderror" id="reservation_duration" name="reservation_duration" value="{{ $reservationDuration }}" min="1" required>
                    <div class="form-text">Cette durée sera appliquée à toutes les nouvelles réservations.</div>
                    @error('reservation_duration')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Enregistrer les paramètres
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 
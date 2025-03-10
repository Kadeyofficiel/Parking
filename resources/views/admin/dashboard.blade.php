@extends('layouts.app')

@section('title', 'Administration')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Tableau de bord administrateur</h1>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Utilisateurs</h5>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="text-white">Voir les détails</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-success mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Places</h5>
                            <h2 class="mb-0">{{ $totalPlaces }}</h2>
                        </div>
                        <i class="fas fa-parking fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.places.index') }}" class="text-white">Voir les détails</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-info mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Places disponibles</h5>
                            <h2 class="mb-0">{{ $availablePlaces }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.places.index') }}" class="text-white">Voir les détails</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Liste d'attente</h5>
                            <h2 class="mb-0">{{ $waitingListCount }}</h2>
                        </div>
                        <i class="fas fa-list fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.waiting-list') }}" class="text-white">Voir les détails</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('admin.users.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-plus me-2"></i>Ajouter un utilisateur
                        </a>
                        <a href="{{ route('admin.places.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle me-2"></i>Ajouter une place
                        </a>
                        <a href="{{ route('admin.waiting-list') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-list me-2"></i>Gérer la liste d'attente
                        </a>
                        <a href="{{ route('admin.reservations.history') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-history me-2"></i>Consulter l'historique
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations système</h5>
                </div>
                <div class="card-body">
                    <p><strong>Version de l'application :</strong> 1.0.0</p>
                    <p><strong>Date :</strong> {{ now()->format('d/m/Y') }}</p>
                    <p><strong>Heure :</strong> {{ now()->format('H:i') }}</p>
                    <p><strong>Administrateur connecté :</strong> {{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
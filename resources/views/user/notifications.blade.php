@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Mes Notifications</h1>
        <div>
            <form action="{{ route('user.notifications.mark-read') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary me-2">
                    <i class="fas fa-check-double me-1"></i> Marquer tout comme lu
                </button>
            </form>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour au tableau de bord
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active">
                            <i class="fas fa-bell me-2"></i> Toutes les notifications
                            <span class="badge bg-primary rounded-pill float-end">{{ $notifications->count() }}</span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-envelope me-2"></i> Non lues
                            <span class="badge bg-danger rounded-pill float-end">{{ $notifications->where('read', false)->count() }}</span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-info-circle me-2"></i> Informations
                            <span class="badge bg-info rounded-pill float-end">{{ $notifications->where('type', 'info')->count() }}</span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-exclamation-triangle me-2"></i> Alertes
                            <span class="badge bg-warning rounded-pill float-end">{{ $notifications->where('type', 'warning')->count() }}</span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-check-circle me-2"></i> Succès
                            <span class="badge bg-success rounded-pill float-end">{{ $notifications->where('type', 'success')->count() }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste des notifications</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-sort me-1"></i> Trier par
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-calendar-alt me-2"></i>Date (récent d'abord)</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-calendar-alt me-2"></i>Date (ancien d'abord)</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sort-alpha-down me-2"></i>Type</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Non lu d'abord</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action {{ $notification['read'] ? 'bg-light' : '' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h5 class="mb-1">
                                            @if($notification['type'] == 'info')
                                                <i class="fas fa-info-circle text-info me-2"></i>
                                            @elseif($notification['type'] == 'warning')
                                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                            @elseif($notification['type'] == 'success')
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                            @endif
                                            {{ $notification['title'] }}
                                            @if(!$notification['read'])
                                                <span class="badge bg-danger ms-2">Nouveau</span>
                                            @endif
                                        </h5>
                                        <small class="text-muted">{{ $notification['date']->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $notification['message'] }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">{{ $notification['date']->format('d/m/Y H:i') }}</small>
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="display-1 text-muted mb-3">
                                <i class="fas fa-bell-slash"></i>
                            </div>
                            <h4>Aucune notification</h4>
                            <p class="text-muted">Vous n'avez pas de notifications pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Préférences de notification</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Gérez vos préférences de notification pour contrôler les types de notifications que vous recevez.</p>
                    <a href="{{ route('user.profile') }}" class="btn btn-primary">
                        <i class="fas fa-cog me-1"></i> Gérer les préférences
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
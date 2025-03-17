@extends('layouts.app')

@section('title', 'Administration')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Tableau de bord administrateur</h1>
        <div>
            <button class="btn btn-sm btn-outline-secondary me-2" id="refreshStats">
                <i class="fas fa-sync-alt me-1"></i> Actualiser
            </button>
            <a href="{{ route('admin.settings') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-cog me-1"></i> Paramètres
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card text-white bg-primary mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Utilisateurs</h6>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none">Voir les détails</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card text-white bg-success mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Places totales</h6>
                            <h2 class="mb-0">{{ $totalPlaces }}</h2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-parking"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.places.index') }}" class="text-white text-decoration-none">Voir les détails</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card text-white bg-info mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Places disponibles</h6>
                            <h2 class="mb-0">{{ $availablePlaces }}</h2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $totalPlaces > 0 ? ($availablePlaces / $totalPlaces) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.places.index') }}" class="text-white text-decoration-none">Voir les détails</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card text-white bg-warning mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Liste d'attente</h6>
                            <h2 class="mb-0">{{ $waitingListCount }}</h2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.waiting-list') }}" class="text-white text-decoration-none">Voir les détails</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Statistiques d'occupation</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-calendar me-1"></i> Période
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#" data-period="week">Cette semaine</a></li>
                            <li><a class="dropdown-item" href="#" data-period="month">Ce mois</a></li>
                            <li><a class="dropdown-item" href="#" data-period="year">Cette année</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="occupationChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Répartition des places</h5>
                </div>
                <div class="card-body">
                    <canvas id="placesChart" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Actions rapides</h5>
                    <span class="badge bg-primary">{{ now()->format('d/m/Y') }}</span>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('admin.users.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user-plus me-2 text-primary"></i>
                                <span>Ajouter un utilisateur</span>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        <a href="{{ route('admin.places.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-plus-circle me-2 text-success"></i>
                                <span>Ajouter une place</span>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        <a href="{{ route('admin.waiting-list') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-list me-2 text-warning"></i>
                                <span>Gérer la liste d'attente</span>
                            </div>
                            <span class="badge bg-warning rounded-pill">{{ $waitingListCount }}</span>
                        </a>
                        <a href="{{ route('admin.reservations.history') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-history me-2 text-info"></i>
                                <span>Consulter l'historique</span>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Activité récente</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @for ($i = 0; $i < 5; $i++)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        @if($i % 3 == 0)
                                            <i class="fas fa-user-plus text-success me-2"></i> Nouvel utilisateur inscrit
                                        @elseif($i % 3 == 1)
                                            <i class="fas fa-parking text-primary me-2"></i> Place attribuée
                                        @else
                                            <i class="fas fa-sign-out-alt text-warning me-2"></i> Place libérée
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ now()->subHours($i * 2)->format('H:i') }}</small>
                                </div>
                                <p class="mb-1">
                                    @if($i % 3 == 0)
                                        Un nouvel utilisateur s'est inscrit sur la plateforme.
                                    @elseif($i % 3 == 1)
                                        La place n°{{ rand(1, 20) }} a été attribuée à un utilisateur.
                                    @else
                                        La place n°{{ rand(1, 20) }} a été libérée.
                                    @endif
                                </p>
                            </div>
                        @endfor
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.reservations.history') }}" class="btn btn-sm btn-outline-primary">Voir toutes les activités</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations système</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="border-start border-4 border-primary ps-3 mb-3">
                                <p class="text-muted mb-1">Version de l'application</p>
                                <h5 class="mb-0">2.0.0</h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-start border-4 border-success ps-3 mb-3">
                                <p class="text-muted mb-1">Date</p>
                                <h5 class="mb-0">{{ now()->format('d/m/Y') }}</h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-start border-4 border-info ps-3 mb-3">
                                <p class="text-muted mb-1">Heure</p>
                                <h5 class="mb-0">{{ now()->format('H:i') }}</h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-start border-4 border-warning ps-3 mb-3">
                                <p class="text-muted mb-1">Administrateur connecté</p>
                                <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart for occupation statistics
        const occupationCtx = document.getElementById('occupationChart').getContext('2d');
        const occupationChart = new Chart(occupationCtx, {
            type: 'line',
            data: {
                labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
                datasets: [{
                    label: 'Places occupées',
                    data: [{{ $totalPlaces - $availablePlaces }}, {{ rand($totalPlaces - $availablePlaces - 2, $totalPlaces - $availablePlaces + 2) }}, {{ rand($totalPlaces - $availablePlaces - 3, $totalPlaces - $availablePlaces + 3) }}, {{ rand($totalPlaces - $availablePlaces - 2, $totalPlaces - $availablePlaces + 2) }}, {{ rand($totalPlaces - $availablePlaces - 4, $totalPlaces - $availablePlaces + 1) }}, {{ rand($totalPlaces - $availablePlaces - 5, $totalPlaces - $availablePlaces - 2) }}, {{ rand($totalPlaces - $availablePlaces - 6, $totalPlaces - $availablePlaces - 3) }}],
                    backgroundColor: 'rgba(67, 97, 238, 0.2)',
                    borderColor: 'rgba(67, 97, 238, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: {{ $totalPlaces }},
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        
        // Chart for places distribution
        const placesCtx = document.getElementById('placesChart').getContext('2d');
        const placesChart = new Chart(placesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Occupées', 'Disponibles', 'En maintenance'],
                datasets: [{
                    data: [{{ $totalPlaces - $availablePlaces }}, {{ $availablePlaces }}, {{ rand(0, 2) }}],
                    backgroundColor: [
                        'rgba(67, 97, 238, 0.8)',
                        'rgba(76, 201, 240, 0.8)',
                        'rgba(247, 37, 133, 0.8)'
                    ],
                    borderColor: [
                        'rgba(67, 97, 238, 1)',
                        'rgba(76, 201, 240, 1)',
                        'rgba(247, 37, 133, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });
        
        // Handle period change for occupation chart
        document.querySelectorAll('[data-period]').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                const period = event.target.getAttribute('data-period');
                let labels, data;
                
                switch(period) {
                    case 'week':
                        labels = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                        data = [{{ $totalPlaces - $availablePlaces }}, {{ rand($totalPlaces - $availablePlaces - 2, $totalPlaces - $availablePlaces + 2) }}, {{ rand($totalPlaces - $availablePlaces - 3, $totalPlaces - $availablePlaces + 3) }}, {{ rand($totalPlaces - $availablePlaces - 2, $totalPlaces - $availablePlaces + 2) }}, {{ rand($totalPlaces - $availablePlaces - 4, $totalPlaces - $availablePlaces + 1) }}, {{ rand($totalPlaces - $availablePlaces - 5, $totalPlaces - $availablePlaces - 2) }}, {{ rand($totalPlaces - $availablePlaces - 6, $totalPlaces - $availablePlaces - 3) }}];
                        break;
                    case 'month':
                        labels = ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4'];
                        data = [{{ rand($totalPlaces - $availablePlaces - 2, $totalPlaces - $availablePlaces + 2) }}, {{ rand($totalPlaces - $availablePlaces - 1, $totalPlaces - $availablePlaces + 3) }}, {{ rand($totalPlaces - $availablePlaces - 3, $totalPlaces - $availablePlaces + 1) }}, {{ $totalPlaces - $availablePlaces }}];
                        break;
                    case 'year':
                        labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                        data = [
                            {{ rand($totalPlaces - $availablePlaces - 4, $totalPlaces - $availablePlaces) }}, 
                            {{ rand($totalPlaces - $availablePlaces - 3, $totalPlaces - $availablePlaces + 1) }}, 
                            {{ rand($totalPlaces - $availablePlaces - 2, $totalPlaces - $availablePlaces + 2) }}, 
                            {{ rand($totalPlaces - $availablePlaces - 1, $totalPlaces - $availablePlaces + 3) }}, 
                            {{ rand($totalPlaces - $availablePlaces - 2, $totalPlaces - $availablePlaces + 2) }}, 
                            {{ rand($totalPlaces - $availablePlaces - 3, $totalPlaces - $availablePlaces + 1) }}, 
                            {{ rand($totalPlaces - $availablePlaces - 4, $totalPlaces - $availablePlaces) }}, 
                            {{ rand($totalPlaces - $availablePlaces - 5, $totalPlaces - $availablePlaces - 1) }}, 
                            {{ rand($totalPlaces - $availablePlaces - 3, $totalPlaces - $availablePlaces + 1) }}, 
                            {{ rand($totalPlaces - $availablePlaces - 2, $totalPlaces - $availablePlaces + 2) }}, 
                            {{ rand($totalPlaces - $availablePlaces - 1, $totalPlaces - $availablePlaces + 3) }}, 
                            {{ $totalPlaces - $availablePlaces }}
                        ];
                        break;
                }
                
                occupationChart.data.labels = labels;
                occupationChart.data.datasets[0].data = data;
                occupationChart.update();
            });
        });
        
        // Refresh button
        document.getElementById('refreshStats').addEventListener('click', function() {
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Actualisation...';
            this.disabled = true;
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    });
</script>
@endsection 
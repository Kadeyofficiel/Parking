<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Web
|--------------------------------------------------------------------------
|
| Voici où vous pouvez enregistrer les routes web pour votre application.
| Ces routes sont chargées par le RouteServiceProvider et toutes seront
| assignées au groupe de middleware "web".
|
*/

// Routes publiques
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes de réinitialisation du mot de passe
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Dashboard utilisateur
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    
    // Gestion du mot de passe
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    // Réservations utilisateur
    Route::get('/reservation/new', [UserController::class, 'showReservationForm'])->name('reservation.form');
    Route::post('/reservation/request', [UserController::class, 'requestReservation'])->name('reservation.request');
    Route::get('/reservation/history', [UserController::class, 'reservationHistory'])->name('reservation.history');
    Route::post('/reservation/{reservation}/close', [UserController::class, 'closeReservation'])->name('reservation.close');
    
    // Routes administrateur
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Gestion des utilisateurs
        Route::get('/users', [AdminController::class, 'usersList'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // Gestion des places
        Route::get('/places', [AdminController::class, 'placesList'])->name('places.index');
        Route::get('/places/create', [AdminController::class, 'createPlace'])->name('places.create');
        Route::post('/places', [AdminController::class, 'storePlace'])->name('places.store');
        Route::get('/places/{place}/edit', [AdminController::class, 'editPlace'])->name('places.edit');
        Route::put('/places/{place}', [AdminController::class, 'updatePlace'])->name('places.update');
        Route::delete('/places/{place}', [AdminController::class, 'deletePlace'])->name('places.delete');
        
        // Attribution de place
        Route::post('/assign-place', [AdminController::class, 'assignPlace'])->name('assign-place');
        
        // Liste d'attente
        Route::get('/waiting-list', [AdminController::class, 'waitingList'])->name('waiting-list');
        Route::get('/waiting-list/{waitingList}/edit', [AdminController::class, 'editWaitingListPosition'])->name('waiting-list.edit');
        Route::put('/waiting-list/{waitingList}', [AdminController::class, 'updateWaitingListPosition'])->name('waiting-list.update');
        Route::delete('/waiting-list/{waitingList}', [AdminController::class, 'deleteFromWaitingList'])->name('waiting-list.delete');
        
        // Historique des réservations
        Route::get('/reservations/history', [AdminController::class, 'reservationHistory'])->name('reservations.history');
        Route::post('/reservations/{reservation}/close', [AdminController::class, 'closeReservation'])->name('reservations.close');
        
        // Paramètres de l'application
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CooperacionController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DashboardController;

//  Redirigir raíz al login
Route::get('/', fn() => redirect()->route('login'));

// Route::get('/', fn() => redirect()->route('welcome'));

//  Autenticación (sin auth)
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

//  Rutas protegidas
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cooperaciones (CRUD completo via resource)
    Route::resource('cooperaciones', CooperacionController::class);

    // Participantes de una cooperación
    Route::post(
        '/cooperaciones/{cooperacion}/participantes',
        [ParticipanteController::class, 'store']
    )->name('participantes.store');

    Route::delete(
        '/cooperaciones/{cooperacion}/participantes/{participante}',
        [ParticipanteController::class, 'destroy']
    )->name('participantes.destroy');

    // Registrar pago
    Route::post(
        '/cooperaciones/{cooperacion}/pagos',
        [PagoController::class, 'store']
    )->name('pagos.store');

    // Gestión de pagos (solo admin)
    Route::middleware('admin')->group(function () {
        Route::get('/pagos',                 [PagoController::class, 'index'])->name('pagos.index');
        Route::patch('/pagos/{pago}/estado', [PagoController::class, 'updateEstado'])->name('pagos.updateEstado');
        Route::delete('/pagos/{pago}',       [PagoController::class, 'destroy'])->name('pagos.destroy');
    });
});

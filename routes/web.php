<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PakaianController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () { return redirect('/home'); });

// Jalur Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

// Jalur Customer (Proteksi Auth)
Route::get('/home', [PakaianController::class, 'home'])->middleware('auth');
Route::get('/katalog', [PakaianController::class, 'katalog'])->middleware('auth');

// Jalur Admin (Proteksi Auth)
Route::get('/dashboard-admin', [PakaianController::class, 'adminIndex'])->middleware('auth');
Route::post('/dashboard-admin', [PakaianController::class, 'store'])->middleware('auth');
Route::delete('/dashboard-admin/{id}', [PakaianController::class, 'destroy'])->middleware('auth');
Route::put('/dashboard-admin/{id}', [PakaianController::class, 'update'])->middleware('auth');



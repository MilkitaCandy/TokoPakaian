<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PakaianController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminTransaksiController;

Route::get('/', function () { return redirect('/home'); });

// Jalur Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

// Jalur Customer (Bebas Akses / Login Opsional)
Route::get('/home', [PakaianController::class, 'home']);
Route::get('/katalog', [PakaianController::class, 'katalog']);

// Jalur Admin (Proteksi Auth)
Route::get('/dashboard-admin', [PakaianController::class, 'adminIndex'])->middleware('auth');
Route::post('/dashboard-admin', [PakaianController::class, 'store'])->middleware('auth');
Route::delete('/dashboard-admin/{id}', [PakaianController::class, 'destroy'])->middleware('auth');
Route::put('/dashboard-admin/{id}', [PakaianController::class, 'update'])->middleware('auth');

// Jalur Cart
Route::post('/cart/add', [CartController::class, 'add']);
Route::post('/cart/remove', [CartController::class, 'remove']);
Route::get('/checkout', [CartController::class, 'checkout'])->middleware('auth');
Route::post('/checkout/process', [CartController::class, 'process'])->middleware('auth');

// Jalur Admin Transaksi (Proteksi Auth)
Route::get('/admin/transaksi', [AdminTransaksiController::class, 'index'])->middleware('auth');
// Rute Eksekusi Ubah Status
Route::post('/admin/transaksi/{id}/status', [AdminTransaksiController::class, 'updateStatus'])->middleware('auth');
// Jalur Riwayat Transaksi Customer
Route::get('/riwayat', [CartController::class, 'riwayat']);

Route::get('/checkout/bayar-lagi/{invoice}', [App\Http\Controllers\CartController::class, 'bayarLagi'])->middleware('auth');

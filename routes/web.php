<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman login (bebas akses)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

// Rute yang butuh login
Route::middleware('loginuser')->group(function () {
    Route::get('/', function () { return redirect()->route('dashboard'); });
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    Route::resource('incoming-letters', SuratMasukController::class)->only([
        'index', 'create', 'store', 'show', 'destroy'
    ]);

    Route::get('outgoing-letters/{outgoing_letter}/pdf', [SuratKeluarController::class, 'generateSuratKeluar'])->name('outgoing-letters.pdf');

    Route::resource('outgoing-letters', SuratKeluarController::class)->only([
        'index', 'create', 'store', 'show'
    ]);
    
    // Logout (POST) dalam proteksi login
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

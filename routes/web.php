<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManajemenController;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua rute aplikasi web kamu didefinisikan di sini.
|
*/

// ===============================
// 🔓 AUTH (bebas akses)
// ===============================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

// ===============================
// 🔐 PROTEKSI LOGIN
// ===============================
Route::middleware('loginuser')->group(function () {

    // Redirect root ke dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // ===============================
    // 🏠 DASHBOARD
    // ===============================
    Route::get('/dashboard', function () {
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();

        return view('dashboard', compact('totalSuratMasuk', 'totalSuratKeluar'));
    })->name('dashboard');

    // ===============================
    // ✉️ SURAT MASUK
    // ===============================
    Route::resource('incoming-letters', SuratMasukController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    // ===============================
    // 📤 SURAT KELUAR
    // ===============================
    Route::get('outgoing-letters/{outgoing_letter}/pdf', [SuratKeluarController::class, 'generateSuratKeluar'])
        ->name('outgoing-letters.pdf');

    Route::get('outgoing-letters/backfill', [SuratKeluarController::class, 'backfillNomorSurat'])
        ->name('outgoing-letters.backfill');

    Route::resource('outgoing-letters', SuratKeluarController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

    // ===============================
    // 👥 USER MANAJEMEN
    // ===============================
    Route::resource('user_manajemen', UserManajemenController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['user_manajemen' => 'user']);

    // ===============================
    // 🚪 LOGOUT
    // ===============================
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

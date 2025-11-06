<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManajemenController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use App\Http\Controllers\NomorSuratController;
use Illuminate\Support\Facades\DB;
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
    Route::get('/dashboard', function (Request $request) {
        $incomingCount = SuratMasuk::count();
        $outgoingCount = SuratKeluar::count();

        $recentIncoming = SuratMasuk::latest()->limit(5)->get()->map(function ($letter) {
            $letter->type = 'in';
            return $letter;
        });

        $recentOutgoing = SuratKeluar::latest()->limit(5)->get()->map(function ($letter) {
            $letter->type = 'out';
            return $letter;
        });

        $recentLetters = $recentIncoming->merge($recentOutgoing)->sortByDesc('updated_at')->take(5);

        // --- Logika Data Chart dengan Filter ---
        $range = $request->input('range', '6m'); // Default 6 bulan
        $chartLabels = [];
        $incomingData = [];
        $outgoingData = [];

        switch ($range) {
            case 'weekly': // 7 hari terakhir
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $chartLabels[] = $date->isoFormat('dddd'); // Nama hari: Senin, Selasa
                    $incomingData[] = SuratMasuk::whereDate('tanggal_surat', $date->toDateString())->count();
                    $outgoingData[] = SuratKeluar::whereDate('tanggal_surat', $date->toDateString())->count();
                }
                break;
            case 'monthly': // 30 hari terakhir
                for ($i = 29; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $chartLabels[] = $date->format('d M'); // Format: 23 Apr
                    $incomingData[] = SuratMasuk::whereDate('tanggal_surat', $date->toDateString())->count();
                    $outgoingData[] = SuratKeluar::whereDate('tanggal_surat', $date->toDateString())->count();
                }
                break;
            case '3m': // 3 bulan terakhir
                for ($i = 2; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $chartLabels[] = $month->isoFormat('MMMM'); // Nama bulan: April
                    $incomingData[] = SuratMasuk::whereYear('tanggal_surat', $month->year)->whereMonth('tanggal_surat', $month->month)->count();
                    $outgoingData[] = SuratKeluar::whereYear('tanggal_surat', $month->year)->whereMonth('tanggal_surat', $month->month)->count();
                }
                break;
            case '1y': // 12 bulan terakhir
                for ($i = 11; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $chartLabels[] = $month->isoFormat('MMM YYYY'); // Format: Apr 2024
                    $incomingData[] = SuratMasuk::whereYear('tanggal_surat', $month->year)->whereMonth('tanggal_surat', $month->month)->count();
                    $outgoingData[] = SuratKeluar::whereYear('tanggal_surat', $month->year)->whereMonth('tanggal_surat', $month->month)->count();
                }
                break;
            case '6m': // 6 bulan terakhir (default)
            default:
                for ($i = 5; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $chartLabels[] = $month->isoFormat('MMMM'); // Nama bulan: April
                    $incomingData[] = SuratMasuk::whereYear('tanggal_surat', $month->year)->whereMonth('tanggal_surat', $month->month)->count();
                    $outgoingData[] = SuratKeluar::whereYear('tanggal_surat', $month->year)->whereMonth('tanggal_surat', $month->month)->count();
                }
                break;
        }

        $chartData = compact('chartLabels', 'incomingData', 'outgoingData');

        return view('dashboard', compact('incomingCount', 'outgoingCount', 'recentLetters', 'chartData', 'range'));
    })->name('dashboard');

    Route::resource('incoming-letters', SuratMasukController::class)->only([
        'index', 'create', 'store', 'show', 'destroy'
    ]);

    Route::get('outgoing-letters/{outgoing_letter}/pdf', [SuratKeluarController::class, 'generateSuratKeluar'])->name('outgoing-letters.pdf');
    Route::get('outgoing-letters/backfill', [SuratKeluarController::class, 'backfillNomorSurat'])->name('outgoing-letters.backfill');

    Route::resource('outgoing-letters', SuratKeluarController::class)->only([
        'index', 'create', 'store', 'show', 'edit', 'update', 'destroy'
    ]);

    // Manajemen Kode Surat (modal-based CRUD)
    Route::resource('letter_code', NomorSuratController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    
    // Manajemen User (modal-based CRUD)
    Route::resource('user_manajemen', UserManajemenController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['user_manajemen' => 'user']);
    
    // Logout (POST) dalam proteksi login
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rute untuk pencarian
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // Rute untuk profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

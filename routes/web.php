<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\UserManajemenController;
use App\Http\Controllers\PerihalSuratController;
use App\Http\Controllers\PenandatanganController;
use App\Http\Controllers\NomorSuratController;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;

// ===================== Auth (tanpa proteksi) =====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

// ===================== Protected =====================
Route::middleware('loginuser')->group(function () {

    // Root -> dashboard
    Route::get('/', fn() => redirect()->route('dashboard'));

    // ----- Dashboard (pakai closure) -----
    Route::get('/dashboard', function (Request $request) {
        $incomingCount = SuratMasuk::count();
        $outgoingCount = SuratKeluar::count();

        // Ambil 5 terbaru gabungan masuk/keluar
        $recentIncoming = SuratMasuk::latest()->limit(5)->get()->map(function ($letter) {
            $letter->type = 'in';
            return $letter;
        });

        $recentOutgoing = SuratKeluar::latest()->limit(5)->get()->map(function ($letter) {
            $letter->type = 'out';
            return $letter;
        });

        $recentLetters = $recentIncoming
            ->merge($recentOutgoing)
            ->sortByDesc('updated_at')
            ->take(5);

        // ----- Data chart (filter range) -----
        $range = $request->input('range', '6m');
        $chartLabels = [];
        $incomingData = [];
        $outgoingData = [];

        switch ($range) {
            case 'weekly': // 7 hari terakhir
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $chartLabels[] = $date->isoFormat('dddd');
                    $incomingData[] = SuratMasuk::whereDate('tanggal_surat', $date->toDateString())->count();
                    $outgoingData[] = SuratKeluar::whereDate('tanggal_surat', $date->toDateString())->count();
                }
                break;

            case 'monthly': // 30 hari terakhir
                for ($i = 29; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $chartLabels[] = $date->format('d M');
                    $incomingData[] = SuratMasuk::whereDate('tanggal_surat', $date->toDateString())->count();
                    $outgoingData[] = SuratKeluar::whereDate('tanggal_surat', $date->toDateString())->count();
                }
                break;

            case '3m': // 3 bulan terakhir
                for ($i = 2; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $chartLabels[] = $month->isoFormat('MMMM');
                    $incomingData[] = SuratMasuk::whereYear('tanggal_surat', $month->year)
                        ->whereMonth('tanggal_surat', $month->month)->count();
                    $outgoingData[] = SuratKeluar::whereYear('tanggal_surat', $month->year)
                        ->whereMonth('tanggal_surat', $month->month)->count();
                }
                break;

            case '1y': // 12 bulan terakhir
                for ($i = 11; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $chartLabels[] = $month->isoFormat('MMM YYYY');
                    $incomingData[] = SuratMasuk::whereYear('tanggal_surat', $month->year)
                        ->whereMonth('tanggal_surat', $month->month)->count();
                    $outgoingData[] = SuratKeluar::whereYear('tanggal_surat', $month->year)
                        ->whereMonth('tanggal_surat', $month->month)->count();
                }
                break;

            case '6m': // default
            default:
                for ($i = 5; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $chartLabels[] = $month->isoFormat('MMMM');
                    $incomingData[] = SuratMasuk::whereYear('tanggal_surat', $month->year)
                        ->whereMonth('tanggal_surat', $month->month)->count();
                    $outgoingData[] = SuratKeluar::whereYear('tanggal_surat', $month->year)
                        ->whereMonth('tanggal_surat', $month->month)->count();
                }
                break;
        }

        $chartData = compact('chartLabels', 'incomingData', 'outgoingData');

        return view('dashboard', compact('incomingCount', 'outgoingCount', 'recentLetters', 'chartData', 'range'));
    })->name('dashboard');

    // ----- Surat Masuk -----
    Route::resource('incoming-letters', SuratMasukController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    // ----- Surat Keluar: route ekstra sebelum resource -----
    Route::get('outgoing-letters/{outgoing_letter}/pdf', [SuratKeluarController::class, 'generateSuratKeluar'])
        ->name('outgoing-letters.pdf');
    Route::get('outgoing-letters/backfill', [SuratKeluarController::class, 'backfillNomorSurat'])
        ->name('outgoing-letters.backfill');
    Route::get('outgoing-letters/template-preview', [SuratKeluarController::class, 'previewTemplate'])
        ->name('outgoing-letters.template-preview');

    // Resource lengkap (index/create/store/show/edit/update/destroy)
    Route::resource('outgoing-letters', SuratKeluarController::class);

    // ----- Kode Pihak (NomorSurat) -----
    Route::resource('letter_code', NomorSuratController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // ----- Perihal & Penandatangan -----
    Route::resource('perihal_surat', PerihalSuratController::class)
        ->only(['index','store','update','destroy']);
    Route::resource('penandatangan', PenandatanganController::class)
        ->only(['index','store','update','destroy']);

    // ----- Manajemen User -----
    Route::resource('user_manajemen', UserManajemenController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['user_manajemen' => 'user']);

    // ----- Pencarian & Profil -----
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ----- Logout -----
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


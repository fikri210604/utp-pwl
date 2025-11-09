@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-4">Dashboard</h1>

    <!-- Welcome Section -->
    <div class="card shadow-sm mb-4 border-0" style="background-color: #e0e7ff;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="fw-bold text-primary">Selamat Datang Kembali, {{ auth()->user()->name }}!</h4>
                    <p class="text-muted mb-0">Selamat datang di sistem manajemen surat. Anda dapat mengelola surat masuk dan keluar melalui menu di samping.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Card Surat Masuk -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('incoming-letters.index') }}" class="text-decoration-none">
                <div class="card border-start-primary shadow-sm h-100 py-2 card-hover">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Surat Masuk</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $incomingCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-inbox-fill fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Surat Keluar -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('outgoing-letters.index') }}" class="text-decoration-none">
                <div class="card border-start-success shadow-sm h-100 py-2 card-hover">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">Surat Keluar</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $outgoingCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-send-fill fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Kolom untuk Statistik Surat -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 fw-bold text-primary">Statistik Surat</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Pilih Rentang Waktu:</div>
                            <a class="dropdown-item {{ $range == 'weekly' ? 'active' : '' }}" href="{{ route('dashboard', ['range' => 'weekly']) }}">Mingguan</a>
                            <a class="dropdown-item {{ $range == 'monthly' ? 'active' : '' }}" href="{{ route('dashboard', ['range' => 'monthly']) }}">Bulanan</a>
                            <a class="dropdown-item {{ $range == '3m' ? 'active' : '' }}" href="{{ route('dashboard', ['range' => '3m']) }}">3 Bulan</a>
                            <a class="dropdown-item {{ $range == '6m' ? 'active' : '' }}" href="{{ route('dashboard', ['range' => '6m']) }}">6 Bulan</a>
                            <a class="dropdown-item {{ $range == '1y' ? 'active' : '' }}" href="{{ route('dashboard', ['range' => '1y']) }}">1 Tahun</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="letterStatsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Kolom untuk Daftar Surat Terbaru -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Aktivitas Surat Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse ($recentLetters as $letter)
                            <a href="{{ $letter->type == 'in' 
                                ? route('incoming-letters.show', $letter) 
                                : route('outgoing-letters.show', $letter) }}" 
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <span class="badge bg-{{ $letter->type == 'in' ? 'primary' : 'success' }} me-2">{{ $letter->type == 'in' ? 'Masuk' : 'Keluar' }}</span>
                                    <strong>{{ \Illuminate\Support\Str::limit($letter->perihal, 30) }}</strong>
                                </div>
                                <small class="text-nowrap">{{ \Carbon\Carbon::parse($letter->tanggal_surat)->diffForHumans() }}</small>
                            </a>
                        @empty
                            <div class="list-group-item text-center">Belum ada aktivitas surat.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
<style>
    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.2s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('letterStatsChart');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.chartLabels,
                datasets: [{
                    label: 'Surat Masuk',
                    data: chartData.incomingData,
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.3
                }, {
                    label: 'Surat Keluar',
                    data: chartData.outgoingData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endpush

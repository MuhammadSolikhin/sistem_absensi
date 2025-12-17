@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->name }}! 🎉</h5>
                            <p class="mb-4">
                                Sistem Absensi Wajah Masjid Pajak Asri siap digunakan. 
                                <br>Cek laporan hari ini di bawah.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140"
                                alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="col-12 mb-4">
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="col-12 mb-4">
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-user"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Total Jamaah</span>
                            <h3 class="card-title mb-2">{{ $totalJamaah }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-circle"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Hadir Hari Ini</span>
                            <h3 class="card-title text-nowrap mb-1">{{ $hadirHariIni }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                    <div class="card-title">
                                        <h5 class="text-nowrap mb-2">Persentase Kehadiran</h5>
                                        <span class="badge bg-label-warning rounded-pill">Hari Ini</span>
                                    </div>
                                    <div class="mt-sm-auto">
                                        <h3 class="mb-0">{{ $persentaseHadir }}%</h3>
                                    </div>
                                </div>
                                <div id="profileReportChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <h5 class="card-title mb-0">Statistik Kehadiran (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" style="min-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 order-2 mb-4">
            
            @if(auth()->user()->role == 'admin')
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">System AI Control</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Klik tombol di bawah jika Anda baru saja menambahkan data jamaah atau foto baru.</p>
                    <form action="{{ route('system.train') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Proses training mungkin memakan waktu beberapa menit. Lanjutkan?')">
                            <span class="tf-icons bx bx-scan me-1"></span> Train Face Model
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Aktivitas Terakhir</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @forelse($recentActivities as $log)
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-user"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">{{ $log->jamaah->nama_lengkap }}</h6>
                                    <small class="text-muted">{{ $log->waktu_hadir->format('d M Y, H:i') }}</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $log->confidence_score }}%</small>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="text-center text-muted">Belum ada data absensi hari ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil data dari Controller Laravel (dikirim via json_encode)
            const labels = {!! json_encode($chartLabels) !!};
            const data = {!! json_encode($chartData) !!};

            const ctx = document.getElementById('attendanceChart').getContext('2d');
            const attendanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Jamaah Hadir',
                        data: data,
                        backgroundColor: 'rgba(105, 108, 255, 0.7)', // Warna Primary Sneat
                        borderColor: 'rgba(105, 108, 255, 1)',
                        borderWidth: 1,
                        borderRadius: 5,
                        barThickness: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Agar sumbu Y tidak menampilkan desimal (0.5 orang)
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Sembunyikan legend jika cuma 1 dataset
                        }
                    }
                }
            });
        });
    </script>
@endpush
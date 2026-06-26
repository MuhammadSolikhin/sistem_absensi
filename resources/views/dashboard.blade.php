@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">

        <!-- MAIN COLUMN (Left, 8 cols) -->
        <div class="col-lg-8 mb-4 order-0">

            <!-- 1. Welcome Card -->
            <div class="card mb-4">
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

            <!-- 2. Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 3. Statistics Row -->
            <div class="row">
                <div class="col-lg-4 col-md-4 col-6 mb-4">
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

                <div class="col-lg-4 col-md-4 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="bx bx-check-circle"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Hadir Hari Ini</span>
                            <h3 class="card-title text-nowrap mb-1">{{ $hadirHariIni }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                    <div class="card-title">
                                        <h5 class="text-nowrap mb-2">Persentase</h5>
                                        <span class="badge bg-label-warning rounded-pill">Hari Ini</span>
                                    </div>
                                    <div class="mt-sm-auto">
                                        <h3 class="mb-0">{{ $persentaseHadir }}%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Chart -->
            <div class="card">
                <div class="card-header header-elements">
                    <h5 class="card-title mb-0">Statistik Kehadiran (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" style="min-height: 300px;"></canvas>
                </div>
            </div>

            <!-- 5. Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Aksi Cepat</h5>
                            <div class="d-flex gap-2 flex-wrap">
                                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'guru')
                                    <a href="{{ route('attendance.scan') }}" class="btn btn-primary">
                                        <i class="bx bx-scan me-1"></i> Scan Absensi
                                    </a>
                                @endif

                                <a href="{{ route('jamaah.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-user me-1"></i> Data Jamaah
                                </a>

                                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-file me-1"></i> Laporan
                                </a>

                                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'guru')
                                    <a href="{{ route('rapot.index') }}" class="btn btn-outline-success">
                                        <i class="bx bx-book-bookmark me-1"></i> Rapot Caberawit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDEBAR (4 cols) -->
        <div class="col-lg-4 col-md-12 order-1">

            @if(auth()->user()->role == 'admin')
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">System AI Control</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Klik tombol di bawah jika Anda baru saja menambahkan data jamaah atau foto
                            baru.</p>
                        <form id="trainModelForm" action="{{ route('system.train') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100" id="btnTrainModel">
                                <span class="tf-icons bx bx-scan me-1"></span> Train Face Model
                            </button>
                        </form>

                        <!-- Progress Bar Container (Hidden initially) -->
                        <div id="trainProgressContainer" class="mt-3" style="display: none;">
                            <p class="text-center text-primary mb-1 small fw-bold">Memproses Training Model AI...</p>
                            <div class="progress" style="height: 12px; border-radius: 6px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-center text-muted mb-0 mt-1 small" style="font-size: 0.75rem;">Harap tunggu, proses ini mungkin memakan waktu beberapa menit.</p>
                        </div>
                        
                        <!-- Notification Message -->
                        <div id="trainNotification" class="mt-3 alert" style="display: none; padding: 10px; font-size: 0.85rem;"></div>
                    </div>
                </div>
            @endif

            <div class="card">
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
        document.addEventListener("DOMContentLoaded", function () {
            // AI Training Logic
            const trainForm = document.getElementById('trainModelForm');
            const btnTrain = document.getElementById('btnTrainModel');
            const progressContainer = document.getElementById('trainProgressContainer');
            const trainNotification = document.getElementById('trainNotification');

            if (trainForm) {
                trainForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Mencegah reload halaman
                    
                    // Sembunyikan notifikasi lama, tampilkan progress bar
                    trainNotification.style.display = 'none';
                    trainNotification.className = 'mt-3 alert';
                    progressContainer.style.display = 'block';
                    
                    // Disable tombol
                    btnTrain.disabled = true;
                    btnTrain.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Training...';

                    // Gunakan Fetch API untuk AJAX Request
                    fetch(trainForm.action, {
                        method: 'POST',
                        body: new FormData(trainForm),
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        // Sembunyikan progress bar
                        progressContainer.style.display = 'none';
                        
                        // Kembalikan tombol ke semula
                        btnTrain.disabled = false;
                        btnTrain.innerHTML = '<span class="tf-icons bx bx-scan me-1"></span> Train Face Model';
                        
                        // Tampilkan notifikasi
                        trainNotification.style.display = 'block';
                        if (res.status >= 200 && res.status < 300) {
                            trainNotification.classList.add('alert-success');
                            trainNotification.innerHTML = '<i class="bx bx-check-circle me-1"></i> ' + (res.body.message || 'Training Model berhasil diselesaikan!');
                        } else {
                            trainNotification.classList.add('alert-danger');
                            trainNotification.innerHTML = '<i class="bx bx-error-circle me-1"></i> ' + (res.body.message || 'Gagal memulai training.');
                        }
                    })
                    .catch(err => {
                        progressContainer.style.display = 'none';
                        btnTrain.disabled = false;
                        btnTrain.innerHTML = '<span class="tf-icons bx bx-scan me-1"></span> Train Face Model';
                        
                        trainNotification.style.display = 'block';
                        trainNotification.classList.add('alert-danger');
                        trainNotification.innerHTML = '<i class="bx bx-error-circle me-1"></i> Gagal terhubung ke server.';
                        console.error("Training Error: ", err);
                    });
                });
            }

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
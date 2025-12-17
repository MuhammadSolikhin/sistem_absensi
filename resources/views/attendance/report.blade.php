@extends('layouts.app')

@section('title', 'Laporan Absensi')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Laporan Kehadiran Jamaah</h5>
        <small class="text-muted">Filter data berdasarkan tanggal dan nama.</small>
    </div>

    <div class="card-body">
        <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Filter Nama Jamaah</label>
                <select name="jamaah_id" class="form-select">
                    <option value="">-- Tampilkan Semua --</option>
                    @foreach($allJamaah as $j)
                        <option value="{{ $j->id }}" {{ $jamaahId == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_lengkap }} ({{ $j->nik }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bx bx-filter-alt me-1"></i> Filter
                </button>
            </div>
        </form>

        <hr class="my-4">

        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Waktu Hadir</th>
                        <th>Foto Capture</th>
                        <th>Nama Jamaah</th>
                        <th>Akurasi (LBPH)</th>
                        <th>Lokasi</th>
                        @if(auth()->user()->role == 'admin')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $log)
                    <tr>
                        <td>
                            <span class="d-block fw-bold">{{ $log->waktu_hadir->format('H:i:s') }}</span>
                            <small class="text-muted">{{ $log->waktu_hadir->format('d M Y') }}</small>
                        </td>
                        <td>
                            @if($log->capture_image_path)
                                <a href="{{ asset('storage/' . $log->capture_image_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $log->capture_image_path) }}" 
                                         class="rounded-circle" 
                                         style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #696cff;"
                                         alt="Face">
                                </a>
                            @else
                                <span class="badge bg-label-secondary">No Image</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-medium">{{ $log->jamaah->nama_lengkap }}</span>
                                <small class="text-muted">{{ $log->jamaah->nik }}</small>
                            </div>
                        </td>
                        <td>
                            @php
                                $score = $log->confidence_score;
                                $badgeColor = 'bg-success'; 
                                $text = 'Sangat Akurat';

                                // Sesuaikan logic ini dengan hasil tuning Python Anda nanti
                                if($score > 50) { 
                                    $badgeColor = 'bg-warning'; 
                                    $text = 'Cukup';
                                }
                                if($score > 80) { 
                                    $badgeColor = 'bg-danger'; 
                                    $text = 'Meragukan';
                                }
                            @endphp
                            
                            <span class="badge {{ $badgeColor }}">
                                Score: {{ number_format($score, 1) }}
                            </span>
                            <div class="small text-muted mt-1">{{ $text }}</div>
                        </td>
                        <td>{{ $log->lokasi_kamera }}</td>
                        
                        @if(auth()->user()->role == 'admin')
                        <td>
                            <form action="{{ route('attendance.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Hapus log ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-outline-danger">
                                    <span class="tf-icons bx bx-trash"></span>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.png') }}" width="150" class="mb-3">
                            <p class="text-muted">Tidak ada data absensi pada rentang tanggal ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $attendances->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
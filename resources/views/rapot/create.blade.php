@extends('layouts.app')

@section('title', 'Buat Rapot Baru')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Buat Rapot Baru</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('rapot.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Pilih Jamaah (Caberawit)</label>
                    <select name="jamaah_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($caberawits as $caberawit)
                            <option value="{{ $caberawit->id }}">{{ $caberawit->nama_lengkap }} ({{ $caberawit->nik }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Periode</label>
                    <input type="text" name="periode" class="form-control" placeholder="Contoh: Semester 1 2025" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan Wali Kelas</label>
                    <textarea name="catatan_wali" class="form-control" rows="3"></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nilai Bacaan</label>
                        <input type="text" name="nilai[bacaan]" class="form-control" placeholder="A/B/C">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nilai Hafalan</label>
                        <input type="text" name="nilai[hafalan]" class="form-control" placeholder="A/B/C">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nilai Akhlak</label>
                        <input type="text" name="nilai[akhlak]" class="form-control" placeholder="A/B/C">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nilai Kerajinan</label>
                        <input type="text" name="nilai[kerajinan]" class="form-control" placeholder="A/B/C">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keputusan</label>
                    <select name="keputusan" class="form-select">
                        <option value="-">-</option>
                        <option value="Naik Kelas">Naik Kelas</option>
                        <option value="Tinggal Kelas">Tinggal Kelas</option>
                        <option value="Lulus">Lulus</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Rapot</button>
                    <a href="{{ route('rapot.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
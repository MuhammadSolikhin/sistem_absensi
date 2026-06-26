@extends('layouts.app')

@section('title', 'Edit Rapot')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Rapot: {{ $rapot->jamaah->nama_lengkap }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('rapot.update', $rapot->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Periode</label>
                    <input type="text" name="periode" value="{{ old('periode', $rapot->periode) }}" class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan Wali Kelas</label>
                    <textarea name="catatan_wali" class="form-control"
                        rows="3">{{ old('catatan_wali', $rapot->catatan_wali) }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nilai Bacaan</label>
                        <input type="text" name="nilai[bacaan]" value="{{ $rapot->nilai['bacaan'] ?? '' }}"
                            class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nilai Hafalan</label>
                        <input type="text" name="nilai[hafalan]" value="{{ $rapot->nilai['hafalan'] ?? '' }}"
                            class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nilai Akhlak</label>
                        <input type="text" name="nilai[akhlak]" value="{{ $rapot->nilai['akhlak'] ?? '' }}"
                            class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nilai Kerajinan</label>
                        <input type="text" name="nilai[kerajinan]" value="{{ $rapot->nilai['kerajinan'] ?? '' }}"
                            class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keputusan</label>
                    <select name="keputusan" class="form-select">
                        <option value="-" {{ $rapot->keputusan == '-' ? 'selected' : '' }}>-</option>
                        <option value="Naik Kelas" {{ $rapot->keputusan == 'Naik Kelas' ? 'selected' : '' }}>Naik Kelas
                        </option>
                        <option value="Tinggal Kelas" {{ $rapot->keputusan == 'Tinggal Kelas' ? 'selected' : '' }}>Tinggal
                            Kelas</option>
                        <option value="Lulus" {{ $rapot->keputusan == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Rapot</button>
                    <a href="{{ route('rapot.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
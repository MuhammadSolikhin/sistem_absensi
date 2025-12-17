@extends('layouts.app')

@section('title', 'Edit Jamaah & Dataset')

@section('content')
<div class="row">
    
    <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header">Edit Profil Jamaah</h5>
            <div class="card-body">
                <form action="{{ route('jamaah.update', $jamaah->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="number" class="form-control" id="nik" name="nik" value="{{ old('nik', $jamaah->nik) }}" required />
                    </div>

                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $jamaah->nama_lengkap) }}" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-select" name="jenis_kelamin">
                            <option value="L" {{ $jamaah->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $jamaah->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $jamaah->no_hp) }}" />
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2">{{ old('alamat', $jamaah->alamat) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Profil</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header bg-primary text-white mb-3">Upload Dataset Wajah</h5>
            <div class="card-body">
                <div class="alert alert-warning mb-3" role="alert">
                    <i class="bx bx-bulb me-1"></i> <strong>Tips:</strong> Upload minimal 10-20 foto wajah dengan ekspresi dan pencahayaan berbeda agar AI akurat.
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <form action="{{ route('jamaah.upload', $jamaah->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Pilih Foto (Bisa Banyak Sekaligus)</label>
                        <input class="form-control" type="file" id="formFileMultiple" name="photos[]" multiple required accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bx bx-upload me-1"></i> Upload Foto
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header">Gallery Dataset ({{ $jamaah->datasets->count() }} Foto)</h5>
            <div class="card-body">
                @if($jamaah->datasets->count() > 0)
                    <div class="row g-2">
                        @foreach($jamaah->datasets as $foto)
                            <div class="col-4 col-md-3">
                                <div class="position-relative">
                                    <a href="{{ asset('storage/' . $foto->image_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $foto->image_path) }}" class="img-thumbnail w-100" style="height: 80px; object-fit: cover;" alt="Dataset">
                                    </a>
                                    </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-images mb-2" style="font-size: 2rem;"></i>
                        <p>Belum ada foto dataset.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
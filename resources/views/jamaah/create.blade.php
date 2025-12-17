@extends('layouts.app')

@section('title', 'Tambah Jamaah')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Formulir Tambah Jamaah</h5>
                <small class="text-muted float-end">Data Diri</small>
            </div>
            <div class="card-body">
                <form action="{{ route('jamaah.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label" for="nik">NIK (Nomor Induk Kependudukan)</label>
                        <input type="number" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" placeholder="16 digit angka" value="{{ old('nik') }}" required />
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap" placeholder="Sesuai KTP" value="{{ old('nama_lengkap') }}" required />
                        @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <div class="form-check mt-2">
                            <input name="jenis_kelamin" class="form-check-input" type="radio" value="L" id="jkl" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required />
                            <label class="form-check-label" for="jkl"> Laki-laki </label>
                        </div>
                        <div class="form-check">
                            <input name="jenis_kelamin" class="form-check-input" type="radio" value="P" id="jkp" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} />
                            <label class="form-check-label" for="jkp"> Perempuan </label>
                        </div>
                        @error('jenis_kelamin') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="no_hp">No. WhatsApp / HP</label>
                        <input type="number" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" placeholder="0812..." value="{{ old('no_hp') }}" />
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="alamat">Alamat Domisili</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                        <a href="{{ route('jamaah.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
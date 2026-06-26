@extends('layouts.app')

@section('title', 'Edit Group Pengajian')

@section('content')
    <div class="row">
        <!-- Form Edit Group -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Edit Informasi Group</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pengajian.update', $pengajian->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Group</label>
                            <input type="text" name="nama_group" value="{{ old('nama_group', $pengajian->nama_group) }}"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control"
                                rows="3">{{ old('deskripsi', $pengajian->deskripsi) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Info</button>
                            <a href="{{ route('pengajian.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Management Jadwal -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kelola Jadwal</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible small" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <ul class="list-group mb-4">
                        @forelse($pengajian->schedules as $jadwal)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">{{ $jadwal->hari }}</span>
                                    <span class="text-muted ms-2">{{ substr($jadwal->jam_mulai, 0, 5) }} -
                                        {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                                </div>
                                <form action="{{ route('pengajian.destroySchedule', $jadwal->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada jadwal.</li>
                        @endforelse
                    </ul>

                    <h6 class="mb-3">Tambah Jadwal Baru</h6>
                    <form action="{{ route('pengajian.storeSchedule', $pengajian->id) }}" method="POST">
                        @csrf
                        <div class="row g-2 mb-2">
                            <div class="col-12">
                                <select name="hari" class="form-select" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Minggu">Minggu</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <input type="time" name="jam_mulai" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <input type="time" name="jam_selesai" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                            <i class="bx bx-plus"></i> Tambah Jadwal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
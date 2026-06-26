@extends('layouts.app')

@section('title', 'Manajemen Pengajian')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Group Pengajian & Jadwal</h5>
            <a href="{{ route('pengajian.create') }}" class="btn btn-primary">
                <i class="bx bx-plus-circle me-1"></i> Tambah Group
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Group</th>
                            <th>Deskripsi</th>
                            <th>Jadwal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $group)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-bold">{{ $group->nama_group }}</span></td>
                                <td>{{ $group->deskripsi ?? '-' }}</td>
                                <td>
                                    @if($group->schedules->count() > 0)
                                        <ul class="list-unstyled mb-0">
                                            @foreach($group->schedules as $jadwal)
                                                <li>
                                                    <span class="badge bg-label-info">{{ $jadwal->hari }}</span>
                                                    <small>{{ substr($jadwal->jam_mulai, 0, 5) }} -
                                                        {{ substr($jadwal->jam_selesai, 0, 5) }}</small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted small">Belum ada jadwal</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('pengajian.edit', $group->id) }}"
                                            class="btn btn-sm btn-icon btn-label-primary me-2">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <form action="{{ route('pengajian.destroy', $group->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus group ini? Semua data terkait mungkin ikut terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-label-danger">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Belum ada data group pengajian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
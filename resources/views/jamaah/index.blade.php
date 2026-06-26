@extends('layouts.app')

@section('title', 'Data Jamaah')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Jamaah Masjid</h5>
            <a href="{{ route('jamaah.create') }}" class="btn btn-primary">
                <span class="tf-icons bx bx-plus-circle me-1"></span> Tambah Jamaah
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('jamaah.index') }}" method="GET" class="mb-4">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Nama atau Tempat Lahir..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>

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
                            <th>Tempat/Tgl Lahir</th>
                            <th>Nama Lengkap</th>
                            <th>L/P</th>
                            <th>Status Dataset</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($jamaah as $item)
                            <tr>
                                <td>{{ $loop->iteration + $jamaah->firstItem() - 1 }}</td>
                                <td><span class="fw-medium">{{ $item->tempat_lahir }}, {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d M Y') : '-' }}</span></td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>
                                    @if($item->jenis_kelamin == 'L')
                                        <span class="badge bg-label-info">Pria</span>
                                    @else
                                        <span class="badge bg-label-danger">Wanita</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->datasets_count >= 10)
                                        <span class="badge bg-success"><i class="bx bx-check-double me-1"></i> Siap
                                            ({{ $item->datasets_count }} Foto)</span>
                                    @elseif($item->datasets_count > 0)
                                        <span class="badge bg-warning"><i class="bx bx-error me-1"></i> Kurang
                                            ({{ $item->datasets_count }}/10)</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bx bx-x-circle me-1"></i> Belum Ada</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('jamaah.edit', $item->id) }}"
                                            class="btn btn-sm btn-label-primary me-2">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('jamaah.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini? Semua dataset wajah akan ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-label-danger">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Data tidak ditemukan. Silakan tambah data baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                {{ $jamaah->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
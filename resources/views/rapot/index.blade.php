@extends('layouts.app')

@section('title', 'Manajemen Rapot Caberawit')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manajemen Rapot Caberawit</h5>
        <a href="{{ route('rapot.create') }}" class="btn btn-primary">
            <i class="bx bx-plus-circle me-1"></i> Buat Rapot Baru
        </a>
    </div>

    <div class="card-body">
         <form method="GET" action="{{ route('rapot.index') }}" class="mb-4">
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari Jamaah..." value="{{ request('search') }}">
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
                        <th>Tanggal/Periode</th>
                        <th>Nama Jamaah</th>
                        <th>Keputusan</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rapots as $rapot)
                    <tr>
                        <td>{{ $rapot->periode }} <br> <small class="text-muted">{{ $rapot->created_at->format('d M Y') }}</small></td>
                        <td>{{ $rapot->jamaah->nama_lengkap ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $rapot->keputusan === 'Naik Kelas' ? 'bg-label-success' : 'bg-label-warning' }}">
                                {{ $rapot->keputusan }}
                            </span>
                        </td>
                        <td>{{ $rapot->creator->name ?? 'System' }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('rapot.edit', $rapot->id) }}" class="btn btn-sm btn-icon btn-label-primary me-2">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <form action="{{ route('rapot.destroy', $rapot->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-label-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
         <div class="mt-4 d-flex justify-content-end">
            {{ $rapots->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
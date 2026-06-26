@extends('layouts.app')

@section('title', 'Tambah Group Pengajian')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Tambah Group Pengajian Baru</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pengajian.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Group</label>
                    <input type="text" name="nama_group" class="form-control"
                        placeholder="Contoh: Caberawit, Muda-mudi, Kelompok" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                </div>

                <hr class="my-4">
                <h6>Tambah Jadwal Awal (Opsional)</h6>

                <div id="schedule-container">
                    <div class="row g-2 mb-2 schedule-item">
                        <div class="col-md-3">
                            <select name="hari[]" class="form-select">
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
                        <div class="col-md-3">
                            <input type="time" name="jam_mulai[]" class="form-control" placeholder="Mulai">
                        </div>
                        <div class="col-md-3">
                            <input type="time" name="jam_selesai[]" class="form-control" placeholder="Selesai">
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-outline-secondary mb-3" onclick="addSchedule()">
                    <i class="bx bx-plus"></i> Tambah Baris Jadwal
                </button>

                <div class="d-flex gap-2 border-top pt-3">
                    <button type="submit" class="btn btn-primary">Simpan Group</button>
                    <a href="{{ route('pengajian.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addSchedule() {
            const container = document.getElementById('schedule-container');
            const item = document.querySelector('.schedule-item').cloneNode(true);

            // Reset values
            item.querySelectorAll('input, select').forEach(input => input.value = '');
            container.appendChild(item);
        }
    </script>
@endsection
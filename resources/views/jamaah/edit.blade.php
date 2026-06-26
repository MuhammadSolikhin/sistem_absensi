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
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $jamaah->tempat_lahir) }}" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $jamaah->tanggal_lahir) }}" required />
                        </div>
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
                
                <form action="{{ route('jamaah.upload', $jamaah->id) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Pilih Foto (Bisa Banyak Sekaligus)</label>
                        <input class="form-control mb-2" type="file" id="formFileMultiple" name="photos[]" multiple required accept="image/*">
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#cameraModal" id="btnOpenCamera">
                            <i class="bx bx-camera me-1"></i> Ambil dari Kamera Langsung
                        </button>
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

<!-- Camera Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cameraModalLabel">Ambil Foto Wajah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btnCloseCameraModal"></button>
      </div>
      <div class="modal-body text-center">
        <video id="webcamVideo" autoplay playsinline style="width: 100%; max-width: 400px; border-radius: 8px; background: #000;"></video>
        <canvas id="webcamCanvas" style="display: none;"></canvas>
        <div class="mt-3 text-start" id="capturedPhotosPreview">
             <!-- Preview captured photos here -->
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="btnCapturePhoto"><i class="bx bx-aperture"></i> Jepret Foto</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cameraModal = document.getElementById('cameraModal');
    const webcamVideo = document.getElementById('webcamVideo');
    const webcamCanvas = document.getElementById('webcamCanvas');
    const btnCapturePhoto = document.getElementById('btnCapturePhoto');
    const fileInput = document.getElementById('formFileMultiple');
    const capturedPhotosPreview = document.getElementById('capturedPhotosPreview');
    let stream = null;
    
    cameraModal.addEventListener('show.bs.modal', async function () {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
            webcamVideo.srcObject = stream;
        } catch (err) {
            console.error("Error accessing webcam: ", err);
            alert("Tidak dapat mengakses kamera. Pastikan memberikan izin pada browser Anda.");
        }
    });

    cameraModal.addEventListener('hide.bs.modal', function () {
        if (stream) {
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
            webcamVideo.srcObject = null;
        }
    });

    btnCapturePhoto.addEventListener('click', function() {
        if (!stream) return;
        
        const context = webcamCanvas.getContext('2d');
        webcamCanvas.width = webcamVideo.videoWidth;
        webcamCanvas.height = webcamVideo.videoHeight;
        context.drawImage(webcamVideo, 0, 0, webcamCanvas.width, webcamCanvas.height);
        
        webcamCanvas.toBlob(function(blob) {
            if(!blob) return;
            const fileName = `camera_capture_${Date.now()}.jpg`;
            const file = new File([blob], fileName, { type: "image/jpeg" });
            
            // Add file to input
            const dataTransfer = new DataTransfer();
            if (fileInput.files) {
                Array.from(fileInput.files).forEach(f => dataTransfer.items.add(f));
            }
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            
            // Remove required attribute to prevent HTML5 validation error
            fileInput.removeAttribute('required');
            
            // Show preview
            const img = document.createElement('img');
            img.src = URL.createObjectURL(blob);
            img.style.width = '60px';
            img.style.height = '60px';
            img.style.objectFit = 'cover';
            img.className = 'img-thumbnail m-1';
            capturedPhotosPreview.appendChild(img);
            
        }, 'image/jpeg', 0.9);
    });
});
</script>
@endpush
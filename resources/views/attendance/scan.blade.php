@extends('layouts.app')

@section('title', 'Scan Absensi Wajah')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2"><i class='bx bx-scan me-2'></i> Scan Absensi Wajah</h5>
                    </div>
                    <div class="card-body text-center">

                        <p class="mb-4">Pastikan wajah terlihat jelas oleh kamera dan tidak menggunakan masker.</p>

                        <!-- Camera Area -->
                        <div class="position-relative d-inline-block rounded overflow-hidden bg-dark"
                            style="width: 100%; max-width: 640px;">
                            <video id="video" autoplay playsinline
                                style="width: 100%; height: auto; transform: scaleX(-1);"></video>
                            <canvas id="canvas" class="d-none"></canvas>

                            <!-- Overlay Loading -->
                            <div id="loadingOverlay"
                                class="position-absolute top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center bg-dark bg-opacity-75"
                                style="z-index: 10;">
                                <div class="text-white">
                                    <div class="spinner-border text-primary mb-2" role="status"></div>
                                    <div class="fw-bold">Memproses Wajah...</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button id="snap" class="btn btn-primary btn-lg px-5">
                                <i class='bx bx-camera me-2'></i> ABSEN SEKARANG
                            </button>
                        </div>

                        <!-- Result Area -->
                        <div id="resultArea" class="mt-4 d-none">
                            <div class="alert alert-secondary"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSRF Token for Ajax -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const snap = document.getElementById('snap');
            const processingOverlay = document.getElementById('loadingOverlay');
            const resultArea = document.getElementById('resultArea');
            const alertBox = resultArea.querySelector('.alert');

            // Access Webcam
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
                    video.srcObject = stream;
                }).catch(function (err) {
                    console.error("Camera Error: ", err);
                    alert("Gagal mengakses kamera. Pastikan izin browser diberikan.");
                });
            }

            // Trigger Photo Take
            snap.addEventListener("click", function () {
                // Show loading
                processingOverlay.classList.remove('d-none');
                processingOverlay.classList.add('d-flex');
                resultArea.classList.add('d-none');
                snap.disabled = true;

                // Draw to canvas
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Convert to Blob
                canvas.toBlob(function (blob) {
                    const formData = new FormData();
                    formData.append('image', blob, 'capture.jpg');

                    // Send to Laravel
                    fetch("{{ route('attendance.process') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            processingOverlay.classList.add('d-none');
                            processingOverlay.classList.remove('d-flex');
                            snap.disabled = false;
                            resultArea.classList.remove('d-none');

                            alertBox.className = 'alert'; // Reset classes

                            if (data.status === 'success') {
                                alertBox.classList.add('alert-success');
                                alertBox.innerHTML = `<h4 class="alert-heading fw-bold">✅ Berhasil!</h4><p class="mb-0">${data.message}</p><small>${data.time}</small>`;

                                // Speak welcome message
                                if ('speechSynthesis' in window) {
                                    let msg = new SpeechSynthesisUtterance("Selamat Datang " + data.user_name);
                                    msg.lang = 'id-ID';
                                    
                                    // Secara eksplisit memilih Voice bahasa Indonesia
                                    let voices = window.speechSynthesis.getVoices();
                                    let idVoice = voices.find(v => v.lang === 'id-ID' || v.lang === 'id_ID' || v.name.toLowerCase().includes('indonesia'));
                                    if (idVoice) {
                                        msg.voice = idVoice;
                                    }
                                    
                                    window.speechSynthesis.speak(msg);
                                }

                            } else if (data.status === 'warning') {
                                alertBox.classList.add('alert-warning');
                                alertBox.innerHTML = `<h4 class="alert-heading fw-bold">⚠️ Perhatian</h4><p class="mb-0">${data.message}</p>`;
                            } else {
                                alertBox.classList.add('alert-danger');
                                alertBox.innerHTML = `<h4 class="alert-heading fw-bold">❌ Gagal</h4><p class="mb-0">${data.message}</p>`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            processingOverlay.classList.add('d-none');
                            processingOverlay.classList.remove('d-flex');
                            snap.disabled = false;
                            resultArea.classList.remove('d-none');
                            alertBox.classList.add('alert-danger');
                            alertBox.innerHTML = "Terjadi kesalahan sistem. Cek konsol browser.";
                        });
                }, 'image/jpeg', 0.95);
            });
        });
    </script>
@endpush
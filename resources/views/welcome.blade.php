<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Selamat Datang - Sistem Absensi Masjid Pajak Asri</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <style>
        .landing-hero {
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.8)), url('https://source.unsplash.com/1600x900/?mosque,islamic');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-landing {
            max-width: 500px;
            width: 100%;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
</head>

<body>
    <div class="landing-hero px-3">
        <div class="card card-landing text-center p-4">
            <div class="card-body">
                <div class="app-brand justify-content-center mb-4">
                    <span class="app-brand-logo demo">
                        <i class='bx bxs-face-mask text-primary' style="font-size: 3rem;"></i>
                    </span>
                </div>

                <h3 class="mb-3 text-primary fw-bold">Sistem Absensi Wajah</h3>
                <h5 class="mb-4 text-muted">Masjid Pajak Asri</h5>

                <p class="mb-5">
                    Selamat datang di sistem absensi berbasis pengenalan wajah.
                    Silakan login sebagai admin atau lakukan scan absensi.
                </p>

                <div class="d-grid gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="bx bx-home-circle me-1"></i> Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="bx bx-log-in-circle me-1"></i> Login Admin
                        </a>
                    @endauth

                    <a href="{{ route('attendance.scan') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bx bx-scan me-1"></i> Scan Absensi
                    </a>
                </div>

                <div class="mt-5 text-muted small">
                    &copy; {{ date('Y') }} Masjid Pajak Asri. All rights reserved.
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
</body>

</html>
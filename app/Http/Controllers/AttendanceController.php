<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Jamaah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Menampilkan Laporan Absensi dengan Filter
     * Route: GET /laporan (laporan.index)
     */
    public function report(Request $request)
    {
        // 1. Setup Default Filter (Hari Ini)
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $jamaahId = $request->input('jamaah_id');

        // 2. Query Builder
        $query = Attendance::with('jamaah') // Eager load relasi jamaah biar cepat
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate);

        // Jika ada filter per orang
        if ($jamaahId) {
            $query->where('jamaah_id', $jamaahId);
        }

        // Urutkan dari yang paling baru masuk
        $attendances = $query->orderBy('waktu_hadir', 'desc')
            ->paginate(20)
            ->withQueryString(); // Agar pagination tetap membawa parameter filter

        // 3. Ambil data Jamaah untuk Dropdown Filter
        $allJamaah = Jamaah::orderBy('nama_lengkap')->get();

        return view('attendance.report', compact('attendances', 'startDate', 'endDate', 'allJamaah', 'jamaahId'));
    }

    /**
     * Halaman Scan Wajah
     */
    public function scanPage()
    {
        return view('attendance.scan');
    }

    /**
     * Proses Scan Wajah (Ajax)
     * Menerima Upload Foto -> Kirim ke Python -> Simpan DB
     */
    public function processScan(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        $image = $request->file('image');

        // Send to Python API
        try {
            // Default URL or from .env
            $pythonUrl = env('PYTHON_API_URL', 'http://localhost:5000');

            // Post multipart to Flask
            $response = \Illuminate\Support\Facades\Http::attach(
                'image',
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalName()
            )->timeout(5)->post("{$pythonUrl}/predict");

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['success']) && $result['success'] && $result['match']) {
                    $jamaahId = $result['dataset_id'];
                    $confidence = $result['confidence'];

                    // Cek User
                    $jamaah = Jamaah::find($jamaahId);
                    if (!$jamaah) {
                        return response()->json(['status' => 'error', 'message' => 'Wajah terdeteksi (ID:' . $jamaahId . ') tapi data user tidak ditemukan.']);
                    }

                    // --- LOGIKA BARU: Cek Slot Waktu (18:30 vs 20:00) ---
                    $now = Carbon::now();
                    $today = Carbon::today();

                    // Tentukan Slot Waktu berdasarkan jam sekarang
                    // Slot 1: 17:00 - 19:45 (Covering 18:30 session)
                    // Slot 2: 19:46 - 23:59 (Covering 20:00 session)
                    $isSlot2 = $now->hour >= 20 || ($now->hour == 19 && $now->minute > 45);

                    // Cek Duplikat di Slot yang sama
                    $duplicateQuery = Attendance::where('jamaah_id', $jamaahId)
                        ->whereDate('tanggal', $today);

                    if ($isSlot2) {
                        // Jika ini slot 2, cek apakah sudah absen diatas jam 19:45
                        $duplicateQuery->whereTime('waktu_hadir', '>', '19:45:00');
                    } else {
                        // Jika ini slot 1, cek apakah sudah absen dibawah jam 19:45
                        $duplicateQuery->whereTime('waktu_hadir', '<=', '19:45:00');
                    }

                    $alreadyPresent = $duplicateQuery->exists();

                    if ($alreadyPresent) {
                        return response()->json([
                            'status' => 'warning',
                            'message' => "Halo {$jamaah->nama_lengkap}, Anda sudah absen untuk sesi ini."
                        ]);
                    }

                    // Tentukan Group ID (Idealnya dari Schedule, tapi fallback ke default group user)
                    // Kita ambil group_id dari user sebagai default
                    $groupId = $jamaah->pengajian_group_id;

                    // Simpan Foto Bukti
                    $path = $image->store('captures/' . date('Y-m-d'), 'public');

                    // Simpan Absensi
                    Attendance::create([
                        'jamaah_id' => $jamaahId,
                        'waktu_hadir' => $now,
                        'tanggal' => $today,
                        'capture_image_path' => $path,
                        'confidence_score' => $confidence,
                        'lokasi_kamera' => 'Webcam Front',
                        'status_kehadiran' => 'Hadir',
                        'pengajian_group_id' => $groupId
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => "Selamat Datang, {$jamaah->nama_lengkap}!",
                        'user_name' => $jamaah->nama_lengkap,
                        'time' => Carbon::now()->format('H:i')
                    ]);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Wajah tidak dikenali. Silakan coba lagi.']);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Gagal menghubungi AI Service: ' . $response->status()]);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus Log Absensi (Jika ada kesalahan sistem/glitch)
     */
    public function destroy($id)
    {
        // Hanya admin yang boleh hapus (Middleware role:admin sudah membatasi di web.php? Cek route group Anda)
        // Jika belum di group admin, kita cek manual disini:
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $attendance = Attendance::findOrFail($id);

        // Hapus file foto capture jika ada (untuk hemat storage)
        if ($attendance->capture_image_path && file_exists(storage_path('app/public/' . $attendance->capture_image_path))) {
            unlink(storage_path('app/public/' . $attendance->capture_image_path));
        }

        $attendance->delete();

        return back()->with('success', 'Data absensi berhasil dihapus.');
    }
}
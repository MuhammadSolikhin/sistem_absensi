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
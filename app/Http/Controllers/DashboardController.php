<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jamaah;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // Untuk nembak API Python
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Utama
     */
    public function index()
    {
        // 1. Statistik Utama (Cards)
        $totalJamaah = Jamaah::where('status_aktif', true)->count();
        
        $hadirHariIni = Attendance::whereDate('tanggal', Carbon::today())->count();
        
        // Menghitung persentase kehadiran hari ini (Opsional)
        $persentaseHadir = $totalJamaah > 0 ? round(($hadirHariIni / $totalJamaah) * 100, 1) : 0;

        // 2. Data Grafik Kehadiran (7 Hari Terakhir)
        // Kita butuh array Label (Tanggal) dan Data (Jumlah) untuk Chart.js
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $formattedDate = $date->format('d M'); // Contoh: "06 Dec"
            
            // Hitung jumlah pada tanggal tersebut
            $count = Attendance::whereDate('tanggal', $date)->count();

            $chartLabels[] = $formattedDate;
            $chartData[] = $count;
        }

        // 3. Log Aktivitas Terakhir (5 Orang terakhir yang scan wajah)
        $recentActivities = Attendance::with('jamaah')
                            ->orderBy('waktu_hadir', 'desc')
                            ->take(5)
                            ->get();

        // 4. Kirim semua data ke View
        return view('dashboard', compact(
            'totalJamaah',
            'hadirHariIni',
            'persentaseHadir',
            'chartLabels',
            'chartData',
            'recentActivities'
        ));
    }

    /**
     * Trigger API Python untuk Training Model Wajah
     * Diakses via POST oleh Admin
     */
    public function triggerTraining()
    {
        // Ambil konfigurasi dari .env
        $pythonUrl = env('PYTHON_API_URL', 'http://localhost:5000'); 
        $apiKey = env('PYTHON_API_KEY');

        try {
            // Tembak API Python endpoint '/train'
            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
                'Content-Type' => 'application/json'
            ])->post("{$pythonUrl}/train");

            if ($response->successful()) {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json(['status' => 'success', 'message' => 'Training Model berhasil diselesaikan! AI kini sudah mengenali wajah terbaru.']);
                }
                return back()->with('success', 'Perintah Training berhasil dikirim ke sistem AI. Mohon tunggu beberapa saat.');
            } else {
                // Jika Python error (misal 500 atau 401)
                Log::error('Training Error: ' . $response->body());
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'Gagal memulai training. Respon sistem AI: ' . $response->status()], 500);
                }
                return back()->with('error', 'Gagal memulai training. Respon sistem AI: ' . $response->status());
            }

        } catch (\Exception $e) {
            // Jika koneksi putus (Python mati)
            Log::error('Connection Error: ' . $e->getMessage());
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Tidak dapat terhubung ke Service Python. Pastikan script Python berjalan.'], 500);
            }
            return back()->with('error', 'Tidak dapat terhubung ke Service Python. Pastikan script Python berjalan.');
        }
    }
}
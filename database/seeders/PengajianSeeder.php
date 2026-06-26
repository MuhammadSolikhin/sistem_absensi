<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PengajianGroup;
use App\Models\PengajianSchedule;

class PengajianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Caberawit
        $caberawit = PengajianGroup::create([
            'nama_group' => 'Caberawit', // Anak kecil 3th - sebelum SMP
            'deskripsi' => 'Pengajian untuk anak usia dini (3 tahun) sampai pra-remaja.',
            'has_rapot' => true,
        ]);

        // Jadwal Caberawit: Senin - Jumat -> 18.30 - 19.30
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        foreach ($days as $day) {
            PengajianSchedule::create([
                'pengajian_group_id' => $caberawit->id,
                'hari' => $day,
                'jam_mulai' => '18:30:00',
                'jam_selesai' => '19:30:00',
            ]);
        }

        // 2. Kelompok (Muda-mudi & Umum/Campur biasaya, tapi prompt bilang "campur")
        $kelompok = PengajianGroup::create([
            'nama_group' => 'Kelompok',
            'deskripsi' => 'Pengajian umum/kelompok.',
            'has_rapot' => false,
        ]);

        // Jadwal Kelompok: Senin, Kamis, Jumat malam jam 08.00 (20.00)
        $kelompokDays = ['Senin', 'Kamis', 'Jumat'];
        foreach ($kelompokDays as $day) {
            PengajianSchedule::create([
                'pengajian_group_id' => $kelompok->id,
                'hari' => $day,
                'jam_mulai' => '20:00:00',
                'jam_selesai' => '21:00:00', // Asumsi 1 jam? Atau sampai selesai. Required just start time "jam 08.00"
            ]);
        }

        // 3. Muda-mudi
        $mudaMudi = PengajianGroup::create([
            'nama_group' => 'Muda-mudi',
            'deskripsi' => 'Pengajian khusus usia remaja/muda-mudi.',
            'has_rapot' => false,
        ]);

        // Jadwal Muda-mudi: Sabtu malam (Malam Minggu). Usually means Sabtu 20:00 or similar? 
        // Prompt says "sabtu malam". Let's assume 18:30 or 20:00?
        // Let's assume 18:30 start as general rule or 20:00. 
        // Note below says "untuk absensi: 2x jam 18.30 & 20.00 senin - minggu"
        // So potentially Muda-mudi is 18:30 or 20:00. Let's put 18:30 or 20:00.
        // Usually Muda-mudi is separate. Let's start with 20:00 Saturday.
        PengajianSchedule::create([
            'pengajian_group_id' => $mudaMudi->id,
            'hari' => 'Sabtu',
            'jam_mulai' => '18:30:00', // Or 20:00. Prompt didn't specify hour, just "sabtu malam".
            // If check absensi note: "2x jam 18.30 & 20.00".
            // So on Saturday, maybe it's one of these. Let's pick 18:30 for now or maybe both?
            // "Muda-mudi" is often long. Let's set 18:30.
            'jam_selesai' => '21:00:00',
        ]);


        // 4. Pengajian Sebulan Sekali (4S/Ibu2)
        // This is irregular. Schedule entry might not be daily.
        $bulanan = PengajianGroup::create([
            'nama_group' => 'Bulanan (4S/Ibu-ibu)',
            'deskripsi' => 'Pengajian sebulan sekali.',
            'has_rapot' => false,
        ]);

        // No fixed weekly schedule for this, or allow manual event creation?
        // Prompt says "ngaji sebulan sekali (dibikin kelas: 4S/ibu2)".
        // We might not add a Schedule entry if checks are strict, or add a dummy one.
        // Let's leave schedule empty for this one, logic should handle empty schedule (manual attendance only?).
    }
}

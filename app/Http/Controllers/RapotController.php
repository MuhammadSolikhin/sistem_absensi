<?php

namespace App\Http\Controllers;

use App\Models\Rapot;
use App\Models\Jamaah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RapotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Hanya tampilkan jamaah yg punya Rapot (Caberawit) atau yang sudah punya data rapot
        // Tapi requirement bilang: "khusus caberawit ada rapot".
        // Jadi kita list semua Rapot yang sudah ada, plus tombol "Buat Rapot Baru"

        $rapots = Rapot::with('jamaah')
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('jamaah', function ($jq) use ($request) {
                    $jq->where('nama_lengkap', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('rapot.index', compact('rapots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cari jamaah yang grupnya 'Caberawit' (ID 1 di seeder, atau cari by name)
        // Better: Filter by HasRapot group logic.

        $caberawits = Jamaah::whereHas('group', function ($q) {
            $q->where('has_rapot', true);
        })->get();

        return view('rapot.create', compact('caberawits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jamaah_id' => 'required|exists:jamaah,id',
            'periode' => 'required|string',
            'catatan_wali' => 'nullable|string',
            'nilai' => 'nullable|array', // Expecting JSON array/object
            'keputusan' => 'required|in:Naik Kelas,Tinggal Kelas,Lulus,-',
        ]);

        Rapot::create([
            'jamaah_id' => $request->jamaah_id,
            'periode' => $request->periode,
            'catatan_wali' => $request->catatan_wali,
            'nilai' => $request->nilai, // Laravel automatically casts to JSON if model casts set
            'keputusan' => $request->keputusan,
            'created_by_user_id' => Auth::id(),
        ]);

        return redirect()->route('rapot.index')->with('success', 'Rapot berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rapot $rapot)
    {
        return view('rapot.show', compact('rapot'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rapot $rapot)
    {
        return view('rapot.edit', compact('rapot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rapot $rapot)
    {
        $request->validate([
            'periode' => 'required|string',
            'catatan_wali' => 'nullable|string',
            'nilai' => 'nullable|array',
            'keputusan' => 'required|in:Naik Kelas,Tinggal Kelas,Lulus,-',
        ]);

        $rapot->update([
            'periode' => $request->periode,
            'catatan_wali' => $request->catatan_wali,
            'nilai' => $request->nilai,
            'keputusan' => $request->keputusan,
        ]);

        return redirect()->route('rapot.index')->with('success', 'Rapot berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rapot $rapot)
    {
        $rapot->delete();
        return redirect()->route('rapot.index')->with('success', 'Rapot berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PengajianGroup;
use App\Models\PengajianSchedule;
use Illuminate\Http\Request;

class PengajianController extends Controller
{
    public function index()
    {
        $groups = PengajianGroup::with('schedules')->get();
        return view('pengajian.index', compact('groups'));
    }

    public function create()
    {
        return view('pengajian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_group' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'hari' => 'nullable|array',
            'jam_mulai' => 'nullable|array',
            'jam_selesai' => 'nullable|array',
        ]);

        $group = PengajianGroup::create([
            'nama_group' => $request->nama_group,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($request->has('hari')) {
            foreach ($request->hari as $key => $hari) {
                if ($hari && isset($request->jam_mulai[$key]) && isset($request->jam_selesai[$key])) {
                    $group->schedules()->create([
                        'hari' => $hari,
                        'jam_mulai' => $request->jam_mulai[$key],
                        'jam_selesai' => $request->jam_selesai[$key],
                    ]);
                }
            }
        }

        return redirect()->route('pengajian.index')->with('success', 'Group Pengajian berhasil ditambahkan.');
    }

    public function edit(PengajianGroup $pengajian)
    {
        return view('pengajian.edit', compact('pengajian'));
    }

    public function update(Request $request, PengajianGroup $pengajian)
    {
        $request->validate([
            'nama_group' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'hari' => 'nullable|array',
            'jam_mulai' => 'nullable|array',
            'jam_selesai' => 'nullable|array',
        ]);

        $pengajian->update([
            'nama_group' => $request->nama_group,
            'deskripsi' => $request->deskripsi,
        ]);

        // Sync Schedules: Delete old and create new for simplicity (or update smartly)
        // For simplicity, we define that editing schedules here replaces old ones if re-entered, 
        // but to prevent data loss, let's keep it simple: Delete all and re-create if input provided.
        // Or better: Just create new ones if provided. 
        // A better approach for a simple CRUD:
        if ($request->has('new_schedules')) { // We'll implement a logic to manage schedules
            $pengajian->schedules()->delete(); // Reset schedules (careful!)
            foreach ($request->hari as $key => $hari) {
                if ($hari && isset($request->jam_mulai[$key]) && isset($request->jam_selesai[$key])) {
                    $pengajian->schedules()->create([
                        'hari' => $hari,
                        'jam_mulai' => $request->jam_mulai[$key],
                        'jam_selesai' => $request->jam_selesai[$key],
                    ]);
                }
            }
        }

        return redirect()->route('pengajian.index')->with('success', 'Group Pengajian berhasil diperbarui.');
    }

    public function destroy(PengajianGroup $pengajian)
    {
        $pengajian->delete();
        return redirect()->route('pengajian.index')->with('success', 'Group Pengajian berhasil dihapus.');
    }

    public function destroySchedule($id)
    {
        PengajianSchedule::destroy($id);
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function storeSchedule(Request $request, $groupId)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        PengajianSchedule::create([
            'pengajian_group_id' => $groupId,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }
}

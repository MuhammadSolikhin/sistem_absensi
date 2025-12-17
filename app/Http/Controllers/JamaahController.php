<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\FaceDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class JamaahController extends Controller
{
    /**
     * Menampilkan daftar jamaah (Pagination)
     */
    public function index(Request $request)
    {
        // Fitur pencarian sederhana
        $query = Jamaah::query();

        if ($request->has('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                ->orWhere('nik', 'like', '%' . $request->search . '%');
        }

        // Ambil data terbaru, 10 per halaman
        // withCount('datasets') agar kita tahu siapa yg belum punya foto wajah
        $jamaah = $query->withCount('datasets')
            ->latest()
            ->paginate(10);

        return view('jamaah.index', compact('jamaah'));
    }

    /**
     * Menampilkan Form Tambah
     */
    public function create()
    {
        return view('jamaah.create');
    }

    /**
     * Simpan Data Jamaah Baru ke Database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:jamaah,nik|numeric|digits:16',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|numeric',
            'alamat' => 'nullable|string',
        ]);

        Jamaah::create($request->all());

        return redirect()->route('jamaah.index')
            ->with('success', 'Data Jamaah berhasil ditambahkan. Silakan upload foto wajah.');
    }

    /**
     * Menampilkan Form Edit & List Foto Wajah
     */
    public function edit(Jamaah $jamaah)
    {
        // Load data foto yang sudah diupload
        $jamaah->load('datasets');
        return view('jamaah.edit', compact('jamaah'));
    }

    /**
     * Update Data Jamaah
     */
    public function update(Request $request, Jamaah $jamaah)
    {
        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:jamaah,nik,' . $jamaah->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|numeric',
            'alamat' => 'nullable|string',
        ]);

        $jamaah->update($request->all());

        return redirect()->route('jamaah.index')
            ->with('success', 'Data Jamaah berhasil diperbarui.');
    }

    /**
     * Hapus Data Jamaah & Foto Fisik
     */
    public function destroy(Jamaah $jamaah)
    {
        // 1. Hapus Folder Foto Jamaah ini di Storage
        // Path: storage/app/public/datasets/{id}
        if (Storage::disk('public')->exists('datasets/' . $jamaah->id)) {
            Storage::disk('public')->deleteDirectory('datasets/' . $jamaah->id);
        }

        // 2. Hapus Data di Database (Cascade akan menghapus datasets & attendances juga)
        $jamaah->delete();

        return redirect()->route('jamaah.index')
            ->with('success', 'Data Jamaah dan Dataset Wajah berhasil dihapus.');
    }

    /**
     * [KHUSUS SKRIPSI] Logic Upload Foto untuk Training LBPH
     * Route: POST jamaah/{id}/upload-foto
     */
    public function uploadDataset(Request $request, $id)
    {
        $request->validate([
            'photos' => 'required',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048' // Max 2MB per foto
        ]);

        $jamaah = Jamaah::findOrFail($id);

        // Buat folder khusus per user: storage/app/public/datasets/101/
        $destinationPath = "datasets/{$jamaah->id}";

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                // Generate nama file unik: 101_timestamp_random.jpg
                $filename = $jamaah->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Simpan fisik file
                $path = $file->storeAs($destinationPath, $filename, 'public');

                // Simpan path ke database
                FaceDataset::create([
                    'jamaah_id' => $jamaah->id,
                    'image_path' => $path // datasets/1/foto.jpg
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Foto berhasil diupload! Jangan lupa lakukan "Train Model" di Dashboard.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\TahunAjaran;

class KelasController extends Controller
{
    public function index()
    {

        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();
        $prodi = auth()->user()->prodi;
        $query = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id);
        if (auth()->user()->level == 'ap') {
                $query->where(function ($query) use ($prodi) {
                    $query->where('prodi', $prodi)
                        ->orWhere('prodi', 'Umum');
                });
        }
        $kelas = $query->get();

        return view('kelas.index', compact('kelas'));
    }

    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('kelas.show', compact('kelas'));
    }

    public function create()
    {
        $matkuls = Matkul::all();
        $dosens = Dosen::all();

        return view('kelas.create', compact('matkuls', 'dosens'));
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'kd_matkul' => 'required',
        'nidn.0' => 'required|exists:dosen,nidn', // Only the first nidn field is required
        'nidn.1' => 'nullable|exists:dosen,nidn',
        'nidn.2' => 'nullable|exists:dosen,nidn',
        'nidn.3' => 'nullable|exists:dosen,nidn',
        'prodi' => 'required',
        'jmlh_mhs' => 'required|integer',
    ]);

    $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();
    Kelas::create([
        'kd_matkul' => $request->kd_matkul,
        'nidn1' => $request->nidn[0] ?? null,
        'nidn2' => $request->nidn[1] ?? null,
        'nidn3' => $request->nidn[2] ?? null,
        'nidn4' => $request->nidn[3] ?? null,
        'prodi' => $request->prodi,
        'jmlh_mhs' => $request->jmlh_mhs,
        'tahun_ajaran_id' => $tahunAjaranAktif->id
    ]);

    return redirect()->route('kelas.index')->with('success', 'Kelas berhasil disimpan.');
}

public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'kd_matkul' => 'required',
        'nidn.0' => 'required|exists:dosen,nidn', // Only the first nidn field is required
        'nidn.1' => 'nullable|exists:dosen,nidn',
        'nidn.2' => 'nullable|exists:dosen,nidn',
        'nidn.3' => 'nullable|exists:dosen,nidn',
        'prodi' => 'required',
        'jmlh_mhs' => 'required|integer',
    ]);
    $kelas = Kelas::findOrFail($id);

    $kelas->update([
        'kd_matkul' => $request->kd_matkul,
        'nidn1' => $request->nidn[0] ?? null,
        'nidn2' => $request->nidn[1] ?? null,
        'nidn3' => $request->nidn[2] ?? null,
        'nidn4' => $request->nidn[3] ?? null,
        'prodi' => $request->prodi,
        'jmlh_mhs' => $request->jmlh_mhs,
        'tahun_ajaran_id' => TahunAjaran::getActiveTahunAjaran()->id
    ]);

    return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
}


    public function getMatkuls($prodi)
    {
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();

        $matkuls = Matkul::where('prodi', $prodi)
            ->get();



        return response()->json($matkuls);
    }
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $matkuls = Matkul::all();
        $dosens = Dosen::all();

        return view('kelas.edit', compact('kelas', 'matkuls', 'dosens'));
    }

    

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}

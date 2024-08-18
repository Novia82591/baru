<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();

        $query = Ruang::where('tahun_ajaran_id', $tahunAjaranAktif->id);
        if (auth()->user()->level == 'ap') {
            $query->where('prodi', auth()->user()->prodi)
                  ->orWhere('prodi', 'Umum');
        }
        $ruang = $query->get();

        return view('Af.Ruang.ruang', compact('ruang'));
    }

    public function create()
    {
        return view('Af.Ruang.tambah-ruang');
    }

    public function store(Request $request)
    {

        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();
        $exists = Ruang::where('kd_ruang', $request->kd_ruang)
            ->exists();

        if ($exists) {
            return redirect('/ruang')->with('error', 'Data yang dinput sudah ada.');
        }
        $data = $request->except(['_token', 'submit']);
        $data['tahun_ajaran_id'] = $tahunAjaranAktif->id;
        Ruang::create($data);

        return redirect('/ruang')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $ruang = Ruang::where('kd_ruang', $id)->first();
        return view('Af.Ruang.edit-ruang', compact(['ruang']));
    }

    public function update(Request $request, $id)
    {
        $ruang = Ruang::where('kd_ruang', $id)->first();
        $ruang->update($request->except(['_token', 'submit']));
        return redirect('/ruang');
    }

    public function destroy($id)
    {
        $ruang = Ruang::where('kd_ruang', $id);
        $ruang->delete();
        return redirect('/ruang');
    }
}

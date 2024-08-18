<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();

        $mahasiswa = Mahasiswa::where('tahun_ajaran_id', $tahunAjaranAktif->id)->get();
        return view('Af.Mahasiswa.mahasiswa',compact('mahasiswa'));
    }

    public function create()
    {
        return view('Af.Mahasiswa.tambah-mahasiswa');
    }

    public function store(Request $request)
    {
       
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();
        $exists = Mahasiswa::where('prodi', $request->prodi)
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->exists();

        if ($exists) {
            return redirect('/mahasiswa')->with('error', 'Data yang dinput sudah ada.');
        }
        $data = $request->except(['_token', 'submit']);
        $data['tahun_ajaran_id'] = $tahunAjaranAktif->id;
        Mahasiswa::create($data);

        return redirect('/mahasiswa')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        return view('Af.Mahasiswa.edit-mahasiswa', compact(['mahasiswa']));
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::find($id);
        $mahasiswa->update($request->except(['_token','submit']));
        return redirect('/mahasiswa');
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        $mahasiswa->delete();
        return redirect('/mahasiswa');
    }
}

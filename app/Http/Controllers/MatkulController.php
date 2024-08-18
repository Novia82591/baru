<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class MatkulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();

        $query = Matkul::query();
        if (auth()->user()->level == 'ap') {
            $query->where('prodi', auth()->user()->prodi)
                ->orWhere('prodi', 'Umum');
        }
        $matkul = $query->get();
        return view('Af.Matkul.matkul', compact('matkul'));
    }

    public function create()
    {
        return view('Af.Matkul.tambah-matkul');
    }

    public function store(Request $request)
    {
        $exists = Matkul::where('kd_matkul', $request->kd_matkul)
            ->exists();

        if ($exists) {
            return redirect('/matkul')->with('error', 'Data yang dinput sudah ada.');
        }
        $data = $request->except(['_token', 'submit']);
        Matkul::create($data);

        return redirect('/matkul')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $matkul = Matkul::where('kd_matkul', $id)->first();
        return view('Af.Matkul.edit-matkul', compact(['matkul']));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method', 'submit']);

        Matkul::where('kd_matkul', $id)->update($data);
        return redirect('/matkul')->with('success', 'Data berhasil diubah.');
    }

    public function destroy($id)
    {
        $matkul = Matkul::where('kd_matkul', $id);
        $matkul->delete();
        return redirect('/matkul');
    }
}

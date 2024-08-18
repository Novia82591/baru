<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();

        $dosen = Dosen::get();

        return view('Af.Dosen.dosen', compact('dosen'));
    }

    public function create()
    {
        return view('Af.Dosen.tambah-dosen');
    }


    public function store(Request $request)
    {
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();
        $exists = Dosen::where('nidn', $request->nidn)
            ->exists();

        if ($exists) {
            return redirect('/dosen')->with('error', 'Data dengan kombinasi nidn yang sama sudah ada');
        }
        $data = $request->except(['_token', 'submit']);
        Dosen::create($data);

        return redirect('/dosen')->with('success', 'Data dosen berhasil disimpan.');
    }



    public function edit($id)
    {
        $dosen = Dosen::where('nidn', $id)->first();
        return view('Af.Dosen.edit-dosen', compact(['dosen']));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::where('nidn', $id)->first();
        $dosen->update($request->except(['_token', 'submit']));
        return redirect('/dosen');
    }

    public function destroy($id)
    {
        $dosen = Dosen::where('nidn', $id);
        $dosen->delete();
        return redirect('/dosen');
    }
}

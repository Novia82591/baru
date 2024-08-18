<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::all();
        return view('tahunajaran.index', compact('tahunAjarans'));
    }

    public function activate(Request $request, TahunAjaran $tahunajaran)
    {
        TahunAjaran::where('id', '!=', $tahunajaran->id)->update(['is_active' => false]);

        $tahunajaran->update(['is_active' => true]);

        return redirect()->route('tahunajaran.index')->with('success', 'Tahun ajaran berhasil diaktifkan.');
    }

    public function create()
    {
        return view('tahunajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|unique:tahunajarans',
        ]);

        TahunAjaran::create($request->all());

        return redirect()->route('tahunajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function show(TahunAjaran $tahunajaran)
    {
        return view('tahunajaran.show', compact('tahunajaran'));
    }

    public function edit($id)
    {      
        $tahunAjaran = TahunAjaran::find($id);
        return view('tahunajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunajaran)
    {
        $request->validate([
            'tahun_ajaran' => 'required|unique:tahunajarans,tahun_ajaran,' . $tahunajaran->id,
        ]);
    
        if ($request->is_active) {
            TahunAjaran::where('id', '!=', $tahunajaran->id)->update(['is_active' => false]);
        }
    
        $tahunajaran->update($request->all());
        return redirect()->route('tahunajaran.index')->with('success', 'Tahun ajaran berhasil diupdate.');
    }

    public function destroy(TahunAjaran $tahunajaran)
    {
        if ($tahunajaran->is_active) {
            return redirect()->route('tahunajaran.index')->with('error', 'Tahun ajaran yang aktif tidak dapat dihapus.');
        }
    
        $tahunajaran->delete();
    
        return redirect()->route('tahunajaran.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
    
}

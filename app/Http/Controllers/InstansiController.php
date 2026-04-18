<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instansi = Instansi::all();
        return view('admin.Instansi.indexInstansi', compact('instansi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Instansi.createInstansi');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|max:100',
            'jenis_instansi' => 'required|in:Pendidikan Tinggi,Instansi Pemerintah,BUMN,Perusahaan Swasta',
            'skala_instansi' => 'required|in:Wirausaha,Nasional,Multinasional',
            'lokasi_instansi' => 'required|max:100',
            'no_hp_instansi' => 'required|min:10|max:13',
        ]);

        Instansi::create($request->all());

        return redirect()->route('instansi.index')
            ->with('success', 'Instansi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Instansi $instansi)
    {
        return view('admin.Instansi.showInstansi', compact('instansi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $instansi = Instansi::findOrFail($id);
        return view('admin.Instansi.editInstansi', compact('instansi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_instansi' => 'required|max:100',
            'jenis_instansi' => 'required|in:Pendidikan Tinggi,Instansi Pemerintah,BUMN,Perusahaan Swasta',
            'skala_instansi' => 'required|in:Wirausaha,Nasional,Multinasional',
            'lokasi_instansi' => 'required|max:100',
            'no_hp_instansi' => 'required|min:10|max:20',
        ]);

        $instansi = Instansi::findOrFail($id);
        $instansi->update($request->all());

        return redirect()->route('instansi.index')
            ->with('success', 'Instansi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $instansi = Instansi::findOrFail($id);
        $instansi->delete();

        return redirect()->route('instansi.index')
            ->with('success', 'Instansi berhasil dihapus.');
    }
}
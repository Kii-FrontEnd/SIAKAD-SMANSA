<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mapel = Mapel::orderBy('nama_mapel', 'asc')->get();
        $guru = Guru::orderBy('nama', 'asc')->get();
        return view('pages.admin.guru.index', compact('guru', 'mapel'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'nama' => 'required',
            'nip' => 'required|unique:gurus',
            'no_telp' => 'required',
            'alamat' => 'required',
            'mapel_id' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'nip.unique' => 'NIP sudah terdaftar',
        ]);

        if(isset($request->foto)){
            $file = $request->file('foto');
            $namaFoto = time() . '.' . $file->getClientOriginalExtension();
            $foto = $file->storeAs('images/guru', $namaFoto, 'public');
        }

        $guru = new Guru;
        $guru->nama = $request->nama;
        $guru->nip = $request->nip;
        $guru->no_telp = $request->no_telp;
        $guru->alamat = $request->alamat;
        $guru->mapel_id = $request->mapel_id;
        $guru->foto = $foto;
        $guru->save();


        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $guru = Guru::findOrFail($id);

        return view('pages.admin.guru.profile', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $mapel = Mapel::all();
        $guru = Guru::findOrFail($id);

        return view('pages.admin.guru.edit', compact('guru', 'mapel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Note: added minimal code to allow NIP validation to ignore current record,
     * and safer deletion of previous photo if stored on disk 'public'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Try to decrypt id if it's encrypted (like edit/show)
        try {
            $decryptedId = Crypt::decrypt($id);
            $idToUse = $decryptedId;
        } catch (\Exception $e) {
            $idToUse = $id;
        }

        $guru = Guru::find($idToUse);

        // Validation: ignore unique constraint on current guru id
        $this->validate($request, [
            'nip' => ['required', Rule::unique('gurus', 'nip')->ignore($guru ? $guru->id : $idToUse)]
        ], [
            'nip.unique' => 'NIP sudah terdaftar',
        ]);

        $guru->nama = $request->input('nama');
        $guru->nip = $request->input('nip');
        $guru->no_telp = $request->input('no_telp');
        $guru->alamat = $request->input('alamat');
        $guru->mapel_id = $request->input('mapel_id');

        if($request->hasFile('foto'))
        {
            // Try to delete previous file from storage disk 'public' first (if path stored relatively)
            if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                Storage::disk('public')->delete($guru->foto);
            } else {
                // Fallback: if previous implementation stored filename only under public/images/guru
                $lokasi = public_path('images/guru/'.$guru->foto);
                if(File::exists($lokasi)) {
                    File::delete($lokasi);
                }
            }

            $foto = $request->file('foto');
            $namaFoto = time() . '.' . $foto->getClientOriginalExtension();

            // Keep original behavior: move to public/images/guru (existing code) and also store name
            $tujuanFoto = public_path('/images/guru');
            $foto->move($tujuanFoto, $namaFoto);

            // Also store as path relative to public (to keep compatibility). If you prefer storage disk, change to storeAs.
            $guru->foto = $namaFoto;
        }

        $guru->update();

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $guru = Guru::find($id);
        $guru->delete();

        // Hapus data user
        if($user = User::where('id', $guru->user_id)->first()){
            $user->delete();
        }

        return back()->with('success', 'Data mapel berhasil dihapus!');
    }
}

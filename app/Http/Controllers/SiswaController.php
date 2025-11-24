<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siswa = Siswa::OrderBy('nama', 'asc')->get();
        $kelas = Kelas::all();
        return view('pages.admin.siswa.index', compact('siswa', 'kelas'));
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
        // ---- TAMBAHAN: guard server-side minimal untuk duplikat dan kapasitas kelas (max 45)
        // tanpa mengubah alur penyimpanan asli. Jika kondisi terpenuhi, simpan langsung dan redirect.
        $nisInput = $request->input('nis');
        $kelasInput = $request->input('kelas_id');

        if ($kelasInput) {
            // cek apakah NIS sudah ada di kelas yang dipilih
            $existsInClass = \App\Models\Siswa::where('nis', $nisInput)->where('kelas_id', $kelasInput)->exists();

            // hitung jumlah siswa di kelas tersebut
            $countInClass = \App\Models\Siswa::where('kelas_id', $kelasInput)->count();

            // jika NIS belum ada di kelas dan kapasitas belum penuh (45), lakukan penyimpanan manual
            if (!$existsInClass && $countInClass < 45) {
                // validasi minimal untuk memastikan field wajib tersedia
                $request->validate([
                    'nama' => 'required',
                    'nis' => 'required',
                    'telp' => 'required',
                    'alamat' => 'required',
                    'kelas_id' => 'required',
                    'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
                ]);

                // simpan file foto (mengikuti pola penyimpanan existing: storage disk 'public')
                $foto = null;
                if ($request->hasFile('foto')) {
                    $file = $request->file('foto');
                    $namaFoto = time() . '.' . $file->getClientOriginalExtension();
                    $foto = $file->storeAs('images/siswa', $namaFoto, 'public');
                }

                // buat dan simpan model siswa
                $siswa = new \App\Models\Siswa;
                $siswa->nama = $request->input('nama');
                $siswa->nis = $request->input('nis');
                $siswa->telp = $request->input('telp');
                $siswa->alamat = $request->input('alamat');
                $siswa->kelas_id = $request->input('kelas_id');
                $siswa->foto = $foto;
                $siswa->save();

                return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan');
            }

            // jika kondisi tidak terpenuhi (duplicate atau sudah penuh), biarkan alur lama menangani error
        }
        // ---- AKHIR TAMBAHAN ----

        $this->validate($request, [
            'nama' => 'required',
            'nis' => 'required|unique:siswas',
            'telp' => 'required',
            'alamat' => 'required',
            'kelas_id' => 'required|unique:siswas',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nis.unique' => 'NIS sudah terdaftar',
            'kelas_id.unique' => 'Siswa sudah terdaftar di kelas ini',
        ]);

        if (isset($request->foto)) {
            $file = $request->file('foto');
            $namaFoto = time() . '.' . $file->getClientOriginalExtension();
            $foto = $file->storeAs('images/siswa', $namaFoto, 'public');
        }

        $siswa = new Siswa;
        $siswa->nama = $request->nama;
        $siswa->nis = $request->nis;
        $siswa->telp = $request->telp;
        $siswa->alamat = $request->alamat;
        $siswa->kelas_id = $request->kelas_id;
        $siswa->foto = $foto;
        $siswa->save();


        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan');
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
        $siswa = Siswa::findOrFail($id);

        return view('pages.admin.siswa.profile', compact('siswa'));
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
        $kelas = Kelas::all();
        $siswa = Siswa::findOrFail($id);

        return view('pages.admin.siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Siswa $siswa)
    {
        if ($request->nis != $siswa->nis) {
            $this->validate($request, [
                'nis' => 'unique:siswas'
            ], [
                'nis.unique' => 'NIS sudah terdaftar',
            ]);
        }

        $siswa->nama = $request->nama;
        $siswa->nis = $request->nis;
        $siswa->telp = $request->telp;
        $siswa->alamat = $request->alamat;
        $siswa->kelas_id = $request->kelas_id;

        if ($request->hasFile('foto')) {
            $lokasi = 'img/siswa/' . $siswa->foto;
            if (File::exists($lokasi)) {
                File::delete($lokasi);
            }
            $foto = $request->file('foto');
            $namaFoto = time() . '.' . $foto->getClientOriginalExtension();
            $tujuanFoto = public_path('/img/siswa');
            $foto->move($tujuanFoto, $namaFoto);
            $siswa->foto = $namaFoto;
        }

        $siswa->update();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $siswa = Siswa::find($id);
        $lokasi = 'img/siswa/' . $siswa->foto;
        if (File::exists($lokasi)) {
            File::delete($lokasi);
        }

        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }
}

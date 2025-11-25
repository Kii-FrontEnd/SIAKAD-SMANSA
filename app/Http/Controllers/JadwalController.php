<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource (admin).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwal = Jadwal::orderBy('hari', 'desc')->get();
        $mapel = Mapel::orderBy('nama_mapel', 'desc')->get();
        $kelas = Kelas::orderBy('nama_kelas', 'desc')->get();
        return view('pages.admin.jadwal.index', compact('jadwal', 'mapel', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $this->validate($request, [
            'kelas_id' => 'required',
            'mapel_id' => 'required|unique:jadwals,dari_jam',
            'hari' => 'required',
            'dari_jam' => 'required',
            'sampai_jam' => 'required',
        ], [
            'kelas_id.required' => 'Kelas wajib diisi',
            'mapel_id.required' => 'Mata Pelajaran wajib diisi',
            'mapel_id.unique' => 'Mata Pelajaran sudah ada di jam tersebut',
            'hari.required' => 'Hari wajib diisi',
            'dari_jam.required' => 'Jam mulai wajib diisi',
            'sampai_jam.required' => 'Jam selesai wajib diisi',
        ]);

        Jadwal::create([
            'kelas_id' => $data['kelas_id'],
            'mapel_id' => $data['mapel_id'],
            'hari' => $data['hari'],
            'dari_jam' => $data['dari_jam'],
            'sampai_jam' => $data['sampai_jam'],
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource (admin).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jadwal = Jadwal::find($id);
        $mapel = Mapel::orderBy('nama_mapel', 'desc')->get();
        $kelas = Kelas::orderBy('nama_kelas', 'desc')->get();

        $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

        return view('pages.admin.jadwal.edit', compact('jadwal', 'mapel', 'kelas', 'hari'));
    }

    /**
     * Update the specified resource in storage (admin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update($data);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage (admin).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);
        if ($jadwal) {
            $jadwal->delete();
        }

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus');
    }

    /**
     * Display jadwal for the logged-in siswa (siswa view).
     * Mirip dengan MateriController::siswa — ambil siswa berdasarkan Auth user,
     * ambil kelasnya, ambil jadwal untuk kelas tersebut dan kirim ke view siswa.
     *
     * @return \Illuminate\Http\Response
     */
    public function siswa()
    {
        // Ambil siswa berdasarkan nis dari user yang sedang login
        $siswa = Siswa::where('nis', Auth::user()->nis)->first();

        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // Ambil kelas siswa
        $kelas = Kelas::findOrFail($siswa->kelas_id);

        // Ambil semua jadwal untuk kelas tersebut
        $jadwal = Jadwal::where('kelas_id', $kelas->id)->get();

        // Opsional: filter jadwal hanya untuk hari ini agar otomatis menampilkan jadwal hari ini
        $now = Carbon::now();
        $hari_en = $now->format('l'); // Monday, Tuesday, ...
        $mapHari = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $hari_id = $mapHari[$hari_en] ?? $hari_en;

        // Filter jadwal untuk hari ini (cocokkan nama hari Indonesia atau Inggris)
        $jadwal = $jadwal->filter(function ($item) use ($hari_id, $hari_en) {
            if (!isset($item->hari)) return false;
            $itemHari = trim((string) $item->hari);
            return strcasecmp($itemHari, $hari_id) === 0 || strcasecmp($itemHari, $hari_en) === 0;
        })->values();

        // Hapus jadwal yang sudah selesai (opsional): jika sampai_jam tersedia dan sudah lewat, hilangkan
        $nowTime = Carbon::now();
        $jadwal = $jadwal->filter(function ($item) use ($nowTime) {
            $endTimeRaw = $item->sampai_jam ?? $item->hingga_jam ?? null;
            if (!$endTimeRaw) {
                return true;
            }
            try {
                $end = Carbon::parse($endTimeRaw);
                return $nowTime->lte($end);
            } catch (\Throwable $e) {
                return true;
            }
        })->values();

        // Kirim data ke view siswa jadwal (buat view pages.siswa.jadwal.index jika belum ada)
        return view('pages.siswa.jadwal.index', compact('jadwal', 'kelas'));
    }
}

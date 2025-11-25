@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Selamat datang {{ Auth::user()->name }}{{ isset($orangtua) && $orangtua->siswas->isNotEmpty() ? ' Orangtua dari : '.
            $orangtua->siswas->pluck('nama')->join(', ') : '' }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                @forelse($orangtua->siswas as $key => $siswa)
                    <div class="col-12 col-sm-12 col-lg-5">
                        <div class="card profile-widget">
                            
                            <div class="profile-widget-header">
                            @php
                                $defaultAvatar = asset('images/default-avatar.png');
                                $foto = $siswa->foto ?? null;
                                $avatarUrl = $defaultAvatar;

                                if ($foto) {
                                    // 1) Jika sudah berupa URL penuh
                                    if (filter_var($foto, FILTER_VALIDATE_URL)) {
                                        $avatarUrl = $foto;
                                    } else {
                                        $clean = ltrim($foto, '/');

                                        try {
                                            // 2) Cek di storage/app/public/<clean> -> akses via /storage/<clean>
                                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($clean)) {
                                                $avatarUrl = asset('storage/' . $clean);
                                            }
                                            // 3) Cek jika disimpan sebagai nama file di public/img/siswa/<name>
                                            elseif (file_exists(public_path('img/siswa/' . $clean))) {
                                                $avatarUrl = asset('img/siswa/' . $clean);
                                            }
                                            // 4) Cek basename (untuk kasus $foto = '123.jpg')
                                            elseif (file_exists(public_path('images/siswa/' . basename($clean)))) {
                                                $avatarUrl = asset('images/siswa/' . basename($clean));
                                            }
                                        } catch (\Throwable $e) {
                                            // Jika ingin debugging, aktifkan log sementara:
                                            // \Log::error('Avatar check error: '.$e->getMessage());
                                            $avatarUrl = $defaultAvatar;
                                        }
                                    }
                                }
                            @endphp

                            <img
                                alt="Foto {{ $siswa->nama ?? 'Siswa' }}"
                                src="{{ $avatarUrl }}"
                                class="rounded-circle profile-widget-picture"
                                style="object-fit:cover;border:2px solid #e9ecef;"
                                onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';"
                            />

                                <div class="profile-widget-items">
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">NIS</div>
                                        <div class="profile-widget-item-value">{{ $siswa->nis }}</div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Telp</div>
                                        <div class="profile-widget-item-value">{{ $siswa->telp }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-widget-description pb-0">
                                <div class="profile-widget-name">{{ $siswa->nama }}
                                    <div class="text-muted d-inline font-weight-normal">
                                        <div class="slash"></div> siswa {{ $siswa->kelas->nama_kelas }}
                                    </div>
                                </div>
                                <label for="alamat">Alamat</label>
                                <p>{{ $siswa->alamat }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            Tidak ada siswa yang terdaftar.
                        </div>
                    </div>
                @endforelse

            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-3">
                    <div class="card card-hero" style="margin-top: 36px">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <h4>Pengumuman</h4>
                            <div class="card-description">Pengumuman sekolah hari ini</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="card-body p-0">
                                <div class="tickets-list">
                                    @forelse ($pengumumans as $pengumuman)
                                        <div class="ticket-item">
                                            <div class="ticket-title">
                                                <h4>{{ $pengumuman->description }}</h4>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="ticket-item">
                                            <div class="ticket-title">
                                                <h4>Tidak ada pengumuman hari ini</h4>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                @php
                use Illuminate\Support\Collection;
                use Illuminate\Support\Str;

                // Pastikan $orangtua tersedia
                $orangtua = $orangtua ?? null;

                // Siapkan koleksi siswa dari orangtua agar aman digunakan di view
                $siswaCollection = collect();
                if ($orangtua && isset($orangtua->siswas)) {
                    $siswaCollection = collect($orangtua->siswas);
                }

                // Siapkan daftar nama siswa (digabung) untuk header
                $siswaNames = $siswaCollection->pluck('nama')->filter()->values();
                $siswaNamesText = $siswaNames->isNotEmpty() ? $siswaNames->join(', ') : null;

                // Ambil daftar kelas id dari siswa orangtua (untuk filter jadwal/materi/tugas)
                $kelasIds = $siswaCollection->pluck('kelas_id')->filter()->unique()->values()->all();

                // Helper untuk memastikan Collection
                $ensureCollection = function ($value) {
                    if ($value instanceof Collection) return $value;
                    if (is_null($value)) return collect();
                    if (is_array($value)) return collect($value);
                    // Jika Eloquent Builder, ambil get()
                    try {
                        if (method_exists($value, 'get')) return $value->get();
                    } catch (\Throwable $e) {}
                    return collect($value);
                };

                // Pastikan variabel jadwal, materi, tugas ada dan berupa collection.
                // Jika tidak dikirim dari controller, coba ambil berdasarkan kelas siswa orangtua.
                // Semua operasi aman (try/catch) agar tidak memunculkan error di view.

                // 1) Jadwal
                if (!isset($jadwal) || is_null($jadwal)) {
                    if (!empty($kelasIds)) {
                        try {
                            $jadwal = \App\Models\Jadwal::whereIn('kelas_id', $kelasIds)->get();
                        } catch (\Throwable $e) {
                            $jadwal = collect();
                        }
                    } else {
                        $jadwal = collect();
                    }
                } else {
                    $jadwal = $ensureCollection($jadwal);
                    if (!empty($kelasIds)) {
                        $jadwal = $jadwal->filter(function ($item) use ($kelasIds) {
                            $kelasId = $item->kelas_id ?? ($item->kelas->id ?? null);
                            return in_array($kelasId, $kelasIds);
                        })->values();
                    }
                }

                // 2) Materi
                if (!isset($materi) || is_null($materi)) {
                    if (!empty($kelasIds)) {
                        try {
                            $materi = \App\Models\Materi::whereIn('kelas_id', $kelasIds)->get();
                        } catch (\Throwable $e) {
                            $materi = collect();
                        }
                    } else {
                        $materi = collect();
                    }
                } else {
                    $materi = $ensureCollection($materi);
                    if (!empty($kelasIds)) {
                        $materi = $materi->filter(function ($item) use ($kelasIds) {
                            $kelasId = $item->kelas_id ?? ($item->kelas->id ?? null);
                            return in_array($kelasId, $kelasIds);
                        })->values();
                    }
                }

                // 3) Tugas
                if (!isset($tugas) || is_null($tugas)) {
                    if (!empty($kelasIds)) {
                        try {
                            $tugas = \App\Models\Tugas::whereIn('kelas_id', $kelasIds)->get();
                        } catch (\Throwable $e) {
                            $tugas = collect();
                        }
                    } else {
                        $tugas = collect();
                    }
                } else {
                    $tugas = $ensureCollection($tugas);
                    if (!empty($kelasIds)) {
                        $tugas = $tugas->filter(function ($item) use ($kelasIds) {
                            $kelasId = $item->kelas_id ?? ($item->kelas->id ?? null);
                            return in_array($kelasId, $kelasIds);
                        })->values();
                    }
                }

                // Siapkan variabel hari (mis. 'Senin', 'Selasa', dsb.) jika belum ada
                $hari = $hari ?? null;

                // Filter jadwal untuk hari ini jika $hari tersedia
                if ($hari) {
                    $jadwalHariIni = $jadwal->filter(function ($item) use ($hari) {
                        return isset($item->hari) && $item->hari == $hari;
                    })->values();
                } else {
                    $jadwalHariIni = collect();
                }
            @endphp

                {{-- jadwal Mapel --}}
                <div class="col-12 col-sm-12 col-lg-3">
                    <div class="card card-hero" style="margin-top: 36px">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <h4>Jadwal Mapel</h4>
                            <div class="card-description">Jadwal Mapel hari ini</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="card-body p-0">
                                <div class="tickets-list">
                                    @if($jadwal->isNotEmpty())
                                        @foreach ($jadwal as $data )
                                            <div class="ticket-item">
                                                <div class="ticket-title">
                                                    <h4>{{ $data->mapel->nama_mapel ?? $data->guru->mapel->nama_mapel ?? '—' }}</h4>
                                                </div>
                                                <div class="ticket-info">
                                                    <div class="text-primary">
                                                        Pada jam {{ $data->dari_jam ?? '—' }} 
                                                        sampai {{ $data->sampai_jam ?? $data->hingga_jam ?? '—' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="ticket-item">
                                            <div class="ticket-title">
                                                <h4>Tidak ada jadwal hari ini</h4>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Materi 
                <div class="col-12 col-sm-12 col-lg-3">
                    <div class="card card-hero" style="margin-top: 36px">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h4>{{ $materi->count() }}</h4>
                            <div class="card-description">Materi Tersedia</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="tickets-list">
                                @if($materi->isNotEmpty())
                                    @foreach ($materi as $data)
                                        <div class="ticket-item">
                                            <div class="ticket-title">
                                                <h4>{{ $data->judul ?? 'Judul tidak tersedia' }}</h4>
                                            </div>
                                            <div class="ticket-info">
                                                <div>{{ $data->guru->nama ?? 'Guru tidak tersedia' }}</div>
                                                <div class="bullet"></div>
                                                <div class="text-primary">{{ $data->guru->mapel->nama_mapel ?? 'Mapel tidak tersedia' }}</div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <a href="{{ route('siswa.materi') }}" class="ticket-item ticket-more">
                                        Lihat Semua <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <div class="ticket-item">
                                        <div class="ticket-title">
                                            <h4>Tidak ada materi tersedia</h4>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- Tugas --}}
                <div class="col-12 col-sm-12 col-lg-3">
                    <div class="card card-hero" style="margin-top: 36px">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h4>{{ $tugas->count() }}</h4>
                            <div class="card-description">Tugas Tersedia</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="tickets-list">
                                @if($tugas->isNotEmpty())
                                    @foreach ($tugas as $data)
                                        <div class="ticket-item">
                                            <div class="ticket-title">
                                                <h4>{{ $data->judul ?? 'Judul tidak tersedia' }}</h4>
                                            </div>
                                            <div class="ticket-info">
                                                <div>{{ $data->guru->nama ?? 'Guru tidak tersedia' }}</div>
                                                <div class="bullet"></div>
                                                <div class="text-primary">{{ $data->guru->mapel->nama_mapel ?? 'Mapel tidak tersedia' }}</div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <a href="{{ route('orangtua.tugas.siswa') }}" class="ticket-item ticket-more">
                                        Lihat Semua <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <div class="ticket-item">
                                        <div class="ticket-title">
                                            <h4>Tidak ada tugas</h4>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.main')

@section('title', 'Profile Guru')

@section('content')
    <div class="section">
        <div class="section-body">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-sm-12 col-lg-5">
                    <div class="card profile-widget">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div></div> <!-- spacer agar tombol berada di kanan -->
                            <a href="{{ route('guru.index') }}" class="btn btn-primary btn-sm">Kembali</a>
                        </div>

                        <div class="profile-widget-header">
                            @php
                                $defaultAvatar = asset('images/default-avatar.png');
                                $foto = $guru->foto ?? null;
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
                                            // 3) Cek jika disimpan sebagai nama file di public/images/guru/<name>
                                            elseif (file_exists(public_path('images/guru/' . $clean))) {
                                                $avatarUrl = asset('images/guru/' . $clean);
                                            }
                                            // 4) Cek basename (untuk kasus $foto = '123.jpg')
                                            elseif (file_exists(public_path('images/guru/' . basename($clean)))) {
                                                $avatarUrl = asset('images/guru/' . basename($clean));
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
                                alt="Foto {{ $guru->nama ?? 'Guru' }}"
                                src="{{ $avatarUrl }}"
                                class="rounded-circle profile-widget-picture"
                                style="width:120px;height:120px;object-fit:cover;border:2px solid #e9ecef;"
                                onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';"
                            />

                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">NIP</div>
                                    <div class="profile-widget-item-value">{{ $guru->nip }}</div>
                                </div>
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Telp</div>
                                    <div class="profile-widget-item-value">{{ $guru->no_telp }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-widget-description pb-0">
                            <div class="profile-widget-name">{{ $guru->nama }}
                                <div class="text-muted d-inline font-weight-normal">
                                    <div class="slash"></div> Guru {{ $guru->mapel->nama_mapel ?? '-' }}
                                </div>
                            </div>

                            <label for="alamat">Alamat</label>
                            <p>{{ $guru->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

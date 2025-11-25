@extends('layouts.main')
@section('title', 'List Jadwal')

@section('content')
<section class="section custom-section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>List Jadwal</h4>
                    </div>
                    <div class="card-body">
                        @include('partials.alert')
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Hari</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Guru</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jadwal as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->mapel->nama_mapel ?? ($item->guru->mapel->nama_mapel ?? '-') }}</td>
                                        <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                                        <td>{{ ucfirst((string) ($item->hari ?? '-')) }}</td>
                                        <td>{{ $item->dari_jam ?? '-' }}</td>
                                        <td>{{ $item->sampai_jam ?? ($item->hingga_jam ?? '-') }}</td>
                                        <td>{{ $item->guru->nama ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada jadwal tersedia.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Optional: simple client-side search & pagination controls can be added here if needed --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layouts.main')

@section('title', 'List Guru')

@section('content')
<section class="section custom-section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>List Guru</h4>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="nav-icon fas fa-folder-plus"></i>&nbsp; Tambah Data Guru</button>
                    </div>
                    <div class="card-body">
                        @include('partials.alert')
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Guru</th>
                                        <th>NIP</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guru as $result => $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->nip }}</td>
                                        <td>{{ $data->mapel->nama_mapel }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('guru.show', Crypt::encrypt($data->id)) }}" class="btn btn-primary btn-sm" style="margin-right: 8px"><i class="nav-icon fas fa-user"></i> &nbsp; Profile</a>
                                                <a href="{{ route('guru.edit', Crypt::encrypt($data->id)) }}" class="btn btn-success btn-sm"><i class="nav-icon fas fa-edit"></i> &nbsp; Edit</a>
                                                <form method="POST" action="{{ route('guru.destroy', $data->id) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-danger btn-sm show_confirm" data-toggle="tooltip" title='Delete' style="margin-left: 8px"><i class="nav-icon fas fa-trash-alt"></i> &nbsp; Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Data Guru</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('guru.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible show fade">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert">
                                                    <span>&times;</span>
                                                </button>
                                                @foreach ($errors->all() as $error )
                                                {{ $error }}
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="nama">Nama Guru</label>
                                            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="{{ __('Nama Guru') }}" value="{{ old('nama') }}">
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="form-group">
                                                <label for="nip">NIP</label>
                                                <input type="number" id="nip" name="nip" class="form-control @error('nip') is-invalid @enderror" placeholder="{{ __('NIP Guru') }}" value="{{ old('nip') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="no_telp">No. Telp</label>
                                                <input type="number" id="no_telp" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror" placeholder="{{ __('No. Telp Guru') }}" value="{{ old('no_telp') }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="mapel_id">Mapel</label>
                                            <select id="mapel_id" name="mapel_id" class="select2 form-control @error('mapel_id') is-invalid @enderror">
                                                <option value="">-- Pilih Mapel --</option>
                                                @foreach ($mapel as $m)
                                                    <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                                        {{ $m->nama_mapel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" placeholder="{{ __('Alamat') }}" value="{{ old('alamat') }}"></textarea>
                                        </div>
                                        <!-- PREVIEW GAMBAR: tambahkan ini tepat sebelum input file -->
                                        <div class="form-group mb-2">
                                            <label class="d-block">Preview Foto</label>
                                            <img id="guruFotoPreview"
                                                src="{{ asset('images/default-avatar.png') }}"
                                                style="width:120px;height:120px;object-fit:cover;border-radius:6px;border:1px solid #ddd;display:inline-block;">
                                        </div>
                                        <div class="form-group">
                                            <label for="foto">Foto Guru</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input id="foto" type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" id="foto">
                                                    <label class="custom-file-label" for="foto">Pilih file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer br">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script type="text/javascript">
    $('.show_confirm').click(function(event) {
        var form = $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        swal({
                title: `Yakin ingin menghapus data ini?`
                , text: "Data akan terhapus secara permanen!"
                , icon: "warning"
                , buttons: true
                , dangerMode: true
            , })
            .then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
    });

</script>
@endpush

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // elemen input file (modal) - gunakan selector yang spesifik agar tidak bentrok
        const fotoInputModal = document.querySelector('#exampleModal input[type="file"][name="foto"]');
        const previewModal = document.getElementById('guruFotoPreview');

        if (!fotoInputModal || !previewModal) return;

        // simpan src awal agar bisa dikembalikan bila input dikosongkan
        const originalSrcModal = previewModal.src;

        fotoInputModal.addEventListener('change', function () {
            const file = this.files && this.files[0];

            // update label custom-file jika ada
            const customFileLabel = this.closest('.custom-file') ? this.closest('.custom-file').querySelector('.custom-file-label') : null;
            if (customFileLabel) customFileLabel.textContent = file ? file.name : 'Pilih file';

            if (!file) {
                previewModal.src = originalSrcModal;
                return;
            }

            // validasi tipe file image
            if (!file.type.startsWith('image/')) {
                alert('Silakan pilih file gambar (jpg, jpeg, png, gif, webp).');
                this.value = '';
                if (customFileLabel) customFileLabel.textContent = 'Pilih file';
                previewModal.src = originalSrcModal;
                return;
            }

            // batasi ukuran (mis. 2 MB)
            const maxMB = 2;
            if (file.size > maxMB * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimum ' + maxMB + ' MB.');
                this.value = '';
                if (customFileLabel) customFileLabel.textContent = 'Pilih file';
                previewModal.src = originalSrcModal;
                return;
            }

            // tampilkan preview menggunakan FileReader
            const reader = new FileReader();
            reader.onload = function (e) {
                previewModal.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        // jika modal ditutup/reset, kembalikan preview ke default
        $('#exampleModal').on('hidden.bs.modal', function () {
            // reset input dan label
            if (fotoInputModal) {
                fotoInputModal.value = '';
                const label = fotoInputModal.closest('.custom-file') ? fotoInputModal.closest('.custom-file').querySelector('.custom-file-label') : null;
                if (label) label.textContent = 'Pilih file';
            }
            if (previewModal) previewModal.src = originalSrcModal;
        });
    });
</script>
@endpush
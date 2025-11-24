@extends('layouts.main')
@section('title', 'List User')

@section('content')
<section class="section custom-section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>List Tugas</h4>
                    </div>
                    <div class="card-body">
                        @include('partials.alert')
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Tugas</th>
                                        <th>Deskripsi</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tugas as $key => $tugas)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tugas->judul }}</td>
                                        <td>{{ $tugas->deskripsi }}</td>
                                        <td>{{ $tugas->guru->mapel->nama_mapel }}</td>
                                        <td>
                                            <div class="d-flex">
                                                @if ($jawaban->count() > 0)
                                                {{-- Hiding button --}}
                                                @else
                                                <button class="btn btn-primary mr-2" data-toggle="modal" data-target="#exampleModal"><i class="nav-icon fas fa-paper-plane"></i>&nbsp; Kirim Jawaban</button>
                                                @endif
                                                <a href="{{ route('siswa.tugas.download', $tugas->id) }}" class="btn btn-success btn-sm align-self-center"><i class="nav-icon fas fa-download"></i> &nbsp; Download Tugas</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Kirim jawaban</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('kirim-jawaban') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
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
                                    @if ($tugas->count() > 0)
                                    <input name="tugas_id" type="text" value="{{ $tugas->id }}" hidden>
                                    <div class="form-group">
                                        <label for="jawaban">Jawaban</label>
                                        <input type="text" id="jawaban" name="jawaban" class="form-control @error('jawaban') is-invalid @enderror" placeholder="{{ __('Jawaban tugas') }}">
                                    </div>
                                    <!-- PREVIEW GAMBAR: tambahkan ini tepat sebelum input file -->
                                    <div class="form-group mb-2">
                                        <label class="d-block">Preview Foto</label>
                                        <img id="tugasFotoPreview"
                                            src="{{ asset('images/default-avatar.png') }}"
                                            style="width:120px;height:120px;object-fit:cover;border-radius:6px;border:1px solid #ddd;display:inline-block;">
                                    </div>
                                    <div class="form-group">
                                        <label for="file">File Tugas</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input id="file" type="file" name="file" class="form-control @error('file') is-invalid @enderror" id="file">
                                                <label class="custom-file-label" for="file">Pilih file</label>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label for="jawaban">Jawaban</label>
                                        <input type="text" id="jawaban" name="jawaban" class="form-control @error('jawaban') is-invalid @enderror" placeholder="{{ __('Jawaban tugas') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="file">File Tugas</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input id="file" type="file" name="file" class="form-control @error('file') is-invalid @enderror" id="file">
                                                <label class="custom-file-label" for="file">Pilih file</label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
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
</section>
@endsection
@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // elemen input file (modal) - gunakan selector yang spesifik agar tidak bentrok
        const fotoInputModal = document.querySelector('#exampleModal input[type="file"][name="file"]');
        const previewModal = document.getElementById('tugasFotoPreview');

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
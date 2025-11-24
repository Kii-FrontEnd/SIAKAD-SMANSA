@extends('layouts.main')

@section('title', 'Edit Guru')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                @include('partials.alert')
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>Edit Guru {{ $guru->nama }}</h4>
                        <a href="{{ route('guru.index') }}" class="btn btn-primary">Kembali</a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('guru.update', $guru->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- ====== TAMBAHAN: img preview baru (tidak mengubah img lama) ====== --}}
                            <div class="mb-3">
                                <label class="d-block">Preview Foto</label>
                                <img id="currentFotoPreview"
                                     src=""
                                     style="width:120px;height:120px;object-fit:cover;border-radius:6px;border:1px solid #ddd;display:inline-block;">
                            </div>
                            {{-- ====== END TAMBAHAN ====== --}}
                            <img src="{{ url(Storage::url($guru->foto)) }}" style="width: 120px" alt="foto guru">

                            <div class="form-group">
                                <label for="foto">Foto Guru</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input id="foto" type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" id="foto">
                                        <label class="custom-file-label" for="foto">Pilih file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama Guru</label>
                                <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="{{ __('Nama Guru') }}" value="{{ $guru->nama }}">
                            </div>
                            <div class="d-flex">
                                <div class="form-group">
                                    <label for="nip">NIP</label>
                                    <input type="text" id="nip" name="nip" class="form-control @error('nip') is-invalid @enderror" placeholder="{{ __('NIP Guru') }}" value="{{ $guru->nip }}">
                                </div>
                                <div class="form-group ml-4">
                                    <label for="no_telp">No. Telp</label>
                                    <input type="text" id="no_telp" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror" placeholder="{{ __('No. TElp Guru') }}" value="{{ $guru->no_telp }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Textarea</label>
                                <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" placeholder="{{ __('Alamat') }}">{{ $guru->alamat }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="mapel_id">Mata Pelajaran</label>
                                <select id="mapel_id" name="mapel_id" class="select2bs4 form-control @error('mapel_id') is-invalid @enderror">
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach ($mapel as $data )
                                    <option value="{{ $data->id }}" @if ($guru->mapel_id == $data->id)
                                        selected
                                        @endif
                                        >{{ $data->nama_mapel }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp; Simpan Perubahan</button>
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
        // Elemen
        const fotoInput = document.getElementById('foto');
        const previewImg = document.getElementById('currentFotoPreview');
        const originalImg = document.querySelector('img[alt="foto guru"]'); // elemen img asli yang Anda pertahankan

        // Jika original img ada, gunakan src-nya sebagai preview awal dan sembunyikan original agar tidak tampil ganda
        if (originalImg && previewImg) {
            try {
                // ambil src dari original, jika kosong gunakan default placeholder
                const origSrc = originalImg.getAttribute('src') || '';
                if (origSrc) {
                    // set preview awal
                    previewImg.src = origSrc;
                } else {
                    // jika tidak ada src, sembunyikan previewImg (akan diatur saat memilih file)
                    // tetap biarkan tampil default kosong
                }
                // sembunyikan original img supaya tidak muncul ganda
                originalImg.style.display = 'none';
            } catch (e) {
                // no-op
            }
        }

        if (!fotoInput || !previewImg) return;

        // simpan src awal agar bisa dikembalikan bila input dikosongkan
        const originalSrc = previewImg.src;

        fotoInput.addEventListener('change', function () {
            const file = this.files && this.files[0];

            // update label custom-file bila ada (Bootstrap)
            const label = this.closest('.custom-file') ? this.closest('.custom-file').querySelector('.custom-file-label') : null;
            if (label) label.textContent = file ? file.name : 'Pilih file';

            if (!file) {
                previewImg.src = originalSrc;
                return;
            }

            // validasi tipe file image
            if (!file.type.startsWith('image/')) {
                alert('Silakan pilih file gambar (jpg, jpeg, png, gif, webp).');
                this.value = '';
                if (label) label.textContent = 'Pilih file';
                previewImg.src = originalSrc;
                return;
            }

            // batasi ukuran (misal 2 MB)
            const maxMB = 2;
            if (file.size > maxMB * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimum ' + maxMB + ' MB.');
                this.value = '';
                if (label) label.textContent = 'Pilih file';
                previewImg.src = originalSrc;
                return;
            }

            // tampilkan preview menggunakan FileReader
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        // jika pengguna membatalkan/clear file (mis. dengan tombol reset), kembalikan preview awal
        fotoInput.addEventListener('input', function () {
            if (!this.files || this.files.length === 0) {
                previewImg.src = originalSrc;
                const label = this.closest('.custom-file') ? this.closest('.custom-file').querySelector('.custom-file-label') : null;
                if (label) label.textContent = 'Pilih file';
            }
        });
    });
</script>
@endpush

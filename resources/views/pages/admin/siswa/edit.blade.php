@extends('layouts.main')
@section('title', 'Edit Siswa')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    @include('partials.alert')
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Edit Siswa {{ $siswa->nama }}</h4>
                            <a href="{{ route('siswa.index') }}" class="btn btn-primary">Kembali</a>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('siswa.update', $siswa->id) }}" enctype="multipart/form-data">
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
                                <img src="{{ url(Storage::url($siswa->foto)) }}" style="width: 120px" alt="foto siswa">

                                <div class="form-group">
                                    <label for="foto">Foto Siswa</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input id="foto" type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" id="foto">
                                            <label class="custom-file-label" for="foto">Pilih file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Siswa</label>
                                    <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="{{ __('Nama Siswa') }}" value="{{ $siswa->nama }}">
                                </div>
                                <div class="d-flex">
                                    <div class="form-group">
                                        <label for="nis">NIS</label>
                                        <input type="text" id="nis" name="nis" class="form-control @error('nis') is-invalid @enderror" placeholder="{{ __('NIS Siswa') }}" value="{{ $siswa->nis }}">
                                    </div>
                                    <div class="form-group ml-4">
                                        <label for="telp">No. Telp</label>
                                        <input type="text" id="telp" name="telp" class="form-control @error('telp') is-invalid @enderror" placeholder="{{ __('No. TElp Siswa') }}" value="{{ $siswa->telp }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" placeholder="{{ __('Alamat') }}">{{ $siswa->alamat }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="kelas_id">Kelas</label>
                                    <select id="kelas_id" name="kelas_id" class="select2bs4 form-control @error('kelas_id') is-invalid @enderror">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($kelas as $data )
                                            <option value="{{ $data->id }}"
                                            @if ($siswa->kelas_id == $data->id)
                                                selected
                                            @endif
                                        >{{ $data->nama_kelas }}</option>
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
        const originalImg = document.querySelector('img[alt="foto siswa"]'); // elemen img asli yang Anda pertahankan

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

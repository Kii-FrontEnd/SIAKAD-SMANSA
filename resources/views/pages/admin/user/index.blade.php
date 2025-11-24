@extends('layouts.main')
@section('title', 'List User')

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <section class="section custom-section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>List User</h4>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i
                                    class="nav-icon fas fa-folder-plus"></i>&nbsp; Tambah Data User</button>
                        </div>
                        <div class="card-body">
                            @include('partials.alert')
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-2">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama User</th>
                                            <th>Roles</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $result => $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->roles }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <form method="POST"
                                                            action="{{ route('user.destroy', $data->id) }}">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-danger btn-sm show_confirm"
                                                                data-toggle="tooltip" title='Delete'
                                                                style="margin-left: 8px"><i
                                                                    class="nav-icon fas fa-trash-alt"></i> &nbsp;
                                                                Hapus</button>
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
                <div class="modal fade" role="dialog" id="exampleModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('user.store') }}" method="POST" id="addUserForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if ($errors->any())
                                                <div class="alert alert-danger alert-dismissible show fade">
                                                    <div class="alert-body">
                                                        <button class="close" data-dismiss="alert">
                                                            <span>&times;</span>
                                                        </button>
                                                        @foreach ($errors->all() as $error)
                                                            {{ $error }}
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    placeholder="{{ __('Email User') }}" value="{{ old('email', '') }}" required>
                                            </div>
                                            <input name="password" type="password" value="password123" hidden>
                                            <div class="form-group">
                                                <label for="roles">Roles</label>
                                                <select id="roles" name="roles"
                                                    class="form-control @error('roles') is-invalid @enderror">
                                                    <option value="">-- Pilih Roles --</option>
                                                    <option value="admin" {{ old('roles') == 'admin' ? 'selected' : '' }}>
                                                        Admin</option>
                                                    <option value="guru" {{ old('roles') == 'guru' ? 'selected' : '' }}>
                                                        Guru</option>
                                                    <option value="siswa" {{ old('roles') == 'siswa' ? 'selected' : '' }}>
                                                        Siswa</option>
                                                    <option value="orangtua"
                                                        {{ old('roles') == 'orangtua' ? 'selected' : '' }}>Orangtua
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group" id="noId"></div>

                                            <!-- TAMBAHAN: field Password dan Confirm Password -->
                                            <!-- TAMBAHAN: field Password dengan toggle mata -->
                                            <div class="form-group">
                                                <label for="password_input">Password</label>
                                                <div class="input-group">
                                                    <input id="password" type="password" name="password"
                                                        class="form-control @error('password') is-invalid @enderror" minlength="8" required>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password" tabindex="-1">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="password_confirmation_input">Confirm Password</label>
                                                <div class="input-group">
                                                    <input id="password_confirmation" type="password" name="password_confirmation"
                                                        class="form-control" minlength="8" required>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation" tabindex="-1">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END TAMBAHAN -->

                                        </div>
                                    </div>
                                    <div class="modal- br">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Yakin ingin menghapus data ini?`,
                    text: "Data akan terhapus secara permanen!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>

    <!-- TAMBAHAN: script dinamis untuk role, select2, validasi password, inputAngka, dan open modal on old value -->
    <script>
        $(document).ready(function() {
            // Inisialisasi select2 untuk elemen statis jika tersedia
            if ($.fn.select2) {
                $('.select2').not('.select2-hidden-accessible').select2({ width: '100%' });
            }

            // Fungsi untuk inisialisasi select2 pada elemen dinamis di dalam #noId
            function initDynamicSelect2() {
                if ($.fn.select2) {
                    $('#noId').find('.select2').each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            $(this).select2({ width: '100%' });
                        }
                    });
                }
            }

            // Handler perubahan role -> tampilkan field identitas sesuai role
            $('#roles').on('change', function() {
                var kel = $('#roles option:selected').val();
                var html = '';

                if (kel === "guru") {
                    html = '<label for="nip">NIP guru</label>' +
                        '<input id="nip" type="text" onkeypress="return inputAngka(event)" placeholder="NIP Guru" class="form-control" name="nip" value="{{ old('nip') }}" autocomplete="off">';
                } else if (kel === "siswa") {
                    html = '<label for="nis">NIS Siswa</label>' +
                        '<input id="nis" type="text" placeholder="NIS Siswa" class="form-control" name="nis" value="{{ old('nis') }}" autocomplete="off">';
                } else if (kel === "admin") {
                    html = '<label for="name">Nama Admin</label>' +
                        '<input id="name" type="text" placeholder="Nama admin" class="form-control" name="name" value="{{ old('name') }}" autocomplete="off">';
                } else if (kel === "orangtua") {
                    html = `
                        <label for="name">Nama</label>
                        <input id="name" type="text" placeholder="Nama" class="form-control" name="name" value="{{ old('name') }}" autocomplete="off">
                        <label for="no_telp">No Telepon</label>
                        <input id="no_telp" type="text" placeholder="No Telepon" class="form-control" name="no_telp" value="{{ old('no_telp') }}" autocomplete="off">
                        <label for="alamat">Alamat</label>
                        <input id="alamat" type="text" placeholder="Alamat" class="form-control" name="alamat" value="{{ old('alamat') }}" autocomplete="off">
                        <label for="siswa">Daftar Siswa</label>
                        <select id="siswa" name="siswa[]" class="select2 form-control" multiple="multiple">
                            @foreach ($siswaList as $siswa)
                                <option value="{{ $siswa->id }}" {{ in_array($siswa->id, old('siswa', [])) ? 'selected' : '' }}>
                                    {{ $siswa->user->name ?? '(User belum terhubung)' }}
                                </option>
                            @endforeach
                        </select>
                    `;
                } else {
                    html = '';
                }

                $("#noId").html(html);
                initDynamicSelect2();
            });

            // Toggle password visibility (ikon mata) untuk elemen dengan class .toggle-password
            // Tombol toggle diharapkan memiliki attribute data-target="#inputId"
            $(document).on('click', '.toggle-password', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var targetSelector = $btn.data('target');
                var $input = $(targetSelector);
                if ($input.length === 0) return;

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $btn.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    $input.attr('type', 'password');
                    $btn.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Validasi client-side sebelum submit
            $('#addUserForm').on('submit', function(e) {
                var p = $('#password').val() || '';
                var cp = $('#password_confirmation').val() || '';

                if (p.length < 8) {
                    e.preventDefault();
                    alert('Password minimal 8 karakter.');
                    return false;
                }
                if (p !== cp) {
                    e.preventDefault();
                    alert('Password dan Konfirmasi Password tidak cocok.');
                    return false;
                }

                var role = $('#roles').val();
                if (role === 'guru' && ($('#nip').length && $('#nip').val().trim() === '')) {
                    e.preventDefault();
                    alert('Mohon isi NIP guru.');
                    return false;
                }
                if (role === 'siswa' && ($('#nis').length && $('#nis').val().trim() === '')) {
                    e.preventDefault();
                    alert('Mohon isi NIS siswa.');
                    return false;
                }

                return true;
            });

            // Helper: hanya angka untuk NIP
            window.inputAngka = function(evt) {
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
                return true;
            };

            // Jika ada old('roles') dari server-side (validasi gagal), buka modal dan set value
            @if(old('roles'))
                $('#exampleModal').modal('show');
                $('#roles').val('{{ old('roles') }}').trigger('change');
            @endif

            // Jika ada old value individual (mis. NIP/NIS), trigger change setelah DOM siap
            // Trigger manual jika roles sudah dipilih dari server (contoh: setelah reload)
            if ($('#roles').val()) {
                $('#roles').trigger('change');
            }
        });
    </script>
    <!-- END TAMBAHAN -->
@endpush

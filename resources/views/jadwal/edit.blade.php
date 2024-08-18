@section('js')
    <script>
        $(document).ready(function() {
            $('#prodi').change(function() {
                var prodi = $(this).val();
                var semester = $('#semester').val();
                var semesterMatkul;
                if (semester === 'ganjil') {
                    semesterMatkul = '1,3,5,7';
                } else if (semester === 'genap') {
                    semesterMatkul = '2,4,6,8';
                }
                if (prodi) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.kelas.by.prodi.and.semester') }}",
                        data: {
                            prodi: prodi,
                            semester: semesterMatkul
                        },
                        success: function(res) {
                            if (res) {
                                $("#kelas_id").empty();
                                $("#kelas_id").append('<option value="">Pilih Kelas</option>')
                                $.each(res, function(index, kelas) {
                                    $("#kelas_id").append('<option value="' + kelas.id +
                                        '">Mata Kuliah: ' + kelas.matkul
                                        .nm_matkul + ' / Semester: ' + kelas.matkul
                                        .semester + ' / Dosen: ' + kelas.dosen1
                                        .nm_dosen + '</option>');
                                });
                            } else {
                                $("#kelas_id").empty();
                            }
                        }
                    });
                } else {
                    $("#kelas_id").empty();
                }
            });

            $('#semester').change(function() {
                $('#prodi').change();
            });

            $('#kelas_id').change(function() {
                var kelasId = $(this).val();
                if (kelasId) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.jumlah.mahasiswa.by.kelas') }}",
                        data: {
                            kelas_id: kelasId
                        },
                        success: function(response) {
                            $('#jmlh_mhs').val(response.jumlah_mahasiswa);
                        }
                    });
                } else {
                    $('#jmlh_mhs').val('');
                }
            });


            $('#kd_ruang').change(function() {
                var kd_ruang = $(this).val();
                if (kd_ruang) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.kapasitas.ruangan') }}",
                        data: {
                            kd_ruang: kd_ruang
                        },
                        success: function(response) {
                            $('#kapasitas_ruang').val(response.kapasitas_ruangan);
                        }
                    });
                } else {
                    $('#kapasitas_ruang').val('');
                }
            });


            $('form').submit(function(event) {
                var jumlahMahasiswa = parseInt($('#jmlh_mhs').val());
                var kapasitasRuang = parseInt($('#kapasitas_ruang').val());
                if (jumlahMahasiswa > kapasitasRuang) {
                    alert(
                        'Kapasitas ruangan tidak mencukupi untuk jumlah mahasiswa yang dipilih. Silakan pilih ruangan lain.'
                    );
                    event.preventDefault();
                }
            });

        });
    </script>


@stop
@extends('layouts.app')
@section('content')
    <!-- End of Topbar -->

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit Jadwal</h6>
                <div class="mb-4"></div>
                <a href="{{ route('jadwal.index') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="tahunajaran">Tahun Ajaran</label>
                        <input type="text" class="form-control" id="tahun_ajaran"
                            value="{{ $jadwal->tahunajaran->tahun_ajaran }}" name="tahun_ajaran" readonly>
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select class="form-control" id="semester" name="semester" readonly>
                            <option value="ganjil" {{ $jadwal->semester == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ $jadwal->semester == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="prodi">Program Studi</label>
                        <select class="form-control" id="prodi" name="prodi">
                            <option value="">Pilih Prodi</option>
                            <option value="Umum" {{ $jadwal->prodi == 'Umum' ? 'selected' : '' }}>Umum</option>
                            @if (auth()->user()->level == 'af')
                                <option value="Sipil" {{ $jadwal->prodi == 'Sipil' ? 'selected' : '' }}>Sipil</option>
                                <option value="Informatika" {{ $jadwal->prodi == 'Informatika' ? 'selected' : '' }}>
                                    Informatika</option>
                                <option value="Arsitektur" {{ $jadwal->prodi == 'Arsitektur' ? 'selected' : '' }}>
                                    Arsitektur</option>
                                <option value="Perencanaan Wilayah dan Kota"
                                    {{ $jadwal->prodi == 'Perencanaan Wilayah dan Kota' ? 'selected' : '' }}>Perencanaan
                                    Wilayah dan Kota</option>
                            @elseif (auth()->user()->level == 'ap')
                                <option value="{{ auth()->user()->prodi }}"
                                    {{ $jadwal->prodi == auth()->user()->prodi ? 'selected' : '' }}>
                                    {{ auth()->user()->prodi }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kelas_id">Kelas</label>
                        <select class="form-control" id="kelas_id" name="kelas_id">
                            @foreach ($kelass as $kelas)
                                <option value="{{ $kelas->id }}"> Mata Kuliah:
                                    {{ $kelas->matkul->nm_matkul }} / Semester:
                                    {{ $kelas->matkul->semester }} / Dosen: {{ $kelas->dosen1->nm_dosen }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jmlh_mhs">Jumlah Mahasiswa Dalam Kelas</label>
                        <input type="text" class="form-control" id="jmlh_mhs" value="{{ $jadwal->kelas->jmlh_mhs }}"
                            name="jmlh_mhs" readonly>
                    </div>
                    <!-- Form untuk ruang -->
                    <div class="form-group">
                        <label for="kd_ruang">Ruang</label>
                        <select class="form-control" id="kd_ruang" name="kd_ruang">
                            <option value="{{ $jadwal->kd_ruang }}">{{ $jadwal->ruang->kd_ruang }} -
                                {{ $jadwal->ruang->nama_ruang }}</option>
                            @foreach ($ruangs as $ruang)
                                <option value="{{ $ruang->kd_ruang }}">{{ $ruang->kd_ruang }} - {{ $ruang->nama_ruang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kapasitas_ruang">Kapasitas Ruangan</label>
                        <input type="text" class="form-control" id="kapasitas_ruang"
                            value="{{ $jadwal->ruang->kapasitas }}" name="kapasitas_ruang" readonly>
                    </div>
                    <!-- Form untuk hari -->
                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <select class="form-control" id="hari" name="hari">
                            <option value="Senin" {{ $jadwal->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ $jadwal->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ $jadwal->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ $jadwal->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ $jadwal->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        </select>
                    </div>
                    <!-- Form untuk jam -->
                    <div class="form-group">
                        <label for="jam">Jam</label>
                        <select class="form-control" id="jam" name="jam">
                            <option value="08:00-08:50" {{ $jadwal->jam == '08:00-08:50' ? 'selected' : '' }}>08:00 - 08:50
                            </option>
                            <option value="08:50-09:40" {{ $jadwal->jam == '08:50-09:40' ? 'selected' : '' }}>08:50 - 09:40
                            </option>
                            <option value="09:40-10:30" {{ $jadwal->jam == '09:40-10:30' ? 'selected' : '' }}>09:40 - 10:30
                            </option>
                            <option value="10:30-11:20" {{ $jadwal->jam == '10:30-11:20' ? 'selected' : '' }}>10:30 - 11:20
                            </option>
                            <option value="11:20-12:10" {{ $jadwal->jam == '11:20-12:10' ? 'selected' : '' }}>11:20 - 12:10
                            </option>
                            <option value="13:00-13:50" {{ $jadwal->jam == '13:00-13:50' ? 'selected' : '' }}>13:00 - 13:50
                            </option>
                            <option value="13:50-14:40" {{ $jadwal->jam == '13:50-14:40' ? 'selected' : '' }}>13:50 - 14:40
                            </option>
                            <option value="14:40-15:30" {{ $jadwal->jam == '14:40-15:30' ? 'selected' : '' }}>14:40 - 15:30
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>

                <!-- Menampilkan error jika terdapat error validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

    </div>
    <!-- End of Topbar -->
@endsection

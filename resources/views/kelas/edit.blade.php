@extends('layouts.app')

@section('js')
    <script>
        $(document).ready(function() {
            $('#prodi').change(function() {
                var prodi = $(this).val();
                if (prodi) {
                    $.ajax({
                        type: "GET",
                        url: "{{ url('get-matkuls') }}/" + prodi,
                        success: function(res) {
                            if (res) {
                                $("#kd_matkul").empty();
                                $("#kd_matkul").append('<option value="">Pilih Mata Kuliah</option>');
                                $.each(res, function(index, matkul) {
                                    $("#kd_matkul").append('<option value="' + matkul.kd_matkul + '">' + matkul.nm_matkul + ' | ' + matkul.sks + ' SKS | Semester ' + matkul.semester + '</option>');
                                });
                            } else {
                                $("#kd_matkul").empty();
                            }
                        }
                    });
                } else {
                    $("#kd_matkul").empty();
                }
            });

            let dosenCount = {{ count(array_filter([$kelas->nidn1, $kelas->nidn2, $kelas->nidn3, $kelas->nidn4])) }};
            updateTambahDosenButtonState();

            document.getElementById('add-dosen-btn').addEventListener('click', function() {
                if (dosenCount < 4) {
                    dosenCount++;
                    const dosenSelect = document.createElement('div');
                    dosenSelect.classList.add('form-group');
                    dosenSelect.innerHTML = `
                        <label for="nidn${dosenCount}">Dosen ${dosenCount}</label>
                        <select class="form-control" id="nidn${dosenCount}" name="nidn[]">
                            <option value="">Pilih Dosen</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->nidn }}">{{ $dosen->nm_dosen }}</option>
                            @endforeach
                        </select>
                    `;
                    document.getElementById('additional-dosen-container').appendChild(dosenSelect);
                }
                updateTambahDosenButtonState();
            });

            function updateTambahDosenButtonState() {
                if (dosenCount >= 4) {
                    document.getElementById('add-dosen-btn').disabled = true;
                } else {
                    document.getElementById('add-dosen-btn').disabled = false;
                }
            }
        });
    </script>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit Kelas</h6>
                <div class="mb-4"></div>
                <a href="{{ route('kelas.index') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="program_studi">Program Studi</label>
                        <select name="prodi" id="prodi" class="form-control" required>
                            <option value="">Pilih Prodi</option>
                            <option value="Umum" {{ $kelas->prodi == 'Umum' ? 'selected' : '' }}>Umum</option>
                            @if (auth()->user()->level == 'af')
                                <option value="Sipil" {{ $kelas->prodi == 'Sipil' ? 'selected' : '' }}>Sipil</option>
                                <option value="Informatika" {{ $kelas->prodi == 'Informatika' ? 'selected' : '' }}>Informatika</option>
                                <option value="Arsitektur" {{ $kelas->prodi == 'Arsitektur' ? 'selected' : '' }}>Arsitektur</option>
                                <option value="Perencanaan Wilayah dan Kota" {{ $kelas->prodi == 'Perencanaan Wilayah dan Kota' ? 'selected' : '' }}>Perencanaan Wilayah dan Kota</option>
                            @elseif (auth()->user()->level == 'ap')
                                <option value="{{ auth()->user()->prodi }}" {{ $kelas->prodi == auth()->user()->prodi ? 'selected' : '' }}>{{ auth()->user()->prodi }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kd_matkul">Mata Kuliah</label>
                        <select class="form-control" id="kd_matkul" name="kd_matkul" required>
                            <option value="">Pilih Mata Kuliah</option>
                            @foreach ($matkuls as $matkul)
                                <option value="{{ $matkul->kd_matkul }}" {{ $kelas->kd_matkul == $matkul->kd_matkul ? 'selected' : '' }}>
                                    {{ $matkul->nm_matkul }} | {{ $matkul->sks }} SKS | Semester {{ $matkul->semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nidn1">Dosen 1</label>
                        <select class="form-control" id="nidn1" name="nidn[]" required>
                            <option value="">Pilih Dosen</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->nidn }}" {{ $dosen->nidn == $kelas->nidn1 ? 'selected' : '' }}>
                                    {{ $dosen->nm_dosen }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="additional-dosen-container">
                        @if ($kelas->nidn2)
                            <div class="form-group">
                                <label for="nidn2">Dosen 2</label>
                                <select class="form-control" id="nidn2" name="nidn[]">
                                    <option value="">Pilih Dosen</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nidn }}" {{ $dosen->nidn == $kelas->nidn2 ? 'selected' : '' }}>
                                            {{ $dosen->nm_dosen }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if ($kelas->nidn3)
                            <div class="form-group">
                                <label for="nidn3">Dosen 3</label>
                                <select class="form-control" id="nidn3" name="nidn[]">
                                    <option value="">Pilih Dosen</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nidn }}" {{ $dosen->nidn == $kelas->nidn3 ? 'selected' : '' }}>
                                            {{ $dosen->nm_dosen }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if ($kelas->nidn4)
                            <div class="form-group">
                                <label for="nidn4">Dosen 4</label>
                                <select class="form-control" id="nidn4" name="nidn[]">
                                    <option value="">Pilih Dosen</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nidn }}" {{ $dosen->nidn == $kelas->nidn4 ? 'selected' : '' }}>
                                            {{ $dosen->nm_dosen }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-dosen-btn" class="btn btn-secondary mb-3">Tambah Dosen</button>

                    <div class="form-group">
                        <label for="jmlh_mhs">Jumlah Mahasiswa</label>
                        <input type="number" class="form-control" id="jmlh_mhs" name="jmlh_mhs" value="{{ $kelas->jmlh_mhs }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Simpan</button> 
                </form>

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
@endsection

@extends('layouts.app')
@section('content')
    <!-- End of Topbar -->

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
                <div class="mb-4"></div>
                <a href="{{ url('matkul') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">

                <form action="/matkul/{{ $matkul->kd_matkul }}" method="POST">
                    @method('put')
                    @csrf
                    <label>Kode Mata Kuliah</label><br>
                    <div class="mb-3">
                        <input type="text" name="kd_matkul" class="form-control" required placeholder=""
                            value="{{ $matkul->kd_matkul }}">
                    </div>
                    <label>Mata Kuliah</label><br>
                    <div class="mb-3">
                        <input type="text" name="nm_matkul" class="form-control" placeholder=""
                            value="{{ $matkul->nm_matkul }}">
                    </div>
                    <label>Semester</label><br>
                    <select name="semester" class="form-control">
                        <option value="">Pilih Semester</option>
                        <option @if ($matkul->semester == '1') selected @endif>1</option>
                        <option @if ($matkul->semester == '2') selected @endif>2</option>
                        <option @if ($matkul->semester == '3') selected @endif>3</option>
                        <option @if ($matkul->semester == '4') selected @endif>4</option>
                        <option @if ($matkul->semester == '5') selected @endif>5</option>
                        <option @if ($matkul->semester == '6') selected @endif>6</option>
                        <option @if ($matkul->semester == '7') selected @endif>7</option>
                        <option @if ($matkul->semester == '8') selected @endif>8</option>
                    </select><br>
                    <label>Prodi</label><br>
                    <select name="prodi" class="form-control">
                        <option value="">Pilih Program Studi</option>
                        <option @if ($matkul->prodi == 'Umum') selected @endif>Umum</option>
                        <option @if ($matkul->prodi == 'Sipil') selected @endif>Sipil</option>
                        <option @if ($matkul->prodi == 'Informatika') selected @endif>Informatika</option>
                        <option @if ($matkul->prodi == 'Arsitektur') selected @endif>Arsitektur</option>
                        <option @if ($matkul->prodi == 'Perencanaan Wilayah dan Kota') selected @endif>Perencanaan Wilayah dan Kota</option>
                    </select><br>
                    <label>Sks</label><br>
                    <select name="sks" class="form-control">
                        <option value="">Pilih Sks</option>
                        <option @if ($matkul->sks == '1') selected @endif>1</option>
                        <option @if ($matkul->sks == '2') selected @endif>2</option>
                        <option @if ($matkul->sks == '3') selected @endif>3</option>
                        <option @if ($matkul->sks == '4') selected @endif>4</option>
                        <option @if ($matkul->sks == '5') selected @endif>5</option>
                        <option @if ($matkul->sks == '6') selected @endif>6</option>
                    </select><br>
                    <input class="btn btn-primary" type="submit" name="submit" value="Simpan">
                </form>
            </div>
        </div>
    </div>
@endsection

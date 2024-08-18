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
                <a href="{{ url('mahasiswa') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">

                <form action="/mahasiswa/{{ $mahasiswa->id }}" method="POST">
                    @method('put')
                    @csrf
                    <label>Jumlah Mahasiswa</label><br>
                    <div class="mb-3">
                        <input type="text" name="jmlh_mhs" class="form-control" placeholder="Nama"
                            value="{{ $mahasiswa->jmlh_mhs }}">
                    </div>
                    <label>Prodi</label><br>
                    <select name="prodi" class="form-control">
                        <option value="">Pilih Program Studi</option>
                        <option @if ($mahasiswa->prodi == 'Sipil') selected @endif>Sipil</option>
                        <option @if ($mahasiswa->prodi == 'Informatika') selected @endif>Informatika</option>
                        <option @if ($mahasiswa->prodi == 'Arsitektur') selected @endif>Arsitektur</option>
                        <option @if ($mahasiswa->prodi == 'Perencanaan Wilayah dan Kota') selected @endif>Perencanaan Wilayah dan Kota</option>
                    </select><br>

                    <input class="btn btn-primary" type="submit" name="submit" value="Simpan">
                </form>
            </div>
        </div>
    </div>
@endsection

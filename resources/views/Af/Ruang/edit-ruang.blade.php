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
                <a href="{{ url('ruang') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">
                <form action="/ruang/{{ $ruang->kd_ruang }}" method="POST">
                    @method('put')
                    @csrf
                    <label>Kode Ruang</label><br>
                    <div class="mb-3">
                        <input type="text" name="kd_ruang" class="form-control" placeholder=""
                            value="{{ $ruang->kd_ruang }}">
                    </div>
                    <label>Nama Ruang</label><br>
                    <div class="mb-3">
                        <input type="text" name="nama_ruang" class="form-control" placeholder="Nama"
                            value="{{ $ruang->nama_ruang }}">
                    </div>
                    <label>Prodi</label><br>
                    <select name="prodi" class="form-control">
                        <option value="">Pilih Program Studi</option>
                        <option value="Umum" @if ($ruang->prodi == 'Umum') selected @endif>Umum</option>
                        @if (auth()->user()->level == 'af')
                            <option value="Sipil" @if ($ruang->prodi == 'Sipil') selected @endif>Sipil</option>
                            <option value="Informatika" @if ($ruang->prodi == 'Informatika') selected @endif>Informatika
                            </option>
                            <option value="Arsitektur" @if ($ruang->prodi == 'Arsitektur') selected @endif>Arsitektur</option>
                            <option value="Perencanaan Wilayah dan Kota" @if ($ruang->prodi == 'Perencanaan Wilayah dan Kota') selected @endif>
                                Perencanaan Wilayah dan Kota</option>
                        @elseif (auth()->user()->level == 'ap')
                            <option value="{{ auth()->user()->prodi }}" selected>{{ auth()->user()->prodi }}</option>
                        @endif
                    </select><br>
                    <label>Kapasitas</label><br>
                    <div class="mb-3">
                        <input type="text" name="kapasitas" class="form-control" placeholder="Kapasitas"
                            value="{{ $ruang->kapasitas }}">
                    </div>
                    <input class="btn btn-primary" type="submit" name="submit" value="Simpan">
                </form>
            </div>
        </div>
    </div>
@endsection

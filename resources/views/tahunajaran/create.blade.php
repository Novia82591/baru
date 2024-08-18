@extends('layouts.app')
@section('content')
    <!-- End of Topbar -->

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Tahun Ajaran</h6>
                <div class="mb-4"></div>
                <a href="{{ route('tahunajaran.index') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('tahunajaran.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="tahun_ajaran">Tahun Ajaran</label>
                        <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran">
                    </div>
                    {{-- <div class="form-group">
                        <label for="ganjil_genap">Ganjil/Genap</label>
                        <select class="form-control" id="ganjil_genap" name="ganjil_genap">
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div> --}}
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>

    </div>


    <!-- End of Topbar -->
@endsection

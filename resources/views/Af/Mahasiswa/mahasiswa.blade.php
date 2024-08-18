@extends('layouts.app')
@section('content')
    <!-- End of Topbar -->

    <!-- Begin Page Content -->
    <div class="container-fluid">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Mahasiswa</h6>
                <div class="mb-4"></div>
                <a href="/tambah-mahasiswa" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Tambah</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun Ajaran</th>
                                <th>Jumlah Mahasiswa</th>
                                <th>Prodi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        @foreach ($mahasiswa as $mhs)
                            <tbody>
                                <tr>
                                    <td>{{ $mhs->id }}</td>
                                    <td>{{ $mhs->tahunajaran->tahun_ajaran }}</td>
                                    <td>{{ $mhs->jmlh_mhs }}</td>
                                    <td>{{ $mhs->prodi }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-warning" style="border-radius: 4px;"
                                                href="/mahasiswa/{{ $mhs->id }}/edit"><i class="fa fa-edit"></i></a>
                                            &nbsp;
                                            <form action= "/mahasiswa/{{ $mhs->id }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit"
                                                    onclick="return confirm('anda yakin ingin hapus data ini?')"
                                                    class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    @endsection

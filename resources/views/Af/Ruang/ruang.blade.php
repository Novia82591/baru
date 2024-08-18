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
                <h6 class="m-0 font-weight-bold text-primary">Data Ruang</h6>
                <div class="mb-4"></div>
                <a href="/tambah-ruang" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Tambah</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Tahun Ajaran</th>
                                <th>Nama Ruang</th>
                                <th>Prodi</th>
                                <th>Kapasitas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($ruang as $r)
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $r->kd_ruang }}</td>
                                    <td>{{ $r->tahunajaran->tahun_ajaran }}</td>
                                    <td>{{ $r->nama_ruang }}</td>
                                    <td>{{ $r->prodi }}</td>
                                    <td>{{ $r->kapasitas }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-warning" style="border-radius: 4px;"
                                                href="/ruang/{{ $r->kd_ruang }}/edit"><i class="fa fa-edit"></i></a> &nbsp;
                                            <form action= "/ruang/{{ $r->kd_ruang }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit"
                                                    onclick="return confirm('anda yakin ingin hapus data ini?')"
                                                    class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                            @php
                                $counter++;
                            @endphp
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection

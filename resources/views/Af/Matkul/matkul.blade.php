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
                <h6 class="m-0 font-weight-bold text-primary">Data Mata Kuliah</h6>
                <div class="mb-4"></div>
                <a href="/tambah-matkul" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Tambah</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Mata Kuliah</th>
                                <th>Mata Kuliah</th>
                                <th>Semester</th>
                                <th>Prodi</th>
                                <th>Sks</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>


                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($matkul as $ma)
                                <tr>
                                    <td>{{ $counter }}</td>
                                    {{-- <td>{{ $ma->tahunajaran->tahun_ajaran }}</td> --}}
                                    <td>{{ $ma->kd_matkul }}</td>
                                    <td>{{ $ma->nm_matkul }}</td>
                                    <td>{{ $ma->semester }}</td>
                                    <td>{{ $ma->prodi }}</td>
                                    <td>{{ $ma->sks }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-warning" style="border-radius: 4px;"
                                                href="/matkul/{{ $ma->kd_matkul }}/edit"><i class="fa fa-edit"></i></a>
                                            &nbsp;
                                            <form action= "/matkul/{{ $ma->kd_matkul }}" method="POST">
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

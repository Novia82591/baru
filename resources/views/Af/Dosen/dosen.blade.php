@extends('layouts.app')

@section('content')
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

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Dosen</h6>
                <div class="mb-4"></div>
                <a href="/tambah-dosen" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Tambah</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIDN</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($dosen as $d)
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $d->nidn }}</td>
                                    <td>{{ $d->nm_dosen }}</td>
                                    <td>{{ $d->prodi }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-warning" style="border-radius: 4px;"
                                                href="/dosen/{{ $d->nidn }}/edit"><i class="fa fa-edit"></i></a>
                                            <form action="/dosen/{{ $d->nidn }}" method="POST">
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
    </div>
@endsection

@section('css')
@endsection

@section('js')

@endsection

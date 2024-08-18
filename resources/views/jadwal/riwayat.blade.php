@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Riwayat Jadwal</h1>
        <p class="mb-4">Pilih Tahun Ajaran untuk melihat riwayat jadwal.</p>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun Ajaran</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tahunAjarans as $index => $tahunAjaran)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tahunAjaran->tahun_ajaran }}</td>
                                    <td>
                                        <a href="{{ route('jadwal.riwayat.prodi', $tahunAjaran->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

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
                <h6 class="m-0 font-weight-bold text-primary">Data Kelas</h6>
                <div class="mb-4"></div>
                <a href="{{ route('kelas.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Tambah</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Mata Kuliah</th>
                                <th>Dosen</th>
                                <th>Program Studi</th>
                                <th>Semester</th>
                                <th>Jumlah Mahasiswa</th>
                                <th>Tahun Ajaran</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($kelas as $item)
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $item->matkul->nm_matkul }}</td>
                                    <td>
                                        {{ $item->dosen1 ? $item->dosen1->nm_dosen : '' }}
                                        {{ $item->dosen2 ? '; ' . $item->dosen2->nm_dosen : '' }}
                                        {{ $item->dosen3 ? '; ' . $item->dosen3->nm_dosen : '' }}
                                        {{ $item->dosen4 ? '; ' . $item->dosen4->nm_dosen : '' }}
                                    </td>
                                    <td>{{ $item->prodi }}</td>
                                    <td>{{ $item->matkul->semester }}</td>
                                    <td>{{ $item->jmlh_mhs }}</td>
                                    <td>{{ $item->tahunajaran->tahun_ajaran }}</td>
                                    <td>
                                        <a href="{{ route('kelas.edit', $item->id) }}" class="btn btn-info btn-sm">Edit</a>
                                        <form action="{{ route('kelas.destroy', $item->id) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
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

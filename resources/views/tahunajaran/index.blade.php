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
                <h6 class="m-0 font-weight-bold text-primary">Data Tahun Ajaran</h6>
                <div class="mb-4"></div>
                <a href="{{ route('tahunajaran.create') }}" class="btn btn-primary btn-icon-split btn-sm">
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
                                {{-- <th>Ganjil/Genap</th> --}}
                                <th>Status Aktif</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($tahunAjarans as $item)
                                <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $item->tahun_ajaran }}</td>
                                    {{-- <td>{{ $item->ganjil_genap }}</td> --}}
                                    <td>
                                        @if ($item->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('tahunajaran.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('tahunajaran.destroy', $item->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
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


    <!-- End of Topbar -->
@endsection

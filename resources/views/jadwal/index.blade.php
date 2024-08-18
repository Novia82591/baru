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
                <h6 class="m-0 font-weight-bold text-primary">Data Jadwal</h6>
                <div>
                    @if (Auth::user()->level == 'af')
                        <a href="{{ route('jadwal.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                            <span class="text">Tambah</span>
                        </a>
                        <form action="{{ route('jadwal.validateAll') }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-icon-split btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin memvalidasi semua jadwal?')">
                                <span class="text">Validasi Semua</span>
                            </button>
                        </form>
                    @endif

                    @if (Auth::user()->level == 'ap' && !$isValidated)
                        <a href="{{ route('random.jadwal') }}" class="btn btn-warning btn-icon-split btn-sm"
                            onclick="return confirm('Apakah Anda yakin ingin merandom jadwal?')">
                            <span class="text">Random Jadwal</span>
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('jadwal.index') }}">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <label class="sr-only" for="filterHari">Hari</label>
                            <select class="form-control mb-2" id="filterHari" name="hari">
                                <option value="">Pilih Hari</option>
                                <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="sr-only" for="filterMatkul">Mata Kuliah</label>
                            <input type="text" class="form-control mb-2" id="filterMatkul" name="matkul"
                                placeholder="Mata Kuliah" value="{{ request('matkul') }}">
                        </div>
                        <div class="col-auto">
                            <label class="sr-only" for="filterProdi">Prodi</label>
                            @if (Auth::user()->level == 'af')
                                <select class="form-control mb-2" id="filterProdi" name="prodi">
                                    <option value="">Pilih Prodi</option>
                                    <option value="Informatika" {{ request('prodi') == 'Informatika' ? 'selected' : '' }}>
                                        Informatika</option>
                                    <option value="Sipil" {{ request('prodi') == 'Sipil' ? 'selected' : '' }}>Sipil
                                    </option>
                                    <option value="Arsitektur" {{ request('prodi') == 'Arsitektur' ? 'selected' : '' }}>
                                        Arsitektur</option>
                                    <option value="Perencanaan Wilayah dan Kota"
                                        {{ request('prodi') == 'Perencanaan Wilayah dan Kota' ? 'selected' : '' }}>
                                        Perencanaan Wilayah dan Kota</option>
                                </select>
                            @else
                                <input type="text" class="form-control mb-2" id="filterProdi" name="prodi"
                                    placeholder="Prodi" value="{{ Auth::user()->prodi }}" readonly>
                            @endif
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary mb-2">Filter</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="white-space: nowrap;">No</th>
                                <th style="white-space: nowrap;">Hari</th>
                                <th style="white-space: nowrap;">Jam</th>
                                <th style="white-space: nowrap;">Kode Matkul</th>
                                <th style="white-space: nowrap;">Mata Kuliah</th>
                                <th style="white-space: nowrap;">Semester</th>
                                <th style="white-space: nowrap;">Dosen</th>
                                <th style="white-space: nowrap;">Ruang</th>
                                <th style="white-space: nowrap;">Tahun Ajaran</th>
                                <th style="white-space: nowrap;">Prodi</th>
                                <th style="white-space: nowrap;">Jumlah Mahasiswa</th>
                                <th style="white-space: nowrap;">Kapasitas Ruangan</th>
                                <th style="white-space: nowrap;">Validasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mergedJadwals as $jadwal)
                                <tr>
                                    <td style="white-space: nowrap;">{{ $loop->iteration }}</td>
                                    <td style="white-space: nowrap;">{{ $jadwal['hari'] }}</td>
                                    <td style="white-space: nowrap;">{{ $jadwal['jam'] }} WITA</td>
                                    <td style="white-space: nowrap;">{{ $jadwal['kelas']->matkul->kd_matkul }}</td>
                                    <td style="white-space: nowrap;">{{ $jadwal['kelas']->matkul->nm_matkul }}</td>
                                    <td style="white-space: nowrap;">{{ $jadwal['kelas']->matkul->semester }}</td>
                                    <td style="white-space: nowrap;">
                                        {{ $jadwal['kelas']->dosen1 ? $jadwal['kelas']->dosen1->nm_dosen : '' }}
                                        {{ $jadwal['kelas']->dosen2 ? '; ' . $jadwal['kelas']->dosen2->nm_dosen : '' }}
                                        {{ $jadwal['kelas']->dosen3 ? '; ' . $jadwal['kelas']->dosen3->nm_dosen : '' }}
                                        {{ $jadwal['kelas']->dosen4 ? '; ' . $jadwal['kelas']->dosen4->nm_dosen : '' }}
                                    </td>
                                    <td style="white-space: nowrap;">{{ $jadwal['ruang']->nama_ruang }}</td>
                                    <td style="white-space: nowrap;">{{ $tahunAjaranAktif ? $tahunAjaranAktif->tahun_ajaran : 'Tidak Ada' }}</td>
                                    <td style="white-space: nowrap;">{{ $jadwal['kelas']->prodi }}</td>
                                    <td style="white-space: nowrap;">{{ $jadwal['kelas']->jmlh_mhs }}</td>
                                    <td style="white-space: nowrap;">{{ $jadwal['ruang']->kapasitas }}</td>
                                    <td style="white-space: nowrap;">
                                        @if($jadwal['validated'])
                                            <span class="badge badge-success">Validated</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
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

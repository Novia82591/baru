@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Riwayat Random Jadwal</h1>
        <p class="mb-4">Berikut adalah riwayat hasil random jadwal.</p>

        <form method="GET" action="{{ route('jadwal.history') }}">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <label class="sr-only" for="filterGenerate">Generate</label>
                    <select class="form-control mb-2" id="filterGenerate" name="generate" required>
                        <option value="">Pilih History</option>
                        @foreach ($generateOptions as $option)
                            <option value="{{ $option->generate_date }}|{{ $option->generate_count }}"
                                {{ request('generate') == $option->generate_date . '|' . $option->generate_count ? 'selected' : '' }}>
                                {{ $option->generate_date }} / {{ $option->generate_count }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="sr-only" for="filterProdi">Prodi</label>
                    <select class="form-control mb-2" id="filterProdi" name="prodi">
                        <option value="">Pilih Mata Kuliah</option>
                        @if (auth()->user()->level == 'af')
                            <option value="Umum" {{ request('prodi') == 'Umum' ? 'selected' : '' }}>Umum</option>
                            <option value="Sipil" {{ request('prodi') == 'Sipil' ? 'selected' : '' }}>Sipil</option>
                            <option value="Informatika" {{ request('prodi') == 'Informatika' ? 'selected' : '' }}>
                                Informatika
                            </option>
                            <option value="Arsitektur" {{ request('prodi') == 'Arsitektur' ? 'selected' : '' }}>Arsitektur
                            </option>
                            <option value="Perencanaan Wilayah dan Kota"
                                {{ request('prodi') == 'Perencanaan Wilayah dan Kota' ? 'selected' : '' }}>Perencanaan
                                Wilayah
                                dan Kota</option>
                        @elseif (auth()->user()->level == 'ap')
                        <option value="Umum" {{ request('prodi') == 'Umum' ? 'selected' : '' }}>Umum</option>
                            <option value="{{ auth()->user()->prodi }}" {{ request('prodi') == auth()->user()->prodi ? 'selected' : '' }}>{{ auth()->user()->prodi }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-2">Filter</button>
                </div>
            </div>
        </form>

        <div class="card shadow mb-4">
            <div class="card-body">
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
                                <th style="white-space: nowrap;">Prodi</th>
                                <th style="white-space: nowrap;">Dosen</th>
                                <th style="white-space: nowrap;">Ruang</th>
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
                                    <td style="white-space: nowrap;">{{ $jadwal['kelas']->prodi }}</td>
                                    <td style="white-space: nowrap;">
                                        {{ $jadwal['kelas']->dosen1 ? $jadwal['kelas']->dosen1->nm_dosen : '' }}
                                        {{ $jadwal['kelas']->dosen2 ? '; ' . $jadwal['kelas']->dosen2->nm_dosen : '' }}
                                        {{ $jadwal['kelas']->dosen3 ? '; ' . $jadwal['kelas']->dosen3->nm_dosen : '' }}
                                        {{ $jadwal['kelas']->dosen4 ? '; ' . $jadwal['kelas']->dosen4->nm_dosen : '' }}
                                    </td>
                                    <td style="white-space: nowrap;">{{ $jadwal['ruang']->nama_ruang }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

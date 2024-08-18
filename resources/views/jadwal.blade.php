<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal {{ $prodi }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container-fluid {
            flex: 1;
        }
        .navbar-brand img {
            width: 40px;
            height: auto;
        }
        .table-responsive {
            position: relative;
            height: 60vh;
            overflow: auto;
        }
        table {
            width: 100%;
            margin: 0;
        }
        thead th {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 1;
        }
        th, td {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('logo.png') }}" alt="UMB Logo">
            Sistem Informasi Penyusunan Jadwal Mata Kuliah
        </a>
    </nav>
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Jadwal {{ $prodi }}</h1>
        {{-- @if ($prodi == Sipil {teknik sipil}else{})
            
        @endif --}}
        <p class="mb-4">Tahun Ajaran: {{ $tahunAjaran }}</p>

        <div class="mb-4">
            <a href="{{ route('jadwal.download', ['prodi' => $prodi]) }}" class="btn btn-primary">Download PDF Jadwal</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kode Matkul</th>
                        <th>Mata Kuliah</th>
                        <th>Semester</th>
                        <th>Dosen</th>
                        <th>Ruang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwals as $index => $jadwal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $jadwal['hari'] }}</td>
                            <td>{{ $jadwal['jam'] }}</td>
                            <td>{{ $jadwal['kelas']->matkul->kd_matkul }}</td>
                            <td>{{ $jadwal['kelas']->matkul->nm_matkul }}</td>
                            <td>{{ $jadwal['kelas']->matkul->semester }}</td>
                            <td>
                                {{ $jadwal['kelas']->dosen1 ? $jadwal['kelas']->dosen1->nm_dosen : '' }}
                                {{ $jadwal['kelas']->dosen2 ? '; ' . $jadwal['kelas']->dosen2->nm_dosen : '' }}
                                {{ $jadwal['kelas']->dosen3 ? '; ' . $jadwal['kelas']->dosen3->nm_dosen : '' }}
                                {{ $jadwal['kelas']->dosen4 ? '; ' . $jadwal['kelas']->dosen4->nm_dosen : '' }}
                            </td>
                            <td>{{ $jadwal['ruang']->kd_ruang }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <footer class="bg-light text-center text-lg-start mt-auto py-3">
        <div class="container text-center">
            © 2024 Universitas Muhammadiyah Banjarmasin. All rights reserved.
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

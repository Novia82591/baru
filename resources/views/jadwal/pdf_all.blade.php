<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Semua Prodi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Smaller font size */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 380px; /* Adjusted logo size */
        }
        .header h1 {
            margin: 0;
            font-size: 18px; /* Smaller title size */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto; /* Ensures table fits content properly */
            margin-bottom: 20px; /* Adds space between tables */
        }
        th, td {
            border: 1px solid #000;
            padding: 4px; /* Smaller padding for more space */
            text-align: left;
            white-space: nowrap; /* Prevents text from wrapping */
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo-cover.png') }}" alt="Logo">
        <p>Tahun Ajaran: {{ $tahunAjaran->tahun_ajaran }}</p>
    </div>
    
    @foreach ($groupedJadwals as $prodi => $jadwals)
        <h2>Prodi: {{ $prodi }}</h2>
        @if(empty($jadwals))
            <p>Jadwal tidak tersedia.</p>
        @else
            <table>
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
        @endif
    @endforeach
</body>
</html>

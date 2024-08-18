<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjadwalan Mata Kuliah</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .btn-group-vertical {
            margin-right: 20px;
        }
        .welcome-text {
            margin-left: 20px;
        }
        .navbar-brand img {
            width: 40px;
            height: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('logo.png') }}" alt="UMB Logo">
            Sistem Informasi Penyusunan Jadwal Mata Kuliah
        </a>
        <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
    </nav>

    <div class="container">
        <div class="btn-group-vertical">
            <a href="{{ route('jadwal.prodi', 'Sipil') }}" class="btn btn-danger btn-lg mb-2">Sipil</a>
            <a href="{{ route('jadwal.prodi', 'Informatika') }}" class="btn btn-primary btn-lg mb-2">Informatika</a>
            <a href="{{ route('jadwal.prodi', 'Arsitektur') }}" class="btn btn-warning btn-lg mb-2">Arsitektur</a>
            <a href="{{ route('jadwal.prodi', 'Perencanaan Wilayah dan Kota') }}" class="btn btn-secondary btn-lg">Perencanaan Wilayah dan Kota</a>
        </div>
        <div class="welcome-text">
            <h1 class="display-4">Selamat Datang</h1>
            <p class="lead">Penjadwalan Mata Kuliah</p>
            <p>Di Fakultas Teknik</p>
            <p>Universitas Muhammadiyah Banjarmasin</p>
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
    
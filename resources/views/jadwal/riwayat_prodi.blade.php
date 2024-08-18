@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Riwayat Jadwal</h1>
        <p class="mb-4">Pilih Jadwal yang akan dicetak</p>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-body">
                @if(auth()->user()->level == 'af')
                    <div class="mb-4">
                        <a href="{{ route('jadwal.cetak.semua', $tahunAjaran) }}" class="btn btn-outline-primary">Cetak PDF
                            Semua</a>
                    </div>
                    <div class="btn-group-vertical">
                        <a href="{{ route('jadwal.cetak.prodi', [$tahunAjaran, 'Sipil']) }}"
                            class="btn btn-danger btn-lg mb-2">Sipil</a>
                        <a href="{{ route('jadwal.cetak.prodi', [$tahunAjaran, 'Informatika']) }}"
                            class="btn btn-primary btn-lg mb-2">Informatika</a>
                        <a href="{{ route('jadwal.cetak.prodi', [$tahunAjaran, 'Arsitektur']) }}"
                            class="btn btn-secondary btn-lg mb-2">Arsitektur</a>
                        <a href="{{ route('jadwal.cetak.prodi', [$tahunAjaran, 'Perencanaan Wilayah dan Kota']) }}"
                            class="btn btn-warning btn-lg">Perencanaan Wilayah dan Kota</a>
                    </div>
                @else
                    <div class="btn-group-vertical">
                        <a href="{{ route('jadwal.cetak.prodi', [$tahunAjaran, auth()->user()->prodi]) }}"
                            class="btn btn-primary btn-lg mb-2">{{ auth()->user()->prodi }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

<?php

use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/getJadwal', [JadwalController::class, 'getJadwal']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/jadwal/{prodi}', [JadwalController::class, 'prodi'])->name('jadwal.prodi');
Route::get('/download/jadwal/{prodi}', [JadwalController::class, 'downloadJadwalProdi'])->name('jadwal.download');
Route::middleware(['auth'])->group(function () {
    // Route::get('/', function () {
    //     // return view('Af.dashboard');
    // });
    // Route::get('/login', function () {
    //     return view('login');
    // });
    Route::get('/lihat-jadwal', function () {
        return view('lihat-jadwal');
    });
    Route::get('/dashboard', function () {
        return view('Af.dashboard');
    });
    Route::get('/informatika', function () {
        return view('Ap.informatika');
    });
    Route::get('/random-jadwal', [JadwalController::class, 'randomJadwal'])->name('random.jadwal');
    Route::get('/jadwal-history', [JadwalController::class, 'history'])->name('jadwal.history');
    Route::patch('jadwal/validate-all', [JadwalController::class, 'validateAll'])->name('jadwal.validateAll');

    Route::get('/dosen', [DosenController::class, 'index']);
    Route::get('/tambah-dosen', [DosenController::class, 'create']);
    Route::post('/simpan-dosen', [DosenController::class, 'store']);
    Route::get('/dosen/{id}/edit', [DosenController::class, 'edit']);
    Route::put('/dosen/{id}', [DosenController::class, 'update']);
    Route::delete('/dosen/{id}', [DosenController::class, 'destroy']);


    Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
    Route::get('/tambah-mahasiswa', [MahasiswaController::class, 'create']);
    Route::post('/simpan-mahasiswa', [MahasiswaController::class, 'store']);
    Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit']);
    Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update']);
    Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy']);


    Route::get('/ruang', [RuangController::class, 'index']);
    Route::get('/tambah-ruang', [RuangController::class, 'create']);
    Route::post('/simpan-ruang', [RuangController::class, 'store']);
    Route::get('/ruang/{id}/edit', [RuangController::class, 'edit']);
    Route::put('/ruang/{id}', [RuangController::class, 'update']);
    Route::delete('/ruang/{id}', [RuangController::class, 'destroy']);


    Route::get('/matkul', [MatkulController::class, 'index']);
    Route::get('/tambah-matkul', [MatkulController::class, 'create']);
    Route::post('/simpan-matkul', [MatkulController::class, 'store']);
    Route::get('/matkul/{id}/edit', [MatkulController::class, 'edit']);
    Route::put('/matkul/{id}', [MatkulController::class, 'update']);
    Route::delete('/matkul/{id}', [MatkulController::class, 'destroy']);
    Route::resource('tahunajaran', TahunAjaranController::class);

    Route::put('tahunajaran/{tahunajaran}/activate', [TahunAjaranController::class, 'activate'])->name('tahunajaran.activate');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('users', UserController::class);

    Route::resource('kelas', KelasController::class);

    Route::resource('jadwal', JadwalController::class);
    Route::get('get-matkuls/{prodi}', [KelasController::class, 'getMatkuls'])->name('get-matkuls');
    Route::get('get-kelas-by-prodi/{prodi}', [JadwalController::class, 'getKelasByProdi'])->name('get.kelas.by.prodi');

    Route::get('get-jumlah-mahasiswa-by-kelas', [JadwalController::class, 'getJumlahMahasiswaByKelas'])->name('get.jumlah.mahasiswa.by.kelas');
    Route::get('get-kapasitas-ruangan', [JadwalController::class, 'getKapasitasRuangan'])->name('get.kapasitas.ruangan');

    Route::get('/get-kelas-by-prodi-and-semester', [JadwalController::class, 'getKelasByProdiAndSemester'])->name('get.kelas.by.prodi.and.semester');

    Route::get('/riwayat-jadwal', [JadwalController::class, 'riwayatJadwal'])->name('jadwal.riwayat');
    Route::get('/riwayat-jadwal/{tahunAjaran}', [JadwalController::class, 'riwayatJadwalProdi'])->name('jadwal.riwayat.prodi');
    Route::get('/riwayat-jadwal/{tahunAjaran}/{prodi}/cetak', [JadwalController::class, 'cetakJadwalProdi'])->name('jadwal.cetak.prodi');
    Route::get('/riwayat-jadwal/{tahunAjaran}/cetak-semua', [JadwalController::class, 'cetakJadwalSemua'])->name('jadwal.cetak.semua');
});

Auth::routes();

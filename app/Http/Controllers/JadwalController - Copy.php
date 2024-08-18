<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $prodi = Auth::user()->prodi;
        $level = Auth::user()->level;
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();

        $query = Jadwal::where('tahun_ajaran_id', $tahunAjaranAktif->id);

        if ($level == 'ap') {
            $query->where(function ($query) use ($prodi) {
                $query->where('prodi', $prodi)
                    ->orWhere('prodi', 'Umum');
            });
        } elseif ($request->has('prodi') && $request->prodi != '') {
            $query->where('prodi', $request->prodi);
        }

        if ($request->has('hari') && $request->hari != '') {
            $query->where('hari', $request->hari);
        }

        if ($request->has('matkul') && $request->matkul != '') {
            $query->whereHas('kelas.matkul', function ($q) use ($request) {
                $q->where('nm_matkul', 'like', '%' . $request->matkul . '%');
            });
        }

        $jadwals = $query->get();

        return view('jadwal.index', compact('jadwals', 'tahunAjaranAktif'));
    }
    public function create()
    {
        $ruangs = Ruang::all();
        $kelass = Kelas::all();

        return view('jadwal.create', compact('ruangs', 'kelass'));
    }
    public function getKelasByProdi(Request $request, $prodi)
    {
        $kelas = Kelas::with(['matkul', 'dosen'])
            ->where('prodi', $prodi)
            ->get();

        return response()->json($kelas);
    }

    public function getKelasByProdiAndSemester(Request $request)
    {
        $prodi = $request->prodi;
        $semester = $request->semester;
        $semesters = explode(',', $semester);
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();

        $kelas = Kelas::with(['matkul', 'dosen'])->where('prodi', $prodi)
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->whereHas('matkul', function ($query) use ($semesters) {
                $query->whereIn('semester', $semesters);
            })
            ->get();


        return response()->json($kelas);
    }

    public function getJumlahMahasiswaByKelas(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $kelas = Kelas::findOrFail($kelasId);
        $jumlahMahasiswa = $kelas->jmlh_mhs;
        return response()->json(['jumlah_mahasiswa' => $jumlahMahasiswa]);
    }

    public function getKapasitasRuangan(Request $request)
    {
        $ruangId = $request->input('kd_ruang');
        $ruang = Ruang::where('kd_ruang', $ruangId)->first();
        $kapasitasRuangan = $ruang->kapasitas;
        return response()->json(['kapasitas_ruangan' => $kapasitasRuangan]);
    }
    public function store(Request $request)
    {

        $request->validate([
            'kelas_id' => 'required',
            'kd_ruang' => 'required',
            'hari' => 'required',
            'jam' => 'required',
            'prodi' => 'required',
            'semester' => 'required|in:ganjil,genap',
        ]);
        $exists = Jadwal::where('kelas_id', $request->kelas_id)
            ->where('kd_ruang', $request->kd_ruang)
            ->where('hari', $request->hari)
            ->where('jam', $request->jam)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['errors' => 'Data tersebut sudah ada, silahkan tambahkan di waktu yang lain.'])->withInput();
        }
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();
        $jadwal = Jadwal::create([
            'kelas_id' => $request->kelas_id,
            'kd_ruang' => $request->kd_ruang,
            'hari' => $request->hari,
            'jam' => $request->jam,
            'tahun_ajaran_id' => $tahunAjaranAktif->id,
            'prodi' => $request->prodi,
            'semester' => $request->semester,
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal baru telah berhasil disimpan.');
    }

    public function edit($id)
    {
        $ruangs = Ruang::all();
        $jadwal = Jadwal::findOrFail($id);


        $semesterMatkul = null;
        if ($jadwal->semester === 'ganjil') {
            $semesterMatkul = '1,3,5,7';
        } else if ($jadwal->semester === 'genap') {
            $semesterMatkul = '2,4,6,8';
        }

        $semester = $semesterMatkul;
        $semesters = explode(',', $semester);

        $kelass = Kelas::with(['matkul', 'dosen'])->where('prodi', $jadwal->prodi)
            ->whereHas('matkul', function ($query) use ($semesters) {
                $query->whereIn('semester', $semesters);
            })
            ->get();

        return view('jadwal.edit', compact('jadwal', 'ruangs', 'kelass'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required',
            'kd_ruang' => 'required',
            'hari' => 'required',
            'jam' => 'required',
            'prodi' => 'required',
            'semester' => 'required|in:ganjil,genap',
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update([
            'kelas_id' => $request->kelas_id,
            'kd_ruang' => $request->kd_ruang,
            'hari' => $request->hari,
            'jam' => $request->jam,
            'prodi' => $request->prodi,
            'semester' => $request->semester,
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);

        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function randomJadwal()
    {
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();
        $prodi = Auth::user()->prodi;

        $kelasList = Kelas::where(function ($query) use ($prodi) {
            $query->where('prodi', $prodi)
                ->orWhere('prodi', 'Umum');
        })
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->get();
        $ruangList = Ruang::where('tahun_ajaran_id', $tahunAjaranAktif->id)->get();

        if ($kelasList->isEmpty() || $ruangList->isEmpty()) {
            return redirect()->back()->with('error', 'Data kelas atau ruang untuk prodi Anda tidak tersedia.');
        }

        $existingJadwals = Jadwal::where('tahun_ajaran_id', $tahunAjaranAktif->id)->get();

        $hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jamOptions = [
            '08:00-08:50', '08:50-09:40', '09:40-10:30', '10:30-11:20', '11:20-12:10', '13:00-13:50', '13:50-14:40', '14:40-15:30'
        ];

        $bestSchedule = $this->generateRandomSchedule($kelasList, $existingJadwals, $hariOptions, $jamOptions, $ruangList);

        $saveCount = 0;
        $mutationCount = 0;

        foreach ($bestSchedule as $jadwalData) {
            $jadwal = new Jadwal();
            $jadwal->semester = $tahunAjaranAktif->ganjil_genap;
            $jadwal->kelas_id = $jadwalData['kelas_id'];
            $jadwal->kd_ruang = $jadwalData['kd_ruang'];
            $jadwal->hari = $jadwalData['hari'];
            $jadwal->jam = $jadwalData['jam'];
            $jadwal->prodi = $jadwalData['prodi'];
            $jadwal->tahun_ajaran_id = $tahunAjaranAktif->id;
            $jadwal->created_at = Carbon::now();
            $jadwal->updated_at = Carbon::now();

            $scheduledCount = Jadwal::where('kelas_id', $jadwalData['kelas_id'])
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->count();

            if ($scheduledCount < $jadwalData['sks']) {
                try {
                    $jadwal->save();
                    $saveCount++;
                } catch (\Exception $e) {
                    // Log::error('Error saat menyimpan jadwal: ' . $e->getMessage());
                }
            }
        }

        if ($mutationCount == 0) {
            $mutationCount = rand(1, 13451);
        }

        return redirect()->back()->with('success', 'Jadwal berhasil dirandom.');
    }

    private function generateRandomSchedule($kelasList, $existingJadwals, $hariOptions, $jamOptions, $ruangList)
    {
        $schedule = [];
        $occupiedSlots = [];
        $practicalPairs = [
            '21INF1204' => '21INF1203',
            '21INF2205' => '21INF2204',
            '21INF2207' => '21INF2206',
            '21INF3204' => '21INF3203',
            '21INF3206' => '21INF3205',
            '21INF5205' => '21INF5204',
            'INF3218' => 'INF3216',
            'INF3217' => 'INF3215',
            'INF4226' => '21INF3207',
            'INF4228' => 'INF4225',
            'INF5236' => 'INF5234',
            'INF5235' => 'INF5232'
            // '21INF2203' => '21INF2202',
            // '21INF2205' => '21INF2204',
            // '21INF2207' => '21INF2206',
            // '21INF4202' => '21INF4201',
            // '21INF4204' => '21INF4203',
        ];
    
        foreach ($kelasList as $kelas) {
            $sks = $kelas->matkul->sks;
            $kelasId = $kelas->id;
            $kelasProdi = $kelas->prodi;
            $jumlahMahasiswa = $kelas->jmlh_mhs;
    
            $filteredRuangList = $ruangList->filter(function ($ruang) use ($kelasProdi, $jumlahMahasiswa) {
                if ($kelasProdi == 'Umum') {
                    return $ruang->prodi == 'Umum' && $ruang->kapasitas >= $jumlahMahasiswa;
                } else {
                    return $ruang->prodi == $kelasProdi && $ruang->kapasitas >= $jumlahMahasiswa;
                }
            });
    
            $scheduledCount = $this->countScheduledSlots($existingJadwals, $kelasId);
    
            if ($scheduledCount >= $sks) {
                continue;
            }
    
            $foundSlot = false;
            $attempts = 0;
            $maxAttempts = count($hariOptions) * count($filteredRuangList) * (count($jamOptions) - $sks + 1);
    
            while (!$foundSlot && $attempts < $maxAttempts) {
                $attempts++;
                $hari = $hariOptions[array_rand($hariOptions)];
                $ruang = $filteredRuangList->random();
                $jamIndex = rand(0, count($jamOptions) - $sks);
    
                $slotAvailable = true;
    
                for ($i = 0; $i < $sks; $i++) {
                    $jam = $jamOptions[$jamIndex + $i];
                    $slotKey = $hari . '-' . $jam . '-' . $ruang->kd_ruang;
    
                    if (isset($occupiedSlots[$slotKey])) {
                        $slotAvailable = false;
                        break;
                    }
                }
    
                if ($slotAvailable && $scheduledCount < $sks) {
                    for ($i = 0; $i < $sks; $i++) {
                        $jam = $jamOptions[$jamIndex + $i];
                        $schedule[] = [
                            'kelas_id' => $kelas->id,
                            'kd_ruang' => $ruang->kd_ruang,
                            'hari' => $hari,
                            'jam' => $jam,
                            'prodi' => $kelas->prodi,
                            'sks' => $sks
                        ];
                        $slotKey = $hari . '-' . $jam . '-' . $ruang->kd_ruang;
                        $occupiedSlots[$slotKey] = true;
                        $scheduledCount++;
                    }
                    $foundSlot = true;
    
                    foreach ($practicalPairs as $practical => $main) {
                        if ($kelas->matkul->kd_matkul == $main) {
                            $practicalClass = $kelasList->firstWhere('matkul.kd_matkul', $practical);
    
                            if ($practicalClass) {
                                $practicalSks = $practicalClass->matkul->sks;
                                $jamIndexPractical = $jamIndex + $sks;
                                $slotAvailablePractical = true;
    
                                for ($i = 0; $i < $practicalSks; $i++) {
                                    if (($jamIndexPractical + $i) >= count($jamOptions)) {
                                        $slotAvailablePractical = false;
                                        break;
                                    }
                                    $jam = $jamOptions[$jamIndexPractical + $i];
                                    $slotKey = $hari . '-' . $jam . '-' . $ruang->kd_ruang;
    
                                    if (isset($occupiedSlots[$slotKey])) {
                                        $slotAvailablePractical = false;
                                        break;
                                    }
                                }
    
                                if ($slotAvailablePractical && !$this->isClassScheduled($existingJadwals, $practicalClass->id)) {
                                    for ($i = 0; $i < $practicalSks; $i++) {
                                        $jam = $jamOptions[$jamIndexPractical + $i];
                                        $schedule[] = [
                                            'kelas_id' => $practicalClass->id,
                                            'kd_ruang' => $ruang->kd_ruang,
                                            'hari' => $hari,
                                            'jam' => $jam,
                                            'prodi' => $practicalClass->prodi,
                                            'sks' => $practicalSks
                                        ];
                                        $slotKey = $hari . '-' . $jam . '-' . $ruang->kd_ruang;
                                        $occupiedSlots[$slotKey] = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
    
            if (!$foundSlot) {
                // Log::error('Gagal menemukan slot jadwal yang cocok untuk kelas: ', [$kelas->id]);
            }
        }
    
        return $schedule;
    }
    
    private function countScheduledSlots($existingJadwals, $kelasId)
    {
        return $existingJadwals->where('kelas_id', $kelasId)->count();
    }
    
    private function isClassScheduled($existingJadwals, $kelasId)
    {
        return $existingJadwals->contains('kelas_id', $kelasId);
    }
    
    

    // private function countScheduledSlots($existingJadwals, $kelasId)
    // {
    //     return $existingJadwals->where('kelas_id', $kelasId)->count();
    // }

    // private function isClassScheduled($existingJadwals, $kelasId)
    // {
    //     return $existingJadwals->contains('kelas_id', $kelasId);
    // }

    private function evaluateFitness($population, $existingJadwals)
    {
        $fitnessScores = [];
        foreach ($population as $schedule) {
            $score = 0;

            // Tambahkan logika penilaian kecocokan di sini
            // Contoh penilaian: Mengurangi skor jika ada konflik dengan jadwal yang sudah ada
            foreach ($schedule as $jadwal) {
                $conflict = $existingJadwals->first(function ($item) use ($jadwal) {
                    return $item->hari === $jadwal['hari'] && $item->jam === $jadwal['jam'] && $item->kd_ruang === $jadwal['kd_ruang'];
                });
                if ($conflict) {
                    $score -= 10; // Penalti untuk konflik jadwal
                }
            }

            $fitnessScores[] = $score;
        }
        return $fitnessScores;
    }

    private function selection($population, $fitnessScores)
    {
        $selected = [];
        $totalFitness = array_sum($fitnessScores);

        // Jika totalFitness adalah 0, gunakan metode seleksi alternatif
        if ($totalFitness == 0) {
            // Seleksi acak jika semua fitnessScores adalah 0
            $selected = $population;
        } else {
            foreach ($population as $index => $individual) {
                $probability = $fitnessScores[$index] / $totalFitness;
                if (mt_rand() / mt_getrandmax() < $probability) {
                    $selected[] = $individual;
                }
            }
        }

        // Ensure we have enough selected individuals to maintain population size
        while (count($selected) < count($population)) {
            $selected[] = $population[array_rand($population)];
        }

        return $selected;
    }

    private function crossover($parent1, $parent2)
    {
        $crossoverPoint = rand(1, count($parent1) - 2);

        $child1 = array_merge(array_slice($parent1, 0, $crossoverPoint), array_slice($parent2, $crossoverPoint));
        $child2 = array_merge(array_slice($parent2, 0, $crossoverPoint), array_slice($parent1, $crossoverPoint));

        return [$child1, $child2];
    }

    private function mutate($schedule, $existingJadwals, $hariOptions, $jamOptions, $ruangs, &$mutationCount)
    {
        $mutationRate = 0.1; // 10% chance to mutate
        foreach ($schedule as &$jadwal) {
            if (mt_rand() / mt_getrandmax() < $mutationRate) {
                $hari = $hariOptions[array_rand($hariOptions)];
                $jamIndex = array_rand($jamOptions);
                $ruang = $ruangs->random();
                $jam = $jamOptions[($jamIndex) % count($jamOptions)];

                // Cek duplikasi setelah mutasi
                $duplicate = $existingJadwals->first(function ($item) use ($hari, $jam, $ruang) {
                    return $item->hari === $hari && $item->jam === $jam && $item->kd_ruang === $ruang->kd_ruang;
                });

                if (!$duplicate) {
                    $jadwal['hari'] = $hari;
                    $jadwal['jam'] = $jam;
                    $jadwal['kd_ruang'] = $ruang->kd_ruang;
                    $mutationCount++;
                }
            }
        }
        return $schedule;
    }

public function getJadwal(Request $request)
{
    $prodi = $request->query('prodi');
    $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();

    $jadwals = Jadwal::where(function ($query) use ($prodi) {
        $query->where('prodi', $prodi)
            ->orWhere('prodi', 'Umum');
    })
    ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
    ->with(['kelas.matkul', 'kelas.dosen1', 'kelas.dosen2', 'kelas.dosen3', 'kelas.dosen4', 'ruang'])
    ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')")
    ->orderBy('kd_ruang')
    ->orderByRaw("FIELD(jam, '08:00-08:50', '08:50-09:40', '09:40-10:30', '10:30-11:20', '11:20-12:10', '13:00-13:50', '13:50-14:40', '14:40-15:30')")
    ->get();

    return response()->json([
        'jadwals' => $jadwals,
        'tahunAjaran' => $tahunAjaranAktif->tahun_ajaran,
    ]);
}

    
}

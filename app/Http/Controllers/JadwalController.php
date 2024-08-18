<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\RiwayatRandomJadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PDF;
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

        $jadwals = $query->with(['kelas.matkul', 'kelas.dosen1', 'kelas.dosen2', 'kelas.dosen3', 'kelas.dosen4', 'ruang'])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')")
            ->orderByRaw("FIELD(jam, '08:00-08:50', '08:50-09:40', '09:40-10:30', '10:30-11:20', '11:20-12:10', '13:00-13:50', '13:50-14:40', '14:40-15:30')")
            ->orderBy('kd_ruang')
            ->get();

        $mergedJadwals = $this->mergeJadwals($jadwals);
        $isValidated = $jadwals->where('validated', true)->isNotEmpty();

        return view('jadwal.index', compact('mergedJadwals', 'tahunAjaranAktif', 'isValidated'));
    }

    private function mergeJadwals($jadwals)
    {
        $merged = [];
        $temp = [];

        foreach ($jadwals as $jadwal) {
            $key = $jadwal->hari . '-' . $jadwal->kelas->matkul->kd_matkul . '-' . $jadwal->kelas->matkul->nm_matkul . '-' . ($jadwal->kelas->dosen1 ? $jadwal->kelas->dosen1->nm_dosen : '') . '-' . $jadwal->ruang->kd_ruang;
            if (!isset($temp[$key])) {
                $temp[$key] = [
                    'hari' => $jadwal->hari,
                    'jam' => [$jadwal->jam],
                    'kelas' => $jadwal->kelas,
                    'ruang' => $jadwal->ruang,
                    'validated' => $jadwal->validated
                ];
            } else {
                $temp[$key]['jam'][] = $jadwal->jam;
            }
        }

        foreach ($temp as $key => $value) {
            $startTime = explode('-', $value['jam'][0])[0];
            $endTime = explode('-', $value['jam'][count($value['jam']) - 1])[1];
            $jamRange = $startTime . ' - ' . $endTime;
            $value['jam'] = $jamRange;
            $merged[] = $value;
        }

        usort($merged, function($a, $b) {
            return strtotime($a['jam']) - strtotime($b['jam']);
        });

        return $merged;
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

        $kelas = Kelas::where('prodi', $prodi)
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
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

        $kelass = Kelas::where('prodi', $jadwal->prodi)
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
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update([
            'kelas_id' => $request->kelas_id,
            'kd_ruang' => $request->kd_ruang,
            'hari' => $request->hari,
            'jam' => $request->jam,
            'prodi' => $request->prodi,
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

        $today = Carbon::now()->toDateString();
        $generateCount = RiwayatRandomJadwal::where('prodi', $prodi)
            ->whereDate('generate_date', $today)
            ->max('generate_count') + 1;

        foreach ($bestSchedule as $jadwalData) {
            $jadwal = new Jadwal();
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

                    RiwayatRandomJadwal::create([
                        'kelas_id' => $jadwalData['kelas_id'],
                        'kd_ruang' => $jadwalData['kd_ruang'],
                        'hari' => $jadwalData['hari'],
                        'jam' => $jadwalData['jam'],
                        'prodi' => $jadwalData['prodi'],
                        'tahun_ajaran_id' => $tahunAjaranAktif->id,
                        'generate_date' => $today,
                        'generate_count' => $generateCount,
                    ]);
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
            ->orderByRaw("FIELD(jam, '08:00-08:50', '08:50-09:40', '09:40-10:30', '10:30-11:20', '11:20-12:10', '13:00-13:50', '13:50-14:40', '14:40-15:30')")
            ->orderBy('kd_ruang')
            ->get();

        return response()->json([
            'jadwals' => $jadwals,
            'tahunAjaran' => $tahunAjaranAktif->tahun_ajaran,
        ]);
    }

    public function history(Request $request)
    {
        // Fetch distinct generate date and count options for the dropdown
        $generateOptions = RiwayatRandomJadwal::select('generate_date', 'generate_count')
            ->groupBy('generate_date', 'generate_count')
            ->orderBy('generate_date', 'desc')
            ->orderBy('generate_count')
            ->get();
    
        // Get the selected filters
        $prodi = $request->query('prodi');
        $generate = $request->query('generate');
        $generateDate = null;
        $generateCount = null;
    
        if ($generate) {
            list($generateDate, $generateCount) = explode('|', $generate);
        }
    
        $query = RiwayatRandomJadwal::query();
    
        if ($prodi) {
            $query->where('prodi', $prodi);
        } else {
            if (auth()->user()->level == 'ap') {
                $query->where(function ($query) use ($prodi) {
                    $query->where('prodi', $prodi)
                        ->orWhere('prodi', 'Umum');
                });
            }
        }
    
        if ($generateDate && $generateCount) {
            $query->where('generate_date', $generateDate)
                ->where('generate_count', $generateCount);
        }
    
        $jadwals = $query->with(['kelas.matkul', 'kelas.dosen1', 'kelas.dosen2', 'kelas.dosen3', 'kelas.dosen4', 'ruang'])
            ->get();
    
        $mergedJadwals = $this->mergeJadwals2($jadwals);
    
        return view('jadwal.history', compact('mergedJadwals', 'generateOptions'));
    }
    
    private function mergeJadwals2($jadwals)
    {
        $merged = [];
        $temp = [];
    
        foreach ($jadwals as $jadwal) {
            $key = $jadwal->hari . '-' . $jadwal->kelas->matkul->kd_matkul . '-' . $jadwal->kelas->matkul->nm_matkul . '-' . $jadwal->kelas->dosen1->nm_dosen . '-' . $jadwal->ruang->kd_ruang;
            if (!isset($temp[$key])) {
                $temp[$key] = [
                    'hari' => $jadwal->hari,
                    'jam' => [$jadwal->jam],
                    'kelas' => $jadwal->kelas,
                    'ruang' => $jadwal->ruang,
                ];
            } else {
                $temp[$key]['jam'][] = $jadwal->jam;
            }
        }
    
        foreach ($temp as $key => $value) {
            usort($value['jam'], function ($a, $b) {
                return strtotime(explode('-', $a)[0]) - strtotime(explode('-', $b)[0]);
            });
            $startTime = explode('-', $value['jam'][0])[0];
            $endTime = explode('-', end($value['jam']))[1];
            $jamRange = $startTime . ' - ' . $endTime;
    
            $merged[] = [
                'hari' => $value['hari'],
                'jam' => $jamRange,
                'kelas' => $value['kelas'],
                'ruang' => $value['ruang'],
            ];
        }
    
        usort($merged, function ($a, $b) {
            $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            $dayComparison = array_search($a['hari'], $daysOfWeek) - array_search($b['hari'], $daysOfWeek);
            if ($dayComparison === 0) {
                $timeComparison = strtotime(explode(' - ', $a['jam'])[0]) - strtotime(explode(' - ', $b['jam'])[0]);
                if ($timeComparison === 0) {
                    return strcmp($a['ruang']->kd_ruang, $b['ruang']->kd_ruang);
                }
                return $timeComparison;
            }
            return $dayComparison;
        });
    
        return $merged;
    }
    

    public function validateAll(Request $request)
    {
        $prodi = Auth::user()->prodi;
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();

        Jadwal::where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->update(['validated' => true]);

        return redirect()->route('jadwal.index')->with('success', 'Semua jadwal telah divalidasi.');
    }
    public function riwayatJadwal()
    {
        $tahunAjarans = TahunAjaran::all();
        return view('jadwal.riwayat', compact('tahunAjarans'));
    }
    
    public function riwayatJadwalProdi($tahunAjaranId)
    {
        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);
        $prodis = ['Sipil', 'Informatika', 'Arsitektur', 'Perencanaan Wilayah dan Kota'];
        return view('jadwal.riwayat_prodi', compact('tahunAjaran', 'prodis'));
    }
    
    public function cetakJadwalProdi($tahunAjaranId, $prodi)
{
    $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);
    $mergedJadwals = Jadwal::where('tahun_ajaran_id', $tahunAjaranId)
        ->where(function($query) use ($prodi) {
            $query->where('prodi', $prodi)
                  ->orWhere('prodi', 'Umum');
        })
        ->where('validated', 1)
        ->with(['kelas.matkul', 'kelas.dosen1', 'kelas.dosen2', 'kelas.dosen3', 'kelas.dosen4', 'ruang'])
        ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')")
        ->orderByRaw("FIELD(jam, '08:00-08:50', '08:50-09:40', '09:40-10:30', '10:30-11:20', '11:20-12:10', '13:00-13:50', '13:50-14:40', '14:40-15:30')")
        ->orderBy('kd_ruang')
        ->get();

    if ($mergedJadwals->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada data untuk dicetak.');
    }

    $jadwals = $this->mergeJadwals($mergedJadwals);
    $pdf = PDF::loadView('jadwal.pdf', compact('jadwals', 'tahunAjaran', 'prodi'))
        ->setPaper('a4', 'landscape');

    return $pdf->download('jadwal_'.$prodi.'_'.$tahunAjaran->tahun_ajaran.'.pdf');
}
public function cetakJadwalSemua($tahunAjaranId)
{
    $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);
    $allJadwals = Jadwal::where('tahun_ajaran_id', $tahunAjaranId)
        ->where('validated', 1)
        ->with(['kelas.matkul', 'kelas.dosen1', 'kelas.dosen2', 'kelas.dosen3', 'kelas.dosen4', 'ruang'])
        ->get();

    if ($allJadwals->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada data untuk dicetak.');
    }

    $prodis = ['Informatika', 'Sipil', 'Arsitektur', 'Perencanaan Wilayah dan Kota'];
    $groupedJadwals = $this->groupAndMergeJadwals($allJadwals, $prodis);

    $pdf = PDF::loadView('jadwal.pdf_all', compact('groupedJadwals', 'tahunAjaran'))
        ->setPaper('a4', 'landscape');

    return $pdf->download('jadwal_semua_'.$tahunAjaran->tahun_ajaran.'.pdf');
}

private function groupAndMergeJadwals($jadwals, $prodis)
{
    $groupedJadwals = [];

    $umumJadwals = $jadwals->filter(function ($jadwal) {
        return $jadwal->kelas->prodi === 'Umum';
    });

    foreach ($prodis as $prodi) {
        $specificJadwals = $jadwals->filter(function ($jadwal) use ($prodi) {
            return $jadwal->kelas->prodi === $prodi;
        });

        $combinedJadwals = $specificJadwals->merge($umumJadwals);
        $mergedJadwals = $this->mergeJadwals3($combinedJadwals);

        $groupedJadwals[$prodi] = $mergedJadwals;
    }

    return $groupedJadwals;
}

private function mergeJadwals3($jadwals)
{
    $merged = [];
    $temp = [];

    foreach ($jadwals as $jadwal) {
        $key = $jadwal->hari . '-' . $jadwal->kelas->matkul->kd_matkul . '-' . $jadwal->kelas->matkul->nm_matkul . '-' . ($jadwal->kelas->dosen1 ? $jadwal->kelas->dosen1->nm_dosen : '') . '-' . $jadwal->ruang->kd_ruang;
        if (!isset($temp[$key])) {
            $temp[$key] = [
                'hari' => $jadwal->hari,
                'jam' => [$jadwal->jam],
                'kelas' => $jadwal->kelas,
                'ruang' => $jadwal->ruang,
                'validated' => $jadwal->validated
            ];
        } else {
            $temp[$key]['jam'][] = $jadwal->jam;
        }
    }

    foreach ($temp as $key => $value) {
        $startTime = explode('-', $value['jam'][0])[0];
        $endTime = explode('-', $value['jam'][count($value['jam']) - 1])[1];
        $jamRange = $startTime . ' - ' . $endTime;
        $value['jam'] = $jamRange;
        $merged[] = $value;
    }

    usort($merged, function($a, $b) {
        $hariOrder = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat"];
        $aHariIndex = array_search($a['hari'], $hariOrder);
        $bHariIndex = array_search($b['hari'], $hariOrder);

        if ($aHariIndex !== $bHariIndex) {
            return $aHariIndex - $bHariIndex;
        }

        $aJam = strtotime(explode(' - ', $a['jam'])[0]);
        $bJam = strtotime(explode(' - ', $b['jam'])[0]);

        if ($aJam !== $bJam) {
            return $aJam - $bJam;
        }

        return strcmp($a['ruang']->kd_ruang, $b['ruang']->kd_ruang);
    });

    return $merged;
}

public function prodi($prodi)
{
    $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();
    $mergedJadwals = Jadwal::where(function ($query) use ($prodi) {
        $query->where('prodi', $prodi)
              ->orWhere('prodi', 'Umum');
    })
    ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
    ->where('validated', 1)
    ->with(['kelas.matkul', 'kelas.dosen1', 'kelas.dosen2', 'kelas.dosen3', 'kelas.dosen4', 'ruang'])
    ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')")
    ->orderByRaw("FIELD(jam, '08:00-08:50', '08:50-09:40', '09:40-10:30', '10:30-11:20', '11:20-12:10', '13:00-13:50', '13:50-14:40', '14:40-15:30')")
    ->orderBy('kd_ruang')
    ->get();

    if ($mergedJadwals->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada data jadwal.');
    }

    $jadwals = $this->mergeJadwals($mergedJadwals);
    $tahunAjaran = $tahunAjaranAktif->tahun_ajaran;

    return view('jadwal', compact('jadwals', 'prodi', 'tahunAjaran'));
}

public function downloadJadwalProdi($prodi)
{
    $tahunAjaran = TahunAjaran::getActiveTahunAjaran();
    $mergedJadwals = Jadwal::where(function ($query) use ($prodi) {
        $query->where('prodi', $prodi)
              ->orWhere('prodi', 'Umum');
    })
    ->where('tahun_ajaran_id', $tahunAjaran->id)
    ->where('validated', 1)
    ->with(['kelas.matkul', 'kelas.dosen1', 'kelas.dosen2', 'kelas.dosen3', 'kelas.dosen4', 'ruang'])
    ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')")
    ->orderByRaw("FIELD(jam, '08:00-08:50', '08:50-09:40', '09:40-10:30', '10:30-11:20', '11:20-12:10', '13:00-13:50', '13:50-14:40', '14:40-15:30')")
    ->orderBy('kd_ruang')
    ->get();

    if ($mergedJadwals->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada data jadwal.');
    }

    $jadwals = $this->mergeJadwals($mergedJadwals);
    $pdf = PDF::loadView('jadwal.pdf', compact('jadwals', 'prodi', 'tahunAjaran'))
              ->setPaper('a4', 'landscape');
    return $pdf->download('jadwal_'.$prodi.'_'.$tahunAjaran->tahun_ajaran.'.pdf');
}
}

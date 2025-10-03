<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Berkas;
use App\Models\LogActivity;
use App\Models\Penilaian;
use App\Models\Peserta;
use App\Models\TahunSeleksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        if ($role == 'admin') {
            $periodeAktif = TahunSeleksi::where('is_active', true)->first();
            
            $statistik = [
                'jumlahPanitia' => User::where('role', 'panitia')->count(),
                'jumlahJuri' => User::where('role', 'juri')->count(),
                'periodeAktif' => $periodeAktif,
                'tahapanAktif' => optional($periodeAktif)->status ?? 'N/A',
                'pesertaAktif' => $periodeAktif ? Peserta::where('tahun_seleksi_id', $periodeAktif->id)->count() : 0,
                'logs' => LogActivity::with('user')->latest()->take(15)->get()
            ];

            return view('panel.dashboard.admin', ['statistik' => $statistik]);
        }

        if ($role == 'panitia') {
            $periodeAktif = TahunSeleksi::where('is_active', true)->first();
            $statistik = [];
            $chartData = [];
            $pesertaSiapVerifikasi = collect();
            $pesertaBelumLengkapBerkas = collect();
            $pesertaPerluDinilaiCu = collect();

            if ($periodeAktif) {
                $pesertaDiPeriode = Peserta::where('tahun_seleksi_id', $periodeAktif->id);
                $jumlahPeserta = $pesertaDiPeriode->count();
                $pesertaIds = $pesertaDiPeriode->pluck('id');
                $jumlahJuri = User::where('role', 'juri')->count();
                $jumlahTerverifikasi = $pesertaDiPeriode->clone()->where('status_verifikasi', 'diverifikasi')->count();
                $jumlahJuriGk = User::where('role', 'juri')->where('jenis_juri', 'GK')->count();
                $jumlahJuriBi = User::where('role', 'juri')->where('jenis_juri', 'BI')->count();
                
                $progresGkSelesai = Penilaian::whereIn('peserta_id', $pesertaIds)
                    ->whereNotNull('total_skor_gk')
                    ->whereHas('juri', fn($q) => $q->where('jenis_juri', 'GK'))
                    ->count();

                $progresBiSelesai = Penilaian::whereIn('peserta_id', $pesertaIds)
                    ->whereNotNull('total_skor_bi')
                    ->whereHas('juri', fn($q) => $q->where('jenis_juri', 'BI'))
                    ->count();
                
                $statistik = [
                    'jumlahPeserta' => $jumlahPeserta,
                    'jumlahJuri' => User::where('role', 'juri')->count(), 
                    'jumlahTerverifikasi' => $jumlahTerverifikasi,
                    'progresCu' => Berkas::whereIn('peserta_id', $pesertaIds)->where('jenis_berkas', 'CU')->where('skor', '>', 0)->distinct('peserta_id')->count(),
                    'progresGk' => "$progresGkSelesai dari " . ($jumlahTerverifikasi * $jumlahJuriGk),
                    'progresBi' => "$progresBiSelesai dari " . ($jumlahTerverifikasi * $jumlahJuriBi),
                ];
                
                if ($jumlahTerverifikasi > 0) {
                    $avgScores = Peserta::where('tahun_seleksi_id', $periodeAktif->id)
                                        ->where('status_verifikasi', 'diverifikasi')
                                        ->select(
                                            DB::raw('AVG(total_skor_cu) as avg_cu'),
                                            DB::raw('AVG(total_skor_gk) as avg_gk'),
                                            DB::raw('AVG(total_skor_bi) as avg_bi')
                                        )->first();

                    $chartData = [
                        'categories' => ['Capaian Unggulan (CU)', 'Gagasan Kreatif (GK)', 'Bahasa Inggris (BI)'],
                        'series' => [
                            round($avgScores->avg_cu ?? 0, 2),
                            round($avgScores->avg_gk ?? 0, 2),
                            round($avgScores->avg_bi ?? 0, 2)
                        ]
                    ];
                } else {
                     $chartData = [
                        'categories' => ['Capaian Unggulan (CU)', 'Gagasan Kreatif (GK)', 'Bahasa Inggris (BI)'],
                        'series' => [0, 0, 0]
                    ];
                }
                
                if ($jumlahPeserta > 0) {
                    $semuaPeserta = $pesertaDiPeriode->clone()->get();
                    $pesertaSiapVerifikasi = $semuaPeserta->filter(fn($p) => $p->status_verifikasi == 'menunggu' && $p->berkas_lengkap)->take(5);
                    $pesertaBelumLengkapBerkas = $semuaPeserta->filter(fn($p) => !$p->berkas_lengkap)->take(5);
                    
                    $pesertaPerluDinilaiCu = Peserta::where('tahun_seleksi_id', $periodeAktif->id)
                        ->where('status_verifikasi', 'diverifikasi')
                        ->whereDoesntHave('berkas', function ($query) {
                            $query->where('jenis_berkas', 'CU')->where('status_penilaian', 'final');
                        })
                        ->latest()->take(5)->get();
                }
            } else {
                 $statistik = [
                    'jumlahPeserta' => 0, 'jumlahJuri' => User::where('role', 'juri')->count(),
                    'jumlahTerverifikasi' => 0, 'progresCu' => 0,
                    'progresGk' => '0 dari 0', 'progresBi' => '0 dari 0',
                ];
                 $chartData = [
                    'categories' => ['Capaian Unggulan (CU)', 'Gagasan Kreatif (GK)', 'Bahasa Inggris (BI)'],
                    'series' => [0, 0, 0]
                ];
            }

            return view('panel.dashboard.panitia', [
                'periodeAktif' => $periodeAktif,
                'statistik' => $statistik,
                'chartData' => $chartData,
                'pesertaSiapVerifikasi' => $pesertaSiapVerifikasi,
                'pesertaBelumLengkapBerkas' => $pesertaBelumLengkapBerkas,
                'pesertaPerluDinilaiCu' => $pesertaPerluDinilaiCu,
            ]);
        }

        if ($role == 'juri') {
            $periodeAktif = TahunSeleksi::where('is_active', true)->first();
            $statistik = ['totalPeserta' => 0, 'perluDinilai' => 0, 'selesaiDinilai' => 0];
            $pesertas = collect();
            $penilaianLocked = true;
            $jenisJuri = $user->jenis_juri; // Mengambil spesialisasi juri yang sedang login

            if ($periodeAktif && $periodeAktif->status == 'penilaian') {
                $penilaianLocked = false;
                $pesertas = Peserta::where('tahun_seleksi_id', $periodeAktif->id)
                    ->where('status_verifikasi', 'diverifikasi')
                    ->orderBy('nama_lengkap')
                    ->get();
                
                foreach ($pesertas as $peserta) {
                    Penilaian::firstOrCreate(['peserta_id' => $peserta->id, 'juri_id' => $user->id]);
                }

                $pesertas->load(['penilaians' => fn($q) => $q->where('juri_id', $user->id)]);
                
                $statistik['totalPeserta'] = $pesertas->count();

                // Menghitung statistik penilaian berdasarkan spesialisasi juri.
                if ($jenisJuri === 'GK') {
                    $statistik['selesaiDinilai'] = $pesertas->filter(function ($p) {
                        $penilaian = $p->penilaians->first();
                        return $penilaian && $penilaian->total_skor_gk !== null;
                    })->count();
                } elseif ($jenisJuri === 'BI') {
                    $statistik['selesaiDinilai'] = $pesertas->filter(function ($p) {
                        $penilaian = $p->penilaians->first();
                        return $penilaian && $penilaian->total_skor_bi !== null;
                    })->count();
                }
                
                $statistik['perluDinilai'] = $statistik['totalPeserta'] - $statistik['selesaiDinilai'];
            }

            return view('panel.dashboard.juri', [
                'pesertas' => $pesertas,
                'statistik' => $statistik,
                'penilaianLocked' => $penilaianLocked,
                'periodeAktif' => $periodeAktif,
                'jenisJuri' => $jenisJuri, // Mengirim data spesialisasi ke view
            ]);
        }

        return redirect('/');
    }
}
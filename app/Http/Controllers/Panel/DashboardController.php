<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Berkas;
use App\Models\Penilaian;
use App\Models\Peserta;
use App\Models\TahunSeleksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogActivity;

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
                
                $progresGkSelesai = Penilaian::whereIn('peserta_id', $pesertaIds)->whereNotNull('total_skor_gk')->count();
                $progresBiSelesai = Penilaian::whereIn('peserta_id', $pesertaIds)->whereNotNull('total_skor_bi')->count();
                
                $statistik = [
                    'jumlahPeserta' => $jumlahPeserta,
                    'jumlahJuri' => $jumlahJuri,
                    'jumlahTerverifikasi' => $jumlahTerverifikasi,
                    'progresCu' => Berkas::whereIn('peserta_id', $pesertaIds)->where('jenis_berkas', 'CU')->where('skor', '>', 0)->distinct('peserta_id')->count(),
                    'progresGk' => "$progresGkSelesai dari " . ($jumlahTerverifikasi * $jumlahJuri),
                    'progresBi' => "$progresBiSelesai dari " . ($jumlahTerverifikasi * $jumlahJuri),
                ];
                
                $chartData = [ /* ... (tidak berubah) ... */ ];
                
                // Hanya jalankan query tugas jika ada peserta
                if($jumlahPeserta > 0) {
                    $semuaPeserta = $pesertaDiPeriode->clone()->get();
                    $pesertaSiapVerifikasi = $semuaPeserta->filter(fn($p) => $p->status_verifikasi == 'menunggu' && $p->berkas_lengkap)->take(5);
                    $pesertaBelumLengkapBerkas = $semuaPeserta->filter(fn($p) => !$p->berkas_lengkap)->take(5);
                    
                    // Query untuk peserta yang perlu dinilai CU
                    $pesertaPerluDinilaiCu = Peserta::where('tahun_seleksi_id', $periodeAktif->id)
                        ->where('status_verifikasi', 'diverifikasi')
                        ->whereDoesntHave('berkas', function ($query) {
                            $query->where('jenis_berkas', 'CU')->where('status_penilaian', 'final');
                        })
                        ->latest()->take(5)->get();
                }
            }

            return view('panel.dashboard.panitia', [
                'periodeAktif' => $periodeAktif, 'statistik' => $statistik, 'chartData' => $chartData,
                'pesertaSiapVerifikasi' => $pesertaSiapVerifikasi,
                'pesertaBelumLengkapBerkas' => $pesertaBelumLengkapBerkas,
                'pesertaPerluDinilaiCu' => $pesertaPerluDinilaiCu,
            ]);
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

                // Hanya jalankan query detail jika ada peserta
                if ($jumlahPeserta > 0) {
                    $pesertaIds = $pesertaDiPeriode->pluck('id');
                    $jumlahJuri = User::where('role', 'juri')->count();
                    $jumlahTerverifikasi = $pesertaDiPeriode->clone()->where('status_verifikasi', 'diverifikasi')->count();
                    $totalPenilaianDibutuhkan = $jumlahTerverifikasi * $jumlahJuri;
                    if ($totalPenilaianDibutuhkan == 0) $totalPenilaianDibutuhkan = 1;
                    $progresGkSelesai = Penilaian::whereIn('peserta_id', $pesertaIds)->whereNotNull('total_skor_gk')->count();
                    $progresBiSelesai = Penilaian::whereIn('peserta_id', $pesertaIds)->whereNotNull('total_skor_bi')->count();
                    
                    $statistik = [
                        'jumlahPeserta' => $jumlahPeserta,
                        'jumlahJuri' => $jumlahJuri,
                        'jumlahTerverifikasi' => $jumlahTerverifikasi,
                        'progresCu' => Berkas::whereIn('peserta_id', $pesertaIds)->where('jenis_berkas', 'CU')->where('skor', '>', 0)->distinct('peserta_id')->count(),
                        'progresGk' => "$progresGkSelesai dari " . ($jumlahTerverifikasi * $jumlahJuri),
                        'progresBi' => "$progresBiSelesai dari " . ($jumlahTerverifikasi * $jumlahJuri),
                    ];

                    $chartData = [ /* ... (tidak berubah) ... */ ];
                    
                    $semuaPeserta = $pesertaDiPeriode->clone()->get();
                    $pesertaSiapVerifikasi = $semuaPeserta->filter(fn($p) => $p->status_verifikasi == 'menunggu' && $p->berkas_lengkap)->take(5);
                    $pesertaBelumLengkapBerkas = $semuaPeserta->filter(fn($p) => !$p->berkas_lengkap)->take(5);
                    $pesertaPerluDinilaiCu = $semuaPeserta->filter(fn($p) => $p->status_verifikasi == 'diverifikasi' && !$p->berkas()->where('jenis_berkas', 'CU')->where('skor', '>', 0)->exists())->take(5);
                } else {
                    // Jika tidak ada peserta, set statistik default
                     $statistik = [
                        'jumlahPeserta' => 0, 'jumlahJuri' => User::where('role', 'juri')->count(),
                        'jumlahTerverifikasi' => 0, 'progresCu' => 0,
                        'progresGk' => '0 dari 0', 'progresBi' => '0 dari 0',
                    ];
                }
            }

            return view('panel.dashboard.panitia', [
                'periodeAktif' => $periodeAktif, 'statistik' => $statistik, 'chartData' => $chartData,
                'pesertaSiapVerifikasi' => $pesertaSiapVerifikasi,
                'pesertaBelumLengkapBerkas' => $pesertaBelumLengkapBerkas,
                'pesertaPerluDinilaiCu' => $pesertaPerluDinilaiCu,
            ]);
        }

        if ($role == 'juri') {
            // ... (logika juri tidak berubah)
            $periodeAktif = TahunSeleksi::where('is_active', true)->first();
            $statistik = ['totalPeserta' => 0, 'perluDinilai' => 0, 'selesaiDinilai' => 0];
            $pesertas = collect();
            $penilaianLocked = true;
            if ($periodeAktif && $periodeAktif->status == 'penilaian') {
                $penilaianLocked = false;
                $pesertas = Peserta::where('tahun_seleksi_id', $periodeAktif->id)->where('status_verifikasi', 'diverifikasi')->orderBy('nama_lengkap')->get();
                foreach ($pesertas as $peserta) {
                    Penilaian::firstOrCreate(['peserta_id' => $peserta->id, 'juri_id' => $user->id]);
                }
                $pesertas->load(['penilaians' => fn($q) => $q->where('juri_id', $user->id)]);
                $statistik['totalPeserta'] = $pesertas->count();
                $statistik['selesaiDinilai'] = $pesertas->filter(function($p) { $penilaian = $p->penilaians->first(); return $penilaian && ($penilaian->total_skor_gk !== null && $penilaian->total_skor_bi !== null); })->count();
                $statistik['perluDinilai'] = $statistik['totalPeserta'] - $statistik['selesaiDinilai'];
            }
            return view('panel.dashboard.juri', ['pesertas' => $pesertas, 'statistik' => $statistik, 'penilaianLocked' => $penilaianLocked, 'periodeAktif' => $periodeAktif]);
        }

        return redirect('/');
    }
}

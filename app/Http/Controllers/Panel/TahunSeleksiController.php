<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\TahunSeleksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TahunSeleksiController extends Controller
{
    private function adaPeriodeBerjalan()
    {
        return TahunSeleksi::where('status', '!=', 'selesai')->exists();
    }

    public function index()
    {
        $periodes = TahunSeleksi::orderBy('tahun', 'desc')->get();
        $periodeAktif = $periodes->where('is_active', true)->first();
        $jumlahPesertaDiTahunAktif = 0;
        if ($periodeAktif) {
            $jumlahPesertaDiTahunAktif = Peserta::where('tahun_seleksi_id', $periodeAktif->id)->count();
        }
        $bisaTambahPeriodeBaru = !$this->adaPeriodeBerjalan();
        $tahunTerakhir = TahunSeleksi::max('tahun');
        $tahunPlaceholder = $tahunTerakhir ? $tahunTerakhir + 1 : date('Y');

        return view('panel.tahun-seleksi.index', [
            'periodes' => $periodes, 'periodeAktif' => $periodeAktif,
            'jumlahPesertaDiTahunAktif' => $jumlahPesertaDiTahunAktif,
            'bisaTambahPeriodeBaru' => $bisaTambahPeriodeBaru,
            'tahunPlaceholder' => $tahunPlaceholder
        ]);
    }

    public function store(Request $request)
    {
        if ($this->adaPeriodeBerjalan()) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Tidak dapat menambah periode baru. Selesaikan dulu periode yang sedang berjalan.']);
        }
        $tahunTerakhir = TahunSeleksi::max('tahun');
        $validationRules = [ 'tahun' => ['required', 'integer', 'digits:4', 'unique:tahun_seleksis,tahun'], ];
        if ($tahunTerakhir) {
            $validationRules['tahun'][] = Rule::in([$tahunTerakhir + 1]);
        }
        $request->validate($validationRules, [ 'tahun.in' => 'Tahun harus berurutan. Tahun yang bisa ditambahkan sekarang adalah ' . ($tahunTerakhir ? $tahunTerakhir + 1 : 'tahun pertama') . '.', ]);
        TahunSeleksi::create(['tahun' => $request->tahun, 'is_active' => false, 'status' => 'pendaftaran']);
        return redirect()->route('admin.tahun-seleksi.index')->with('notification', ['type' => 'success', 'message' => 'Periode baru berhasil ditambahkan. Silakan klik "Jadikan Aktif".']);
    }

    public function setActive(TahunSeleksi $tahun_seleksi)
    {
        DB::transaction(function () use ($tahun_seleksi) {
            TahunSeleksi::where('is_active', true)->update(['is_active' => false]);
            $tahun_seleksi->update(['is_active' => true]);
        });
        return redirect()->route('admin.tahun-seleksi.index')->with('notification', ['type' => 'success', 'message' => "Periode tahun {$tahun_seleksi->tahun} berhasil diaktifkan!"]);
    }

    public function startPenilaian(TahunSeleksi $tahunSeleksi)
    {
        // Validasi: Pastikan ada peserta sebelum memulai penilaian.
        $jumlahPeserta = Peserta::where('tahun_seleksi_id', $tahunSeleksi->id)->count();
        if ($jumlahPeserta == 0) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => "Gagal! Tidak dapat memulai penilaian karena belum ada peserta yang terdaftar di periode ini."]);
        }
        
        $pesertaMenunggu = Peserta::where('tahun_seleksi_id', $tahunSeleksi->id)->where('status_verifikasi', 'menunggu')->count();
        if ($pesertaMenunggu > 0) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => "Gagal! Masih ada {$pesertaMenunggu} peserta yang statusnya 'Menunggu'. Harap validasi atau tolak semua peserta terlebih dahulu."]);
        }

        if ($tahunSeleksi->is_active) {
            $tahunSeleksi->update(['status' => 'penilaian']);
            return redirect()->back()->with('notification', ['type' => 'info', 'message' => 'Periode ' . $tahunSeleksi->tahun . ' telah memasuki tahap penilaian.']);
        }
        return redirect()->back()->withErrors(['error' => 'Hanya periode aktif yang bisa diubah tahapannya.']);
    }

    public function endPeriod(TahunSeleksi $tahunSeleksi)
    {
        if ($tahunSeleksi->is_active && $tahunSeleksi->status == 'penilaian') {
            $jumlahJuriGk = User::where('role', 'juri')->where('jenis_juri', 'GK')->count();
            $jumlahJuriBi = User::where('role', 'juri')->where('jenis_juri', 'BI')->count();

            $pesertaTerverifikasi = Peserta::where('tahun_seleksi_id', $tahunSeleksi->id)
                ->where('status_verifikasi', 'diverifikasi')
                ->get();
            
            // Validasi: Pastikan ada peserta yang lolos verifikasi.
            if ($pesertaTerverifikasi->isEmpty()) {
                return redirect()->back()->with('notification', ['type' => 'danger', 'message' => "Gagal! Tidak dapat mengakhiri periode karena tidak ada peserta yang lolos verifikasi untuk dinilai."]);
            }

            foreach ($pesertaTerverifikasi as $peserta) {
                $penilaianCuBelumFinal = $peserta->berkas()->where('jenis_berkas', 'CU')->where('status_penilaian', '!=', 'final')->count();
                if ($penilaianCuBelumFinal > 0) {
                    return redirect()->back()->with('notification', ['type' => 'danger', 'message' => "Gagal! Peserta '{$peserta->nama_lengkap}' masih memiliki {$penilaianCuBelumFinal} CU yang belum difinalisasi oleh Panitia."]);
                }
                $penilaianGkSelesai = $peserta->penilaians()->whereNotNull('total_skor_gk')->whereHas('juri', fn($q) => $q->where('jenis_juri', 'GK'))->count();
                if ($penilaianGkSelesai < $jumlahJuriGk) {
                    return redirect()->back()->with('notification', ['type' => 'danger', 'message' => "Gagal! Peserta {$peserta->nama_lengkap} baru dinilai oleh {$penilaianGkSelesai} dari {$jumlahJuriGk} juri GK yang seharusnya menilai."
]);
                }
                $penilaianBiSelesai = $peserta->penilaians()->whereNotNull('total_skor_bi')->whereHas('juri', fn($q) => $q->where('jenis_juri', 'BI'))->count();
                if ($penilaianBiSelesai < $jumlahJuriBi) {
                    return redirect()->back()->with('notification', ['type' => 'danger', 'message' => "Gagal! Peserta '{$peserta->nama_lengkap}' baru dinilai BI oleh {$penilaianBiSelesai} dari {$jumlahJuriBi} juri BI."]);
                }
            }

            $this->hitungSkorFinal($tahunSeleksi->id);
            $tahunSeleksi->update(['status' => 'selesai', 'is_active' => false]);
            return redirect()->back()->with('notification', ['type' => 'warning', 'message' => 'Periode ' . $tahunSeleksi->tahun . ' telah berhasil diakhiri dan diarsipkan.']);
        }
        return redirect()->back()->withErrors(['error' => 'Gagal mengakhiri periode.']);
    }
    
    private function hitungSkorFinal($tahunSeleksiId)
    {
        $pesertas = Peserta::where('tahun_seleksi_id', $tahunSeleksiId)->where('status_verifikasi', 'diverifikasi')->get();
        foreach ($pesertas as $peserta) {
            $totalCu = $peserta->berkas()->where('jenis_berkas', 'CU')->sum('skor');
            $avgGk = $peserta->penilaians()->whereHas('juri', fn($q) => $q->where('jenis_juri', 'GK'))->avg('total_skor_gk');
            $avgBi = $peserta->penilaians()->whereHas('juri', fn($q) => $q->where('jenis_juri', 'BI'))->avg('total_skor_bi');
            $skorAkhir = (($totalCu ?? 0) * 0.45) + (($avgGk ?? 0) * 0.35) + (($avgBi ?? 0) * 0.20);
            $peserta->update([ 'total_skor_cu' => $totalCu ?? 0, 'total_skor_gk' => $avgGk ?? 0, 'total_skor_bi' => $avgBi ?? 0, 'skor_akhir' => $skorAkhir ]);
        }
    }
}
<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\TahunSeleksi;
use App\Models\Peserta;
use App\Models\User; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return view('panel.tahun-seleksi.index', [
            'periodes' => $periodes,
            'periodeAktif' => $periodeAktif,
            'jumlahPesertaDiTahunAktif' => $jumlahPesertaDiTahunAktif,
            'bisaTambahPeriodeBaru' => $bisaTambahPeriodeBaru
        ]);
    }

    public function store(Request $request)
    {
        if ($this->adaPeriodeBerjalan()) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Tidak dapat menambah periode baru. Selesaikan dulu periode yang sedang berjalan.']);
        }
        $request->validate(['tahun' => 'required|integer|digits:4|unique:tahun_seleksis,tahun',]);
        TahunSeleksi::create([
            'tahun' => $request->tahun,
            'is_active' => false,
            'status' => 'pendaftaran',
        ]);
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

    /**
     * Mengakhiri dan mengarsipkan periode.
     */
    public function endPeriod(TahunSeleksi $tahunSeleksi)
    {
        if ($tahunSeleksi->is_active && $tahunSeleksi->status == 'penilaian') {

            // !! LOGIKA VALIDASI BARU YANG LEBIH KETAT !!
            $jumlahJuri = User::where('role', 'juri')->count();
            $pesertaTerverifikasi = Peserta::where('tahun_seleksi_id', $tahunSeleksi->id)
                ->where('status_verifikasi', 'diverifikasi')
                ->get();

            foreach ($pesertaTerverifikasi as $peserta) {
                // Cek penilaian GK
                $penilaianGkSelesai = $peserta->penilaians()->whereNotNull('total_skor_gk')->count();
                if ($penilaianGkSelesai < $jumlahJuri) {
                    $pesan = "Gagal! Peserta '{$peserta->nama_lengkap}' baru dinilai GK oleh {$penilaianGkSelesai} dari {$jumlahJuri} juri.";
                    return redirect()->back()->with('notification', ['type' => 'danger', 'message' => $pesan]);
                }

                // Cek penilaian BI
                $penilaianBiSelesai = $peserta->penilaians()->whereNotNull('total_skor_bi')->count();
                if ($penilaianBiSelesai < $jumlahJuri) {
                    $pesan = "Gagal! Peserta '{$peserta->nama_lengkap}' baru dinilai BI oleh {$penilaianBiSelesai} dari {$jumlahJuri} juri.";
                    return redirect()->back()->with('notification', ['type' => 'danger', 'message' => $pesan]);
                }
            }
            // !! AKHIR DARI LOGIKA VALIDASI BARU !!

            // Lakukan perhitungan skor akhir sekali lagi untuk finalisasi
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
            $avgGk = $peserta->penilaians()->avg('total_skor_gk');
            $avgBi = $peserta->penilaians()->avg('total_skor_bi');
            $skorAkhir = (($totalCu ?? 0) * 0.45) + (($avgGk ?? 0) * 0.35) + (($avgBi ?? 0) * 0.20);
            $peserta->update(['total_skor_cu' => $totalCu ?? 0, 'total_skor_gk' => $avgGk ?? 0, 'total_skor_bi' => $avgBi ?? 0, 'skor_akhir' => $skorAkhir]);
        }
    }
}

<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Berkas;
use App\Models\Peserta;
use App\Models\TahunSeleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenilaianCuController extends Controller
{
    // Rubrik statis untuk klasifikasi Capaian Unggulan (CU).
    private $klasifikasiCu = [
        'Kompetisi' => ['Juara-1 Perorangan', 'Juara-2 Perorangan', 'Juara-3 Perorangan', 'Kategori Juara Perorangan', 'Juara-1 Beregu', 'Juara-2 Beregu', 'Juara-3 Beregu', 'Juara Kategori Beregu'],
        'Pengakuan' => ['Pelatih/Wasit/Juri berlisensi', 'Pelatih/Wasit/Juri tidak berlisensi', 'Nara sumber/pembicara', 'Moderator', 'Lainnya (Pengakuan)'],
        'Penghargaan' => ['Tanda Jasa', 'Penerima Hibah kompetisi', 'Medali Emas (batas nilai)', 'Medali Perak (batas nilai)', 'Medali Perunggu (batas nilai)', 'Piagam Partisipasi', 'Lainnya (Penghargaan)'],
        'Karir Organisasi' => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Satu tingkat dibawah pengurus harian'],
        'Hasil Karya' => ['Patent', 'Patent Sederhana', 'Hak Cipta', 'Buku ber-ISBN penulis utama', 'Buku ber-ISBN penulis kedua dst', 'Penulis Utama/Korespondensi Jurnal', 'Penulis Kedua Jurnal'],
        'Pemberdayaan atau Aksi Kemanusiaan' => ['Pemrakarsa / Pendiri', 'Koordinator Relawan', 'Relawan'],
        'Kewirausahaan' => ['Wirausaha']
    ];

    // Tabel skor statis [min, max] sebagai aturan bisnis utama penilaian CU.
    private $skorTabelRange = [
        'Kompetisi' => [
            'Juara-1 Perorangan' => ['Internasional' => [40, 50], 'Regional' => [30, 40], 'Nasional' => [20, 30], 'Provinsi' => [20, 20]],
            'Juara-2 Perorangan' => ['Internasional' => [35, 45], 'Regional' => [25, 35], 'Nasional' => [15, 25], 'Provinsi' => [15, 15]],
            'Juara-3 Perorangan' => ['Internasional' => [30, 40], 'Regional' => [20, 30], 'Nasional' => [10, 20], 'Provinsi' => [10, 10]],
            'Kategori Juara Perorangan' => ['Internasional' => [24, 32], 'Regional' => [16, 24], 'Nasional' => [8, 16], 'Provinsi' => [8, 8]],
            'Juara-1 Beregu' => ['Internasional' => [30, 40], 'Regional' => [20, 30], 'Nasional' => [10, 20], 'Provinsi' => [10, 10]],
            'Juara-2 Beregu' => ['Internasional' => [25, 35], 'Regional' => [15, 25], 'Nasional' => [7, 15], 'Provinsi' => [7, 7]],
            'Juara-3 Beregu' => ['Internasional' => [20, 30], 'Regional' => [10, 20], 'Nasional' => [6, 10], 'Provinsi' => [6, 6]],
            'Juara Kategori Beregu' => ['Internasional' => [16, 24], 'Regional' => [10, 16], 'Nasional' => [5, 10], 'Provinsi' => [5, 5]],
        ],
        'Pengakuan' => [
            'Pelatih/Wasit/Juri berlisensi' => ['Internasional' => [50, 50], 'Regional' => [40, 40], 'Nasional' => [30, 30], 'Provinsi' => [20, 20]],
            'Pelatih/Wasit/Juri tidak berlisensi' => ['Internasional' => [25, 25], 'Regional' => [20, 20], 'Nasional' => [15, 15], 'Provinsi' => [10, 10]],
            'Nara sumber/pembicara' => ['Internasional' => [25, 25], 'Regional' => [20, 20], 'Nasional' => [15, 15], 'Provinsi' => [10, 10]],
            'Moderator' => ['Internasional' => [20, 20], 'Regional' => [15, 15], 'Nasional' => [10, 10], 'Provinsi' => [5, 5]],
            'Lainnya (Pengakuan)' => ['Internasional' => [20, 20], 'Regional' => [15, 15], 'Nasional' => [10, 10], 'Provinsi' => [5, 5]],
        ],
        'Penghargaan' => [
            'Tanda Jasa' => ['Internasional' => [50, 50], 'Regional' => [40, 40], 'Nasional' => [30, 30], 'Provinsi' => [20, 20]],
            'Penerima Hibah kompetisi' => ['Internasional' => [40, 40], 'Regional' => [30, 30], 'Nasional' => [20, 20], 'Provinsi' => [10, 10]],
            'Medali Emas (batas nilai)' => ['Internasional' => [30, 30], 'Regional' => [20, 20], 'Nasional' => [10, 10], 'Provinsi' => [5, 5]],
            'Medali Perak (batas nilai)' => ['Internasional' => [25, 25], 'Regional' => [15, 15], 'Nasional' => [7, 7], 'Provinsi' => [3, 3]],
            'Medali Perunggu (batas nilai)' => ['Internasional' => [20, 20], 'Regional' => [10, 10], 'Nasional' => [5, 5], 'Provinsi' => [2, 2]],
            'Piagam Partisipasi' => ['Internasional' => [10, 10], 'Regional' => [5, 5], 'Nasional' => [3, 3], 'Provinsi' => [1, 1]],
            'Lainnya (Penghargaan)' => ['Internasional' => [10, 10], 'Regional' => [5, 5], 'Nasional' => [3, 3], 'Provinsi' => [1, 1]],
        ],
        'Karir Organisasi' => [
            'Ketua' => ['Internasional' => [50, 50], 'Regional' => [40, 40], 'Nasional' => [30, 30], 'Provinsi' => [20, 20], 'PT' => [10, 10]],
            'Wakil Ketua' => ['Internasional' => [45, 45], 'Regional' => [35, 35], 'Nasional' => [25, 25], 'Provinsi' => [15, 15], 'PT' => [8, 8]],
            'Sekretaris' => ['Internasional' => [40, 40], 'Regional' => [30, 30], 'Nasional' => [20, 20], 'Provinsi' => [10, 10], 'PT' => [6, 6]],
            'Bendahara' => ['Internasional' => [40, 40], 'Regional' => [30, 30], 'Nasional' => [20, 20], 'Provinsi' => [10, 10], 'PT' => [6, 6]],
            'Satu tingkat dibawah pengurus harian' => ['Internasional' => [30, 30], 'Regional' => [20, 20], 'Nasional' => [10, 10], 'Provinsi' => [5, 5], 'PT' => [2, 2]],
        ],
        'Hasil Karya' => [
            'Patent' => ['Nasional' => [40, 50]],
            'Patent Sederhana' => ['Nasional' => [10, 30]],
            'Hak Cipta' => ['Nasional' => [10, 30]],
            'Buku ber-ISBN penulis utama' => ['Nasional' => [30, 30]],
            'Buku ber-ISBN penulis kedua dst' => ['Nasional' => [20, 20]],
            'Penulis Utama/Korespondensi Jurnal' => ['Internasional' => [50, 50], 'Nasional' => [30, 30]],
            'Penulis Kedua Jurnal' => ['Internasional' => [30, 30], 'Nasional' => [20, 20]],
        ],
        'Pemberdayaan atau Aksi Kemanusiaan' => [
            'Pemrakarsa / Pendiri' => ['Internasional' => [50, 50], 'Regional' => [40, 40], 'Nasional' => [30, 30], 'Provinsi' => [20, 20], 'PT' => [10, 10]],
            'Koordinator Relawan' => ['Internasional' => [35, 35], 'Regional' => [25, 25], 'Nasional' => [15, 15], 'Provinsi' => [10, 10], 'PT' => [5, 5]],
            'Relawan' => ['Internasional' => [25, 25], 'Regional' => [15, 15], 'Nasional' => [10, 10], 'Provinsi' => [5, 5], 'PT' => [3, 3]],
        ],
        'Kewirausahaan' => [
            'Wirausaha' => ['Internasional' => [50, 50], 'Regional' => [40, 40], 'Nasional' => [30, 30], 'Provinsi' => [20, 20], 'PT' => [10, 10]],
        ],
    ];

    public function index(Request $request)
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        
        $viewData = [
            'pesertas' => collect(),
            'selectedPeserta' => null,
            'berkasCu' => collect(),
            'klasifikasiCu' => $this->klasifikasiCu,
            'skorTabelRange' => $this->skorTabelRange,
            'periodeAktif' => $periodeAktif,
            'semuaCuFinal' => false,
            'penilaianLocked' => true,
            'lockMessage' => 'Tahap penilaian belum dibuka atau sudah selesai.',
        ];

        // Mencegah penilaian jika periode tidak aktif.
        if (!$periodeAktif || $periodeAktif->status != 'penilaian') {
            return view('panel.penilaian.capaian-unggulan', $viewData);
        }
        
        $viewData['penilaianLocked'] = false;
        $viewData['pesertas'] = Peserta::where('tahun_seleksi_id', $periodeAktif->id)
            ->where('status_verifikasi', 'diverifikasi')
            ->whereHas('berkas', fn($q) => $q->where('jenis_berkas', 'CU'))
            ->orderBy('nama_lengkap')->get();

        if ($request->query('peserta_id')) {
            $viewData['selectedPeserta'] = Peserta::find($request->query('peserta_id'));
            if ($viewData['selectedPeserta']) {
                $viewData['berkasCu'] = Berkas::where('peserta_id', $viewData['selectedPeserta']->id)
                    ->where('jenis_berkas', 'CU')->get();
                
                if ($viewData['berkasCu']->isNotEmpty()) {
                    $viewData['semuaCuFinal'] = $viewData['berkasCu']->every(fn ($berkas) => $berkas->status_penilaian == 'final');
                }
            }
        }

        return view('panel.penilaian.capaian-unggulan', $viewData);
    }

    public function store(Request $request)
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        if (!$periodeAktif || $periodeAktif->status != 'penilaian') {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Periode penilaian telah ditutup atau belum dimulai.']);
        }
        
        $request->validate(['klasifikasi' => 'required|array']);

        $isFinalisasi = $request->has('finalisasi');
        $pesertaId = null;

        // Validasi semua skor wajib diisi sebelum proses finalisasi/penguncian.
        if ($isFinalisasi) {
            foreach ($request->klasifikasi as $berkasId => $data) {
                if (empty($data['skor']) || (float)$data['skor'] <= 0) {
                    $berkas = Berkas::find($berkasId);
                    $namaBerkas = $berkas ? $berkas->nama_berkas : "Berkas ID {$berkasId}";
                    return redirect()->back()
                        ->withInput()
                        ->with('notification', ['type' => 'danger', 'message' => "Gagal mengunci! Skor untuk '{$namaBerkas}' belum diisi atau tidak valid."]);
                }
            }
        }

        foreach ($request->klasifikasi as $berkasId => $data) {
            $berkas = Berkas::find($berkasId);
            if (!$berkas) continue;

            if (!$pesertaId) $pesertaId = $berkas->peserta_id;
            
            // Lewati berkas yang sudah dikunci untuk mencegah penimpaan data.
            if ($berkas->status_penilaian == 'final') continue;

            $dataToUpdate = [
                'tingkat' => $data['tingkat'],
                'cu_bidang' => $data['bidang'],
                'cu_wujud' => $data['wujud'],
                'skor' => (float) $data['skor'],
            ];

            if ($isFinalisasi) {
                $dataToUpdate['status_penilaian'] = 'final';
            }
            
            $berkas->update($dataToUpdate);
        }

        $message = $isFinalisasi ? 'Penilaian Capaian Unggulan berhasil dikunci!' : 'Perubahan berhasil disimpan sebagai draf.';
        return redirect()->route('panel.penilaian.capaian-unggulan', ['peserta_id' => $pesertaId])
            ->with('notification', ['type' => 'success', 'message' => $message]);
    }

    private function getSkorRange($bidang, $wujud, $tingkat)
    {
        return $this->skorTabelRange[$bidang][$wujud][$tingkat] ?? [0, 0];
    }
}
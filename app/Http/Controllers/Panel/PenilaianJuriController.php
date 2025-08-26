<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peserta;
use App\Models\TahunSeleksi;
use App\Models\Penilaian;
use App\Models\Berkas;

class PenilaianJuriController extends Controller
{
    /**
     * Mengambil data umum penilaian.
     * Perbaikan utama ada di fungsi ini.
     */
    private function getPenilaianData(Request $request)
    {
        $juriId = Auth::id();
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        
        $data = [
            'pesertas' => collect(),
            'selectedPeserta' => null,
            'penilaian' => null,
            'penilaianLocked' => true,
        ];

        if ($periodeAktif && $periodeAktif->status == 'penilaian') {
            $data['penilaianLocked'] = false;

            $data['pesertas'] = Peserta::where('tahun_seleksi_id', $periodeAktif->id)
                ->where('status_verifikasi', 'diverifikasi') 
                ->orderBy('nama_lengkap')
                ->get();

            if ($request->query('peserta_id')) {
                $data['selectedPeserta'] = $data['pesertas']->find($request->query('peserta_id'));
                
                if ($data['selectedPeserta']) {
                    // !! PERBAIKAN DARI firstOrCreate() MENJADI where()->first() !!
                    // Ini memastikan kita selalu mendapat data terbaru dari database.
                    // Record penilaian sudah dijamin dibuat saat juri membuka dashboard.
                    $data['penilaian'] = Penilaian::where([
                        ['peserta_id', $data['selectedPeserta']->id],
                        ['juri_id', $juriId],
                    ])->first();
                }
            }
        }
        return $data;
    }

    public function gagasanKreatif(Request $request)
    {
        $data = $this->getPenilaianData($request);
        
        if (!$data['penilaianLocked'] && $data['selectedPeserta']) {
            $data['berkasGk'] = $data['selectedPeserta']->berkas()->where('jenis_berkas', 'NASKAH_GK')->first();
            
            $data['scoringRubric'] = [
                'Bagian 1: Penyajian (10%)' => [['key' => '1_1', 'label' => 'Penggunaan Bahasa', 'bobot' => 5],['key' => '1_2', 'label' => 'Kesesuaian Pengutipan', 'bobot' => 5],],
                'Bagian 2: Substansi (70%)' => [['key' => '2_1', 'label' => 'Fakta/gejala', 'bobot' => 8],['key' => '2_2', 'label' => 'Identifikasi masalah', 'bobot' => 8],['key' => '2_3', 'label' => 'Rumusan masalah', 'bobot' => 10],['key' => '2_4', 'label' => 'Akibat pembiaran', 'bobot' => 8],['key' => '2_5', 'label' => 'Solusi SMART', 'bobot' => 15],['key' => '2_6', 'label' => 'Dampak lanjutan', 'bobot' => 8],['key' => '2_7', 'label' => 'Langkah tindakan', 'bobot' => 8],['key' => '2_8', 'label' => 'Kendala & antisipasi', 'bobot' => 5],],
                'Bagian 3: Kualitas (20%)' => [['key' => '3_1', 'label' => 'Keunikan & Orisinalitas', 'bobot' => 10],['key' => '3_2', 'label' => 'Keterlaksanaan Gagasan', 'bobot' => 10],],
            ];
        }

        return view('panel.penilaian.gagasan-kreatif', $data);
    }

    public function storeGagasanKreatif(Request $request)
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        if (!$periodeAktif || $periodeAktif->status != 'penilaian') {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Periode penilaian telah ditutup atau belum dimulai.']);
        }

        $request->validate(['penilaian_id' => 'required|exists:penilaians,id', 'skor' => 'required|array', 'skor.*' => 'required|numeric|min:5|max:10', 'catatan_juri_gk' => 'nullable|string',]);
        $penilaian = Penilaian::find($request->penilaian_id);
        
        if (optional($penilaian)->status_penilaian_gk == 'final') {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Gagal! Penilaian GK untuk peserta ini sudah dikunci.']);
        }
        
        if (optional($penilaian)->juri_id != Auth::id()) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Anda tidak berhak mengubah penilaian ini.']);
        }

        $skorDetail = $request->skor;
        $totalSkor = ($skorDetail['1_1'] * 5) + ($skorDetail['1_2'] * 5) + ($skorDetail['2_1'] * 8) + ($skorDetail['2_2'] * 8) + ($skorDetail['2_3'] * 10) + ($skorDetail['2_4'] * 8) + ($skorDetail['2_5'] * 15) + ($skorDetail['2_6'] * 8) + ($skorDetail['2_7'] * 8) + ($skorDetail['2_8'] * 5) + ($skorDetail['3_1'] * 10) + ($skorDetail['3_2'] * 10);
        $catatanDetail = $request->has('catatan_gk') ? array_filter($request->catatan_gk) : null;
        
        $dataToUpdate = [
            'skor_gk_detail' => $skorDetail,
            'total_skor_gk' => $totalSkor / 10,
            'catatan_juri_gk' => $request->catatan_juri_gk,
            'catatan_gk_detail' => $catatanDetail,
        ];

        if ($request->has('finalisasi_gk')) {
            $dataToUpdate['status_penilaian_gk'] = 'final';
        }

        $penilaian->update($dataToUpdate);

        $message = $request->has('finalisasi_gk') ? 'Penilaian Gagasan Kreatif berhasil dikunci!' : 'Perubahan berhasil disimpan sebagai draf.';
        return redirect()->route('juri.penilaian.gk', ['peserta_id' => $penilaian->peserta_id])->with('notification', ['type' => 'success', 'message' => $message]);
    }

    public function bahasaInggris(Request $request)
    {   
        $data = $this->getPenilaianData($request);
        if (!$data['penilaianLocked'] && $data['selectedPeserta']) {
            $data['berkasSlideGk'] = $data['selectedPeserta']->berkas()->where('jenis_berkas', 'SLIDE_GK')->first();
            $data['scoringCriteria'] = [
                ['field' => 'content', 'label' => '1. Content (Isi Materi)', 'min' => 5, 'max' => 25],
                ['field' => 'accuracy', 'label' => '2. Accuracy (Tata Bahasa & Kosakata)', 'min' => 5, 'max' => 25],
                ['field' => 'fluency', 'label' => '3. Fluency (Kelancaran)', 'min' => 5, 'max' => 20],
                ['field' => 'pronunciation', 'label' => '4. Pronunciation (Pengucapan)', 'min' => 5, 'max' => 20],
                ['field' => 'performance', 'label' => '5. Overall Performance (Sikap & Gestur)', 'min' => 3, 'max' => 10]
            ];
        }
        return view('panel.penilaian.bahasa-inggris', $data);
    }

    public function storeBahasaInggris(Request $request)
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        if (!$periodeAktif || $periodeAktif->status != 'penilaian') {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Periode penilaian telah ditutup atau belum dimulai.']);
        }

        $request->validate(['penilaian_id' => 'required|exists:penilaians,id', 'skor' => 'required|array', 'skor.content' => 'required|numeric|min:5|max:25', 'skor.accuracy' => 'required|numeric|min:5|max:25', 'skor.fluency' => 'required|numeric|min:5|max:20', 'skor.pronunciation' => 'required|numeric|min:5|max:20', 'skor.performance' => 'required|numeric|min:3|max:10', 'catatan_juri_bi' => 'nullable|string', 'catatan_bi' => 'nullable|array']);
        $penilaian = Penilaian::find($request->penilaian_id);

        if (optional($penilaian)->status_penilaian_bi == 'final') {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Gagal! Penilaian BI untuk peserta ini sudah dikunci.']);
        }

        if (optional($penilaian)->juri_id != Auth::id()) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Anda tidak berhak mengubah penilaian ini.']);
        }

        $skorDetail = $request->skor;
        $totalSkor = array_sum($skorDetail);
        $catatanDetail = $request->has('catatan_bi') ? array_filter($request->catatan_bi) : null;

        $dataToUpdate = [
            'skor_bi_detail' => $skorDetail,
            'total_skor_bi' => $totalSkor,
            'catatan_juri_bi' => $request->catatan_juri_bi,
            'catatan_bi_detail' => $catatanDetail,
        ];

        if ($request->has('finalisasi_bi')) {
            $dataToUpdate['status_penilaian_bi'] = 'final';
        }

        $penilaian->update($dataToUpdate);

        $message = $request->has('finalisasi_bi') ? 'Penilaian Bahasa Inggris berhasil dikunci!' : 'Perubahan berhasil disimpan sebagai draf.';
        return redirect()->route('juri.penilaian.bi', ['peserta_id' => $penilaian->peserta_id])->with('notification', ['type' => 'success', 'message' => $message]);
    }
    
    public function panduan()
    {
        return view('panel.panduan');
    }
}

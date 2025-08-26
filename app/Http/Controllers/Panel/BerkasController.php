<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Berkas;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BerkasController extends Controller
{
    /**
     * Fungsi untuk mendapatkan deskripsi/nama yang lebih baik untuk notifikasi.
     * Ini adalah fungsi pembantu agar kode tidak berulang.
     */
    private function getDeskripsiBerkas($jenis_berkas, $nama_berkas)
    {
        switch ($jenis_berkas) {
            case 'KTP':
                return 'Berkas Kartu Tanda Penduduk (KTP)';
            case 'KRS':
                return 'Berkas Kartu Rencana Studi (KRS)';
            case 'NASKAH_GK':
                return 'Naskah Gagasan Kreatif (GK)';
            case 'SLIDE_GK':
                return 'Slide Presentasi GK';
            case 'CU':
                // Untuk CU, kita gunakan nama spesifik yang diinput panitia
                return "Bukti Capaian Unggulan '{$nama_berkas}'";
            default:
                // Fallback jika ada jenis berkas lain
                return "Berkas '{$nama_berkas}'";
        }
    }

    /**
     * Menyimpan berkas baru yang diunggah.
     */
    public function store(Request $request, Peserta $peserta)
    {
        // Aturan validasi dasar
        $rules = [
            'jenis_berkas' => 'required|string|in:KTP,KRS,NASKAH_GK,SLIDE_GK,CU',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        // Tambahkan aturan validasi kondisional
        if ($request->input('jenis_berkas') == 'CU') {
            $rules['nama_berkas_cu'] = 'required|string|max:255';
        } else {
            $rules['nama_berkas'] = 'required|string';
        }

        $request->validate($rules);

        // Logika penyimpanan
        $path = $request->file('file')->store('berkas_peserta/' . $peserta->id, 'public');
        $namaBerkas = $request->jenis_berkas == 'CU' ? $request->nama_berkas_cu : $request->nama_berkas;


        Berkas::create([
            'peserta_id' => $peserta->id,
            'nama_berkas' => $namaBerkas,
            'jenis_berkas' => $request->jenis_berkas,
            'path_file' => $path,
        ]);
        
        // Siapkan pesan notifikasi
        $deskripsi = $this->getDeskripsiBerkas($request->jenis_berkas, $namaBerkas);
        $pesanSukses = $deskripsi . ' berhasil diunggah!';

        // Kirim notifikasi dengan tipe 'success' (hijau)
        return redirect()->back()->with('notification', ['type' => 'success', 'message' => $pesanSukses]);
    }

    /**
     * Memperbarui berkas yang sudah ada.
     */
    public function update(Request $request, Berkas $berkas)
    {
        // Validasi input yang mungkin masuk
        $request->validate([
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'nama_berkas_cu' => 'sometimes|required|string|max:255',
            'tingkat' => 'sometimes|required|string',
        ]);

        $dataToUpdate = [];
        $namaBerkasSaatIni = $request->has('nama_berkas_cu') ? $request->nama_berkas_cu : $berkas->nama_berkas;

        if ($request->has('nama_berkas_cu')) {
            $dataToUpdate['nama_berkas'] = $request->nama_berkas_cu;
        }

        if ($request->has('tingkat')) {
            $dataToUpdate['tingkat'] = $request->tingkat;
        }

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($berkas->path_file);
            $dataToUpdate['path_file'] = $request->file('file')->store('berkas_peserta/' . $berkas->peserta_id, 'public');
        }

        if (!empty($dataToUpdate)) {
            $berkas->update($dataToUpdate);
        }

        // Siapkan pesan notifikasi
        $deskripsi = $this->getDeskripsiBerkas($berkas->jenis_berkas, $namaBerkasSaatIni);
        $pesanSukses = $deskripsi . ' berhasil diperbarui!';

        // Kirim notifikasi dengan tipe 'success' (hijau)
        return redirect()->back()->with('notification', ['type' => 'success', 'message' => $pesanSukses]);
    }

    /**
     * Menghapus berkas.
     */
    public function destroy(Berkas $berkas)
    {
        // Simpan dulu informasi penting sebelum berkas dihapus
        $peserta = $berkas->peserta;
        $jenisBerkasYangDihapus = $berkas->jenis_berkas;

        // Siapkan pesan notifikasi
        $deskripsi = $this->getDeskripsiBerkas($berkas->jenis_berkas, $berkas->nama_berkas);
        $pesanSukses = $deskripsi . ' berhasil dihapus.';

        // 1. Hapus file dari storage dan record dari database
        Storage::disk('public')->delete($berkas->path_file);
        $berkas->delete();

        // 2. Logika untuk update status verifikasi jika berkas wajib dihapus
        $berkasWajibList = ['KTP', 'KRS', 'NASKAH_GK', 'SLIDE_GK'];
        if (in_array($jenisBerkasYangDihapus, $berkasWajibList)) {
            $peserta->update(['status_verifikasi' => 'menunggu']);
        }

        // Kirim notifikasi dengan tipe 'danger' (merah)
        return redirect()->back()->with('notification', ['type' => 'danger', 'message' => $pesanSukses]);
    }
}
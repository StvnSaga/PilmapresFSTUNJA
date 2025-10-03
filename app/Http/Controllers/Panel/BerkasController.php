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
     * Fungsi pembantu untuk mendapatkan deskripsi berkas yang lebih informatif.
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
                return "Bukti Capaian Unggulan '{$nama_berkas}'";
            default:
                return "Berkas '{$nama_berkas}'";
        }
    }

    public function store(Request $request, Peserta $peserta)
    {
        $rules = [
            'jenis_berkas' => 'required|string|in:KTP,KRS,NASKAH_GK,SLIDE_GK,CU',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        if ($request->input('jenis_berkas') == 'CU') {
            $rules['nama_berkas_cu'] = 'required|string|max:255';
        } else {
            $rules['nama_berkas'] = 'required|string';
        }

        $request->validate($rules);

        $path = $request->file('file')->store('berkas_peserta/' . $peserta->id, 'public');
        $namaBerkas = $request->jenis_berkas == 'CU' ? $request->nama_berkas_cu : $request->nama_berkas;

        Berkas::create([
            'peserta_id' => $peserta->id,
            'nama_berkas' => $namaBerkas,
            'jenis_berkas' => $request->jenis_berkas,
            'path_file' => $path,
        ]);

        $deskripsi = $this->getDeskripsiBerkas($request->jenis_berkas, $namaBerkas);
        $pesanSukses = $deskripsi . ' berhasil diunggah!';

        return redirect()->back()->with('notification', ['type' => 'success', 'message' => $pesanSukses]);
    }

    public function update(Request $request, Berkas $berkas)
    {
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

        $deskripsi = $this->getDeskripsiBerkas($berkas->jenis_berkas, $namaBerkasSaatIni);
        $pesanSukses = $deskripsi . ' berhasil diperbarui!';

        return redirect()->back()->with('notification', ['type' => 'success', 'message' => $pesanSukses]);
    }

    public function destroy(Berkas $berkas)
    {
        $peserta = $berkas->peserta;
        $jenisBerkasYangDihapus = $berkas->jenis_berkas;
        $deskripsi = $this->getDeskripsiBerkas($berkas->jenis_berkas, $berkas->nama_berkas);
        $pesanSukses = $deskripsi . ' berhasil dihapus.';

        Storage::disk('public')->delete($berkas->path_file);
        $berkas->delete();

        // Jika berkas yang dihapus adalah berkas wajib, ubah status verifikasi peserta.
        $berkasWajibList = ['KTP', 'KRS', 'NASKAH_GK', 'SLIDE_GK'];
        if (in_array($jenisBerkasYangDihapus, $berkasWajibList)) {
            $peserta->update(['status_verifikasi' => 'menunggu']);
        }

        return redirect()->back()->with('notification', ['type' => 'danger', 'message' => $pesanSukses]);
    }
}
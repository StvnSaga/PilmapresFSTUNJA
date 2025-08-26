<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Berkas;
use App\Models\TahunSeleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PesertaController extends Controller
{
    public function index()
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        $pesertas = collect();
        if ($periodeAktif) {
            $pesertas = Peserta::where('tahun_seleksi_id', $periodeAktif->id)
                ->orderBy('nama_lengkap', 'asc')
                ->get();
        }
        $prodiList = [
            'Analisis Kimia', 'Teknik Pertamabangan', 'Teknik Geofisika', 'Teknik Geologi',
            'Matematika', 'Fisika', 'Biologi', 'Kimia', 'Kimia Industri',
            'Sistem Informasi', 'Informatika', 'Teknik Elektro', 'Teknik Kimia',
            'Teknik Lingkungan', 'Teknik Sipil'
        ];
        return view('panel.peserta.index', [
            'pesertas' => $pesertas,
            'prodiList' => $prodiList,
            'periodeAktif' => $periodeAktif,
        ]);
    }

    public function store(Request $request)
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        if (!$periodeAktif) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Gagal menambahkan peserta, tidak ada periode seleksi yang aktif.']);
        }
        $request->merge(['ipk' => str_replace(',', '.', $request->ipk)]);
        $request->validate([
            'nama_lengkap' => 'required|string|min:3|max:255',
            'nim' => 'required|string|unique:pesertas,nim,NULL,id,tahun_seleksi_id,' . $periodeAktif->id,
            'prodi' => 'required|string',
            'angkatan' => 'required|integer|digits:4',
            'email' => 'required|email',
            'no_hp' => 'required|numeric|digits_between:10,13',
            'ipk' => 'nullable|numeric|min:0|max:4.00',
            'foto' => 'required|image|mimes:jpg,png|max:2048',
        ]);
        $data = $request->except(['_token', 'foto']);
        $data['tahun_seleksi_id'] = $periodeAktif->id;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_peserta', 'public');
            $data['foto_path'] = $path;
        }



        Peserta::create($data);
        return redirect()->route('panel.peserta.index')->with('notification', ['type' => 'success', 'message' => 'Peserta baru berhasil ditambahkan!']);
    }

    public function show(Peserta $peserta)
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        $berkas = $peserta->berkas->groupBy('jenis_berkas');
        $berkasWajibList = [
            'KTP' => 'Kartu Tanda Penduduk (KTP)',
            'KRS' => 'Kartu Rencana Studi (KRS)',
            'NASKAH_GK' => 'Naskah Gagasan Kreatif (GK)',
            'SLIDE_GK' => 'Slide Presentasi GK (B. Inggris)',
        ];
        $prodiList = [
            'Analisis Kimia', 'Teknik Pertamabangan', 'Teknik Geofisika', 'Teknik Geologi',
            'Matematika', 'Fisika', 'Biologi', 'Kimia', 'Kimia Industri',
            'Sistem Informasi', 'Informatika', 'Teknik Elektro', 'Teknik Kimia',
            'Teknik Lingkungan', 'Teknik Sipil'
        ];
        return view('panel.peserta.show', [
            'peserta' => $peserta,
            'berkas' => $berkas,
            'berkasWajibList' => $berkasWajibList,
            'prodiList' => $prodiList,
            'periodeAktif' => $periodeAktif,
        ]);
    }

    public function update(Request $request, Peserta $peserta)
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        $request->merge(['ipk' => str_replace(',', '.', $request->ipk)]);
        $request->validate([
            'nama_lengkap' => 'required|string|min:3|max:255',
            'nim' => 'required|string|unique:pesertas,nim,' . $peserta->id . ',id,tahun_seleksi_id,' . $periodeAktif->id,
            'prodi' => 'required|string',
            'angkatan' => 'required|integer|digits:4',
            'email' => 'required|email',
            'no_hp' => 'required|numeric|digits_between:10,13',
            'ipk' => 'nullable|numeric|min:0|max:4.00',
            'foto' => 'nullable|image|mimes:jpg,png|max:2048',
        ]);
        $data = $request->except(['_token', 'foto']);
        if ($request->hasFile('foto')) {
            if ($peserta->foto_path) {
                Storage::disk('public')->delete($peserta->foto_path);
            }
            $path = $request->file('foto')->store('foto_peserta', 'public');
            $data['foto_path'] = $path;
        }
        $peserta->update($data);
        return redirect()->back()->with('notification', ['type' => 'success', 'message' => 'Data peserta berhasil diperbarui!']);
    }

    public function destroy(Peserta $peserta)
    {
        // Perbaikan kecil: Menghapus foto saat peserta dihapus
        if ($peserta->foto_path) {
            Storage::disk('public')->delete($peserta->foto_path);
        }
        $peserta->delete();
        return redirect()->route('panel.peserta.index')->with('notification', ['type' => 'danger', 'message' => 'Data peserta berhasil dihapus!']);
    }

    public function validasi(Peserta $peserta)
    {
        $peserta->update(['status_verifikasi' => 'diverifikasi']);
        return redirect()->back()->with('notification', ['type' => 'success', 'message' => 'Peserta berhasil divalidasi!']);
    }

    public function unverify(Peserta $peserta)
    {
        $peserta->penilaians()->delete();
        $peserta->update(['status_verifikasi' => 'menunggu']);
        return redirect()->back()->with('notification', ['type' => 'warning', 'message' => 'Verifikasi untuk ' . $peserta->nama_lengkap . ' berhasil dibatalkan.']);
    }

    public function reject(Peserta $peserta)
    {
        $peserta->update(['status_verifikasi' => 'ditolak']);
        return redirect()->back()->with('notification', ['type' => 'warning', 'message' => 'Pendaftaran untuk ' . $peserta->nama_lengkap . ' telah ditolak.']);
    }
}

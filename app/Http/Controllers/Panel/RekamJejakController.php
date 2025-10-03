<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\RekamJejak;
use App\Models\TahunSeleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RekamJejakController extends Controller
{
    public function index(Request $request)
    {
        $periodesSelesai = TahunSeleksi::where('status', 'selesai')->orderBy('tahun', 'desc')->get();
        $selectedPeriode = null;
        $top3Pesertas = collect();
        $rekamJejakData = collect();

        if ($request->has('tahun_seleksi_id')) {
            $selectedPeriode = TahunSeleksi::find($request->tahun_seleksi_id);
            if ($selectedPeriode) {
                $top3Pesertas = Peserta::where('tahun_seleksi_id', $selectedPeriode->id)
                    ->where('status_verifikasi', 'diverifikasi')
                    ->orderBy('skor_akhir', 'desc')
                    ->take(3)
                    ->get();

                $rekamJejakData = RekamJejak::where('tahun_seleksi_id', $selectedPeriode->id)
                    ->get()->keyBy('peserta_id');
            }
        }

        return view('panel.rekam-jejak.index', compact('periodesSelesai', 'selectedPeriode', 'top3Pesertas', 'rekamJejakData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_seleksi_id' => 'required|exists:tahun_seleksis,id',
            'pemenang' => 'required|array',
            'pemenang.*.deskripsi' => 'nullable|string',
            'pemenang.*.foto' => 'nullable|image|max:2048',
        ]);

        foreach ($request->pemenang as $pesertaId => $data) {
            $rekamJejak = RekamJejak::firstOrNew([
                'tahun_seleksi_id' => $request->tahun_seleksi_id,
                'peserta_id' => $pesertaId,
            ]);

            $rekamJejak->peringkat = $data['peringkat'];
            $rekamJejak->deskripsi_singkat = $data['deskripsi'];
            $fotoKey = "pemenang.{$pesertaId}.foto";
            
            if ($request->hasFile($fotoKey)) {
                if ($rekamJejak->foto_path) {
                    Storage::disk('public')->delete($rekamJejak->foto_path);
                }
                $path = $request->file($fotoKey)->store('rekam_jejak', 'public');
                $rekamJejak->foto_path = $path;
            }

            $rekamJejak->save();
        }

        return redirect()->back()->with('success', 'Data Rekam Jejak berhasil diperbarui!');
    }
}
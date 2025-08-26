<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User; // <-- TAMBAHKAN INI JIKA BELUM ADA
use App\Models\LogActivity;

class ProfilController extends Controller
{
    public function show()
    {
        // !! TAMBAHKAN LOGIKA INI !!
        $user = Auth::user();
        $logs = LogActivity::where('user_id', $user->id)
                            ->latest() // Ambil yang terbaru
                            ->take(5)  // Batasi 5 log saja
                            ->get();

        // !! UBAH RETURN VIEW UNTUK MENGIRIM DATA LOGS !!
        return view('panel.profil.index', [
            'user' => $user,
            'logs' => $logs,
        ]);
    }

    public function update(Request $request)
    {
        // !! PERBAIKAN DI SINI: Ambil model User dari database !!
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // <-- Sekarang method .save() akan berfungsi

        return redirect()->route('profil.show')->with('notification', ['type' => 'success', 'message' => 'Profil berhasil diperbarui!']);
    }
}
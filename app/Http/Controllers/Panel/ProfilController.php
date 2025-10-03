<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $logs = LogActivity::where('user_id', $user->id)
                            ->latest()
                            ->take(5)
                            ->get();

        return view('panel.profil.index', [
            'user' => $user,
            'logs' => $logs,
        ]);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profil.show')->with('notification', ['type' => 'success', 'message' => 'Profil berhasil diperbarui!']);
    }
}

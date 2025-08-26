<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManajemenUserController extends Controller
{
    public function index()
    {
        // PERBAIKAN 1: Ambil semua user KECUALI yang sedang login
        $loggedInUserId = Auth::id();
        $dataUser = User::where('id', '!=', $loggedInUserId)
                        ->orderBy('name', 'asc')
                        ->get();
        
        return view('panel.manajemen-user.index', ['users' => $dataUser]);
    }

    public function store(Request $request)
    {
        // Method store tidak perlu diubah, sudah benar
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,panitia,juri',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('notification', ['type' => 'success', 'message' => 'User baru berhasil ditambahkan!']);
    }

    public function update(Request $request, User $user)
    {
        // PERBAIKAN 2: Tambahkan proteksi agar Super Admin (ID=1) tidak bisa diubah rolenya
        if ($user->id === 1 && $request->role !== 'admin') {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Gagal! Role untuk Super Admin tidak dapat diubah.']);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'panitia', 'juri'])],
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8';
        }

        $request->validate($rules);
        $dataToUpdate = $request->only(['name', 'email', 'role']);
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }
        $user->update($dataToUpdate);

        return redirect()->route('admin.users.index')->with('notification', ['type' => 'success', 'message' => 'Data user berhasil diperbarui!']);
    }

    public function destroy(User $user)
    {
        // PERBAIKAN 3: Tambahkan proteksi agar Super Admin (ID=1) tidak bisa dihapus
        if ($user->id === 1) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Gagal! Akun Super Admin tidak dapat dihapus.']);
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('notification', ['type' => 'danger', 'message' => 'User berhasil dihapus!']);
    }
}

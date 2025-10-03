<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManajemenUserController extends Controller
{
    public function index()
    {
        $loggedInUserId = Auth::id();
        $dataUser = User::where('id', '!=', $loggedInUserId)
            ->orderBy('name', 'asc')
            ->get();

        return view('panel.manajemen-user.index', ['users' => $dataUser]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:panitia,juri',
            'jenis_juri' => 'nullable|required_if:role,juri|in:GK,BI',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        // Menyesuaikan data jenis_juri berdasarkan role
        if ($validatedData['role'] !== 'juri') {
            $validatedData['jenis_juri'] = null;
        }

        User::create($validatedData);

        return redirect()->route('admin.users.index')->with('notification', ['type' => 'success', 'message' => 'User baru berhasil ditambahkan!']);
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === 1 && $request->role !== 'admin') {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Gagal! Role untuk Super Admin tidak dapat diubah.']);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'panitia', 'juri'])],
            'jenis_juri' => 'nullable|required_if:role,juri|in:GK,BI',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8';
        }

        $dataToUpdate = $request->validate($rules);

        // Menangani update password (opsional)
        if (!empty($dataToUpdate['password'])) {
            $dataToUpdate['password'] = Hash::make($dataToUpdate['password']);
        } else {
            unset($dataToUpdate['password']); // Hapus password dari array jika tidak diisi
        }

        // Menyesuaikan data jenis_juri berdasarkan role
        if ($dataToUpdate['role'] !== 'juri') {
            $dataToUpdate['jenis_juri'] = null;
        }

        $user->update($dataToUpdate);

        return redirect()->route('admin.users.index')->with('notification', ['type' => 'success', 'message' => 'Data user berhasil diperbarui!']);
    }

    public function destroy(User $user)
    {
        if ($user->id === 1) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Gagal! Akun Super Admin tidak dapat dihapus.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('notification', ['type' => 'danger', 'message' => 'User berhasil dihapus!']);
    }
}

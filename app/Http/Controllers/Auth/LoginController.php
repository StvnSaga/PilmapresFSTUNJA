<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth; // BAGIAN PENTING 1: Impor facade Auth

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Mengarahkan pengguna ke halaman yang sesuai berdasarkan role mereka
     * setelah berhasil login.
     */
    public function redirectTo()
    {
        // BAGIAN PENTING 2: Gunakan Auth::user() untuk mendapatkan data pengguna
        $user = Auth::user();

        if ($user) {
            switch ($user->role) {
                case 'admin':
                    return '/panel/dashboard-admin';
                case 'panitia':
                    return '/panel/dashboard';
                case 'juri':
                    return '/panel/dashboard-juri';
                default:
                    return '/'; // Fallback untuk role yang tidak dikenal
            }
        }
        
        // Fallback jika user tidak ditemukan (seharusnya tidak terjadi setelah login)
        return $this->redirectTo;
    }
}
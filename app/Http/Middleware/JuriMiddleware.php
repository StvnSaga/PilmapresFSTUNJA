<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class JuriMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'juri') {
            return $next($request);
        }

        return redirect('/login')->withErrors([
            'email' => 'Anda tidak memiliki hak akses.',
        ]);
    }
}

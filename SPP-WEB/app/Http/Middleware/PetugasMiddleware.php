<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('petugas')->check()) {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Anda harus login sebagai Petugas!');
    }
}

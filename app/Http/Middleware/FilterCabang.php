<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FilterCabang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (in_array($user->role->role, ['administrator', 'owner'])) {
            // Jika pengguna adalah administrator atau owner, tidak perlu melakukan pemfilteran
            return $next($request);
        }

        // Lakukan pemfilteran berdasarkan cabang_id pengguna
        $request->merge(['cabang_id' => $user->cabang_id]);

        return $next($request);
    }
}

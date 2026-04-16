<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsBuyer
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user, 403);

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('toast', [
                'tone' => 'warning',
                'title' => 'Akses buyer ditutup untuk admin',
                'message' => 'Akun admin hanya bisa menggunakan area operasional admin panel.',
                'timeout' => 4500,
            ]);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login.'
                ], 401);
            }
            return redirect()->route('login');
        }

        // Cek role admin
        if (auth()->user()->role !== 'admin') {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden. Admin access only.'
                ], 403);
            }
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
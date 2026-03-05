<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in to access this page.']);
        }

        $user = Auth::user();

        // Check if user is an admin (DA Admin, Admin, or Superadmin)
        if (!$user->is_superadmin && $user->role !== 'Admin' && $user->role !== 'DA Admin') {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }

        return $next($request);
    }
}

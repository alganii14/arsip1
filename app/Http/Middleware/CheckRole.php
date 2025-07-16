<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('sign-in');
        }

        $userRole = $request->user()->role;
        
        // Map old roles to new roles for backward compatibility
        $roleMappings = [
            'admin' => 'unit_kerja',
            'petugas' => 'unit_kerja', 
            'peminjam' => 'unit_pengelola'
        ];

        foreach ($roles as $role) {
            // Check if the role matches directly
            if ($userRole === $role) {
                return $next($request);
            }
            
            // Check if the role matches through mapping
            if (isset($roleMappings[$role]) && $userRole === $roleMappings[$role]) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
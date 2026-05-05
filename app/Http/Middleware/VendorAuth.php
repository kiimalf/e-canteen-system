<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorAuth
{
    /**
     * Handle an incoming request.
     * Check if vendor is logged in via session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('vendor_id')) {
            return redirect()->route('vendor.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}

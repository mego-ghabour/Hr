<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectEmployeeToPortal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't intercept logout requests
        if ($request->is('*/logout') || $request->routeIs('filament.*.auth.logout')) {
            return $next($request);
        }

        $user = $request->user();

        if ($user && $user->isPortalOnlyEmployee()) {
            return redirect()->route('portal.dashboard');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
    
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!in_array(Auth::user()->type, $roles)) {
            abort(403, 'غير مصرح لك بالدخول');
        }

        return $next($request);
    }
    
    }


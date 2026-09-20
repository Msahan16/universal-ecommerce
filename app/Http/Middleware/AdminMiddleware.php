<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        if (!auth()->user()->is_admin) {
            return redirect()->route('home')->with('error', 'Unauthorized access: Administrator account required.');
        }

        return $next($request);
    }
}
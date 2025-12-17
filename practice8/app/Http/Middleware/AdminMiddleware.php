<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware для проверки прав администратора
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Необходимо войти в систему');
        }

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Доступ запрещен. Требуются права администратора.');
        }

        return $next($request);
    }
}
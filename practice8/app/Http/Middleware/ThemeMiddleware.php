<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware для применения пользовательской темы и языка
 */
class ThemeMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Определяем тему пользователя
        $theme = 'light'; // По умолчанию
        $language = 'ru'; // По умолчанию
        
        if (Auth::check()) {
            // Для авторизованных пользователей берем из профиля
            $theme = Auth::user()->theme ?? 'light';
            $language = Auth::user()->language ?? 'ru';
        } else {
            // Для гостей берем из cookies
            $theme = $request->cookie('user_theme', 'light');
            $language = $request->cookie('user_language', 'ru');
        }
        
        // Устанавливаем локаль приложения
        App::setLocale($language);
        
        // Передаем тему и язык во все представления
        View::share('currentTheme', $theme);
        View::share('currentLanguage', $language);
        View::share('availableLanguages', config('localization.languages', []));
        
        return $next($request);
    }
}
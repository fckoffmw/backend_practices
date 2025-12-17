<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * Контроллер настроек пользователя
 * Миграция из Practice 7 с адаптацией под Laravel
 */
class SettingsController extends Controller
{
    /**
     * Конструктор - требует аутентификации
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Показать настройки
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('settings.index', [
            'user' => $user,
            'themes' => $this->getAvailableThemes(),
            'languages' => $this->getAvailableLanguages(),
        ]);
    }

    /**
     * Обновить настройки
     */
    public function update(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark,colorblind',
            'language' => 'required|in:ru,en,de',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ], [
            'theme.required' => 'Выберите тему',
            'theme.in' => 'Недопустимая тема',
            'language.required' => 'Выберите язык',
            'language.in' => 'Недопустимый язык',
            'name.required' => 'Введите имя',
            'email.required' => 'Введите email',
            'email.unique' => 'Этот email уже используется',
        ]);

        $user = Auth::user();
        
        // Обновляем настройки пользователя
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'theme' => $request->theme,
            'language' => $request->language,
        ]);

        // Устанавливаем cookies для применения темы и языка
        cookie()->queue('user_theme', $request->theme, 60 * 24 * 30); // 30 дней
        cookie()->queue('user_language', $request->language, 60 * 24 * 30);
        
        // Устанавливаем локаль для текущего запроса
        app()->setLocale($request->language);

        return back()->with('success', 'Настройки успешно сохранены');
    }

    /**
     * Получить доступные темы
     */
    private function getAvailableThemes(): array
    {
        return [
            'light' => __('app.light_theme'),
            'dark' => __('app.dark_theme'),
            'colorblind' => __('app.colorblind_theme'),
        ];
    }

    /**
     * Получить доступные языки
     */
    private function getAvailableLanguages(): array
    {
        return config('localization.languages', [
            'ru' => ['name' => 'Русский'],
            'en' => ['name' => 'English'],
            'de' => ['name' => 'Deutsch'],
        ]);
    }

    /**
     * Переключить язык интерфейса
     */
    public function switchLanguage($locale)
    {
        // Проверяем, что язык поддерживается
        $availableLanguages = array_keys(config('localization.languages', ['ru' => [], 'en' => [], 'de' => []]));
        
        if (!in_array($locale, $availableLanguages)) {
            return redirect()->back()->withErrors(['language' => 'Неподдерживаемый язык']);
        }

        // Устанавливаем локаль
        app()->setLocale($locale);
        
        // Сохраняем в cookie
        cookie()->queue('user_language', $locale, 60 * 24 * 30); // 30 дней
        
        // Если пользователь авторизован, сохраняем в профиле
        if (Auth::check()) {
            Auth::user()->update(['language' => $locale]);
        }

        return redirect()->back()->with('success', __('app.language') . ' ' . __('app.settings_saved'));
    }

    /**
     * Экспорт настроек пользователя
     */
    public function export()
    {
        $user = Auth::user();
        
        $settings = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'theme' => $user->theme ?? 'light',
            'language' => $user->language ?? 'ru',
            'role' => $user->role,
            'created_at' => $user->created_at->toISOString(),
            'exported_at' => now()->toISOString(),
        ];

        $filename = 'user_settings_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.json';

        return response()->json($settings)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }
}
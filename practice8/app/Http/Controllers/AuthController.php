<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Контроллер аутентификации
 * Реализация полноценной системы входа для Practice 8
 */
class AuthController extends Controller
{
    /**
     * Показать форму входа
     */
    public function showLogin()
    {
        // Если пользователь уже авторизован, перенаправляем на главную
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Обработка входа в систему
     */
    public function login(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Введите email',
            'email.email' => 'Введите корректный email',
            'password.required' => 'Введите пароль',
            'password.min' => 'Пароль должен содержать минимум 6 символов',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Попытка аутентификации
        if (Auth::attempt($credentials, $remember)) {
            // Регенерация сессии для безопасности
            $request->session()->regenerate();
            
            // Обновление времени последнего входа
            Auth::user()->updateLastLogin();

            // Перенаправление на главную страницу
            return redirect()->intended(route('home'))->with('success', 'Добро пожаловать, ' . Auth::user()->name . '!');
        }

        // Если аутентификация не удалась
        return back()->withErrors([
            'email' => 'Неверный email или пароль.',
        ])->withInput($request->except('password'));
    }

    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Инвалидация сессии
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Вы успешно вышли из системы');
    }

    /**
     * Показать профиль пользователя
     */
    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('auth.profile', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Обновление профиля
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Введите имя',
            'email.required' => 'Введите email',
            'email.unique' => 'Этот email уже используется',
            'password.min' => 'Пароль должен содержать минимум 6 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        // Обновление пароля, если указан
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Профиль успешно обновлен');
    }
}
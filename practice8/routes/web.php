<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\DrawerController;
use App\Http\Controllers\SortController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Маршруты веб-приложения Practice 8
| Миграция из Practice 7 с адаптацией под Laravel Router
|
*/

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Статистика
Route::prefix('statistics')->name('statistics.')->group(function () {
    Route::get('/', [StatisticsController::class, 'index'])->name('index');
    Route::post('/generate-fixtures', [StatisticsController::class, 'generateFixtures'])->name('generate-fixtures');
});

// Настройки (только для авторизованных)
Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [App\Http\Controllers\SettingsController::class, 'index'])->name('index');
    Route::put('/', [App\Http\Controllers\SettingsController::class, 'update'])->name('update');
    Route::get('/export', [App\Http\Controllers\SettingsController::class, 'export'])->name('export');
});

// Файлы (только для авторизованных)
Route::middleware('auth')->prefix('files')->name('files.')->group(function () {
    Route::get('/', [App\Http\Controllers\FileController::class, 'index'])->name('index');
    Route::post('/upload', [App\Http\Controllers\FileController::class, 'upload'])->name('upload');
    Route::get('/download/{id}', [App\Http\Controllers\FileController::class, 'download'])->name('download');
    Route::delete('/{id}', [App\Http\Controllers\FileController::class, 'delete'])->name('delete');
});

// Сервисы
Route::prefix('services')->name('services.')->group(function () {
    
    // SVG Генератор
    Route::prefix('drawer')->name('drawer.')->group(function () {
        Route::get('/', [DrawerController::class, 'index'])->name('index');
        Route::post('/generate', [DrawerController::class, 'generate'])->name('generate');
        Route::post('/download', [DrawerController::class, 'download'])->name('download');
    });
    
    // Сортировка массивов
    Route::prefix('sort')->name('sort.')->group(function () {
        Route::get('/', [SortController::class, 'index'])->name('index');
        Route::post('/', [SortController::class, 'sort'])->name('sort');
    });
});

// Заглушки для маршрутов из шаблона
Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
Route::get('/services/drawer', [DrawerController::class, 'index'])->name('services.drawer');
Route::get('/services/sort', [SortController::class, 'index'])->name('services.sort');

// Аутентификация
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    
    // Профиль (только для авторизованных)
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile.update');
    });
});

// Админ-панель (только для администраторов)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('index');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/system', [App\Http\Controllers\AdminController::class, 'system'])->name('system');
});

// Переключение языка
Route::get('/language/{locale}', [App\Http\Controllers\SettingsController::class, 'switchLanguage'])->name('language.switch');

// Совместимость со старыми маршрутами
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
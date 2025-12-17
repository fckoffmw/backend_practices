<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesStatistic;

/**
 * Контроллер админ-панели
 * Доступен только администраторам
 */
class AdminController extends Controller
{
    /**
     * Конструктор - применяем middleware
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Главная страница админ-панели
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::active()->count(),
            'admin_users' => User::admins()->count(),
            'total_sales' => SalesStatistic::count(),
            'total_revenue' => SalesStatistic::sum('revenue'),
        ];

        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        $recentSales = SalesStatistic::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.index', compact('stats', 'recentUsers', 'recentSales'));
    }

    /**
     * Управление пользователями
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Системная информация
     */
    public function system()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
        ];

        return view('admin.system', compact('systemInfo'));
    }
}
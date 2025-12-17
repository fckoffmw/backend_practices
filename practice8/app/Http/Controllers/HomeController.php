<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesStatistic;

/**
 * Контроллер главной страницы
 * Миграция из Practice 7 с адаптацией под Laravel
 */
class HomeController extends Controller
{
    public function index()
    {
        // Получаем статистику для главной страницы
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::active()->count(),
            'total_sales' => SalesStatistic::count(),
            'total_revenue' => SalesStatistic::sum('revenue'),
        ];

        // Последние продажи
        $recentSales = SalesStatistic::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('home.index', compact('stats', 'recentSales'));
    }
}
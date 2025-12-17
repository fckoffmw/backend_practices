<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Eloquent модель статистики продаж
 * Миграция из Practice 7 Entity в Laravel Model
 */
class SalesStatistic extends Model
{
    use HasFactory;

    protected $table = 'sales_statistics';

    protected $fillable = [
        'product_name',
        'category',
        'price',
        'quantity',
        'revenue',
        'sale_date',
        'region',
        'customer_name',
        'customer_email',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'revenue' => 'decimal:2',
        'quantity' => 'integer',
        'sale_date' => 'date',
    ];

    /**
     * Автоматический расчет выручки при сохранении
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->revenue = $model->price * $model->quantity;
        });
    }

    /**
     * Scope для фильтрации по категории
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope для фильтрации по региону
     */
    public function scopeByRegion($query, string $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope для фильтрации по периоду
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }

    /**
     * Получить статистику по категориям
     */
    public static function getCategoryStats()
    {
        return self::selectRaw('category, COUNT(*) as count, SUM(revenue) as total_revenue')
            ->groupBy('category')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    /**
     * Получить статистику по регионам
     */
    public static function getRegionStats()
    {
        return self::selectRaw('region, COUNT(*) as count, SUM(revenue) as total_revenue')
            ->groupBy('region')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    /**
     * Получить статистику по месяцам
     */
    public static function getMonthlyStats()
    {
        return self::selectRaw('YEAR(sale_date) as year, MONTH(sale_date) as month, COUNT(*) as count, SUM(revenue) as total_revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    /**
     * Получить топ продукты
     */
    public static function getTopProducts($limit = 10)
    {
        return self::selectRaw('product_name, COUNT(*) as sales_count, SUM(revenue) as total_revenue')
            ->groupBy('product_name')
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get();
    }
}
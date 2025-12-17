@extends('layouts.app')

@section('title', 'Статистика продаж - Practice 8')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">📊 Статистика продаж</h1>
        <p class="lead">
            Анализ данных о продажах с автоматической генерацией графиков и диаграмм.
        </p>
    </div>
</div>

<!-- Генерация тестовых данных -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1">Генерация тестовых данных</h5>
                        <p class="mb-0 text-muted">
                            Создайте случайные данные о продажах для демонстрации функциональности
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <form action="{{ route('statistics.generate-fixtures') }}" method="POST" class="d-inline-flex align-items-center">
                            @csrf
                            <input 
                                type="number" 
                                name="count" 
                                class="form-control form-control-sm me-2" 
                                value="50" 
                                min="1" 
                                max="1000"
                                style="width: 80px;"
                            >
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                Сгенерировать
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Графики -->
@if(isset($charts) && count($charts) > 0)
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4">Графики и диаграммы</h2>
    </div>
    
    @if(isset($charts['bar']))
    <div class="col-md-4">
        <div class="chart-container">
            <h4>Выручка по категориям</h4>
            <img src="{{ asset('charts/' . $charts['bar']) }}" alt="Столбчатая диаграмма" class="img-fluid">
        </div>
    </div>
    @endif
    
    @if(isset($charts['pie']))
    <div class="col-md-4">
        <div class="chart-container">
            <h4>Продажи по регионам</h4>
            <img src="{{ asset('charts/' . $charts['pie']) }}" alt="Круговая диаграмма" class="img-fluid">
        </div>
    </div>
    @endif
    
    @if(isset($charts['line']))
    <div class="col-md-4">
        <div class="chart-container">
            <h4>Динамика по месяцам</h4>
            <img src="{{ asset('charts/' . $charts['line']) }}" alt="Линейный график" class="img-fluid">
        </div>
    </div>
    @endif
</div>
@endif

<!-- Статистика по категориям -->
@if($categoryStats->count() > 0)
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Статистика по категориям</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Категория</th>
                                <th class="text-end">Продаж</th>
                                <th class="text-end">Выручка</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryStats as $stat)
                            <tr>
                                <td>{{ $stat->category }}</td>
                                <td class="text-end">{{ number_format($stat->count) }}</td>
                                <td class="text-end">₽{{ number_format($stat->total_revenue, 0, ',', ' ') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Статистика по регионам -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Статистика по регионам</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Регион</th>
                                <th class="text-end">Продаж</th>
                                <th class="text-end">Выручка</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($regionStats->take(10) as $stat)
                            <tr>
                                <td>{{ $stat->region }}</td>
                                <td class="text-end">{{ number_format($stat->count) }}</td>
                                <td class="text-end">₽{{ number_format($stat->total_revenue, 0, ',', ' ') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Топ продукты -->
@if($topProducts->count() > 0)
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Топ-10 продуктов</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Продукт</th>
                                <th class="text-end">Продаж</th>
                                <th class="text-end">Выручка</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                            <tr>
                                <td>{{ Str::limit($product->product_name, 30) }}</td>
                                <td class="text-end">{{ number_format($product->sales_count) }}</td>
                                <td class="text-end">₽{{ number_format($product->total_revenue, 0, ',', ' ') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Динамика по месяцам -->
    @if($monthlyStats->count() > 0)
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Динамика по месяцам</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Период</th>
                                <th class="text-end">Продаж</th>
                                <th class="text-end">Выручка</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyStats->take(12) as $stat)
                            <tr>
                                <td>{{ $stat->month }}/{{ $stat->year }}</td>
                                <td class="text-end">{{ number_format($stat->count) }}</td>
                                <td class="text-end">₽{{ number_format($stat->total_revenue, 0, ',', ' ') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

@if($categoryStats->count() == 0)
<!-- Пустое состояние -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <h3 class="text-muted">Нет данных для отображения</h3>
                <p class="text-muted mb-4">
                    Сгенерируйте тестовые данные для просмотра статистики и графиков
                </p>
                <form action="{{ route('statistics.generate-fixtures') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="count" value="100">
                    <button type="submit" class="btn btn-primary">
                        Сгенерировать 100 записей
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Техническая информация -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Технические особенности</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h5>Генерация графиков</h5>
                        <ul>
                            <li>Библиотека GD для PHP</li>
                            <li>Автоматическое масштабирование</li>
                            <li>Динамические цвета</li>
                            <li>Водяные знаки</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>База данных</h5>
                        <ul>
                            <li>Eloquent ORM</li>
                            <li>Индексированные поля</li>
                            <li>Агрегатные запросы</li>
                            <li>Оптимизированные выборки</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Laravel интеграция</h5>
                        <ul>
                            <li>Model Factories</li>
                            <li>Database Seeders</li>
                            <li>Service Container</li>
                            <li>Blade Templates</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
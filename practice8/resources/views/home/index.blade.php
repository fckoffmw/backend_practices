@extends('layouts.app')

@section('title', __('app.home') . ' - Practice 8')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Practice 8 - Laravel Integration</h1>
        
        <!-- Language Debug Info -->
        <div class="alert alert-info mb-3">
            <strong>{{ __('app.language') }}:</strong> {{ app()->getLocale() }} | 
            <strong>{{ __('app.theme') }}:</strong> {{ $currentTheme ?? 'light' }}
        </div>
        
        @auth
            <div class="alert alert-success">
                <h5>👋 {{ __('app.welcome') }}, {{ Auth::user()->name }}!</h5>
                <p class="mb-0">
                    Вы вошли как 
                    <strong>{{ Auth::user()->role === 'admin' ? 'Администратор' : 'Пользователь' }}</strong>
                    ({{ Auth::user()->email }})
                </p>
            </div>
        @else
            <div class="alert alert-info">
                <h5>🔐 Аутентификация</h5>
                <p class="mb-2">
                    Для полного доступа к функциям системы рекомендуется 
                    <a href="{{ route('login') }}" class="alert-link">{{ __('app.login') }}</a>.
                </p>
                <p class="mb-0">
                    <strong>Тестовые аккаунты:</strong> admin@practice8.local / password123
                </p>
            </div>
        @endauth
        
        <p class="lead">
            Добро пожаловать в Practice 8! Это интеграция всей функциональности из Practice 7 
            в Laravel фреймворк с сохранением Clean Architecture принципов.
        </p>
    </div>
</div>

<!-- Статистика -->
<div class="row mb-5">
    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ number_format($stats['total_users']) }}</h3>
            <p class="mb-0">Всего пользователей</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ number_format($stats['active_users']) }}</h3>
            <p class="mb-0">Активных пользователей</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ number_format($stats['total_sales']) }}</h3>
            <p class="mb-0">Всего продаж</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <h3>₽{{ number_format($stats['total_revenue'], 0, ',', ' ') }}</h3>
            <p class="mb-0">Общая выручка</p>
        </div>
    </div>
</div>

<!-- Сервисы -->
<div class="row mb-5">
    <div class="col-12">
        <h2 class="mb-4">Доступные сервисы</h2>
    </div>
    
    <div class="col-md-4">
        <div class="card service-card h-100">
            <div class="card-body">
                <h5 class="card-title">📊 Статистика продаж</h5>
                <p class="card-text">
                    Просмотр и анализ статистики продаж с генерацией графиков
                </p>
                <a href="{{ route('statistics') }}" class="btn btn-primary">Перейти</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card service-card h-100">
            <div class="card-body">
                <h5 class="card-title">🎨 SVG Генератор</h5>
                <p class="card-text">
                    Генерация уникальных SVG изображений на основе чисел
                </p>
                <a href="{{ route('services.drawer') }}" class="btn btn-primary">Перейти</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card service-card h-100">
            <div class="card-body">
                <h5 class="card-title">🔢 Сортировка массивов</h5>
                <p class="card-text">
                    Сортировка массивов различными алгоритмами с анализом производительности
                </p>
                <a href="{{ route('services.sort') }}" class="btn btn-primary">Перейти</a>
            </div>
        </div>
    </div>
</div>

<!-- Последние продажи -->
@if($recentSales->count() > 0)
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Последние продажи</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Продукт</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Выручка</th>
                        <th>Дата</th>
                        <th>Регион</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                    <tr>
                        <td>{{ $sale->product_name }}</td>
                        <td>{{ $sale->category }}</td>
                        <td>₽{{ number_format($sale->price, 2) }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>₽{{ number_format($sale->revenue, 2) }}</td>
                        <td>{{ $sale->sale_date->format('d.m.Y') }}</td>
                        <td>{{ $sale->region }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <a href="{{ route('statistics') }}" class="btn btn-outline-primary">
                Посмотреть всю статистику
            </a>
        </div>
    </div>
</div>
@endif

<!-- Информация о фреймворке -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Преимущества интеграции в Laravel</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Архитектурные улучшения:</h5>
                        <ul>
                            <li>Eloquent ORM вместо PDO</li>
                            <li>Blade шаблонизатор</li>
                            <li>Laravel Service Container</li>
                            <li>Middleware для авторизации</li>
                            <li>Встроенная валидация</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Функциональные преимущества:</h5>
                        <ul>
                            <li>Миграции и сидеры БД</li>
                            <li>Model Factories для тестов</li>
                            <li>Artisan CLI команды</li>
                            <li>Кеширование и сессии</li>
                            <li>Стандартизация кода</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
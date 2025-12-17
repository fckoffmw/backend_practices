@extends('layouts.app')

@section('title', 'Админ-панель - Practice 8')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>🛠️ Админ-панель</h1>
            <div class="badge bg-danger fs-6">Только для администраторов</div>
        </div>
        
        <div class="alert alert-success">
            <h5>✅ Доступ разрешен</h5>
            <p class="mb-0">
                Добро пожаловать в админ-панель, <strong>{{ Auth::user()->name }}</strong>! 
                Вы успешно прошли аутентификацию и авторизацию.
            </p>
        </div>
    </div>
</div>

<!-- Статистика -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ number_format($stats['total_users']) }}</h3>
                <p class="mb-0">Всего пользователей</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ number_format($stats['active_users']) }}</h3>
                <p class="mb-0">Активных</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger">{{ number_format($stats['admin_users']) }}</h3>
                <p class="mb-0">Админов</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">{{ number_format($stats['total_sales']) }}</h3>
                <p class="mb-0">Продаж</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-warning">₽{{ number_format($stats['total_revenue'], 0, ',', ' ') }}</h3>
                <p class="mb-0">Выручка</p>
            </div>
        </div>
    </div>
</div>

<!-- Последние пользователи и продажи -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Последние пользователи</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Роль</th>
                                <th>Дата</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Последние продажи</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Продукт</th>
                                <th>Категория</th>
                                <th>Выручка</th>
                                <th>Дата</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                            <tr>
                                <td>{{ Str::limit($sale->product_name, 20) }}</td>
                                <td>{{ $sale->category }}</td>
                                <td>₽{{ number_format($sale->revenue, 0) }}</td>
                                <td>{{ $sale->sale_date->format('d.m.Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Админ функции -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Административные функции</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="bi bi-people"></i> Управление пользователями
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.system') }}" class="btn btn-outline-info w-100 mb-2">
                            <i class="bi bi-gear"></i> Системная информация
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('statistics.generate-fixtures') }}" class="btn btn-outline-success w-100 mb-2" onclick="return confirm('Сгенерировать 50 записей?')">
                            <i class="bi bi-database-add"></i> Генерация данных
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('statistics') }}" class="btn btn-outline-warning w-100 mb-2">
                            <i class="bi bi-graph-up"></i> Статистика
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
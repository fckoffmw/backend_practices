@extends('layouts.app')

@section('title', 'Профиль - Practice 8')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Профиль пользователя</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Информация о пользователе -->
                        <div class="text-center mb-4">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <h4 class="mt-3">{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                {{ $user->role === 'admin' ? 'Администратор' : 'Пользователь' }}
                            </span>
                        </div>
                        
                        <!-- Статистика -->
                        <div class="card">
                            <div class="card-body">
                                <h6>Информация об аккаунте</h6>
                                <hr>
                                <p><strong>Статус:</strong> 
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Активен' : 'Неактивен' }}
                                    </span>
                                </p>
                                <p><strong>Регистрация:</strong><br>
                                    <small class="text-muted">{{ $user->created_at->format('d.m.Y H:i') }}</small>
                                </p>
                                @if($user->last_login_at)
                                <p><strong>Последний вход:</strong><br>
                                    <small class="text-muted">{{ $user->last_login_at->format('d.m.Y H:i') }}</small>
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <!-- Форма редактирования профиля -->
                        <form action="{{ route('auth.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Имя</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input 
                                    type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <hr>
                            <h5>Изменение пароля</h5>
                            <p class="text-muted">Оставьте поля пустыми, если не хотите менять пароль</p>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Новый пароль</label>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password"
                                    minlength="6"
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password_confirmation" 
                                    name="password_confirmation"
                                    minlength="6"
                                >
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    Назад
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Сохранить изменения
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
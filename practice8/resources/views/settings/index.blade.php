@extends('layouts.app')

@section('title', __('app.settings') . ' - Practice 8')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">⚙️ {{ __('app.user_settings') }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ __('app.personal_info') }}</h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('app.name') }}</label>
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
                        </div>
                        
                        <div class="col-md-6">
                            <h5>{{ __('app.interface_settings') }}</h5>
                            
                            <div class="mb-3">
                                <label for="theme" class="form-label">{{ __('app.theme') }}</label>
                                <select 
                                    class="form-select @error('theme') is-invalid @enderror" 
                                    id="theme" 
                                    name="theme"
                                    onchange="previewTheme(this.value)"
                                >
                                    @foreach($themes as $key => $name)
                                        <option value="{{ $key }}" {{ old('theme', $user->theme ?? 'light') === $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('theme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Изменения применятся после сохранения
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="language" class="form-label">{{ __('app.language') }}</label>
                                <select 
                                    class="form-select @error('language') is-invalid @enderror" 
                                    id="language" 
                                    name="language"
                                >
                                    @foreach($languages as $key => $language)
                                        <option value="{{ $key }}" {{ old('language', $user->language ?? 'ru') === $key ? 'selected' : '' }}>
                                            {{ is_array($language) ? $language['name'] : $language }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Назад
                            </a>
                            <a href="{{ route('settings.export') }}" class="btn btn-outline-info">
                                <i class="bi bi-download"></i> Экспорт настроек
                            </a>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Сохранить настройки
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Информация о пользователе -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">📊 Информация об аккаунте</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>ID пользователя:</strong></td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Роль:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                        {{ $user->role === 'admin' ? 'Администратор' : 'Пользователь' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Статус:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Активен' : 'Неактивен' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Дата регистрации:</strong></td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            @if($user->last_login_at)
                            <tr>
                                <td><strong>Последний вход:</strong></td>
                                <td>{{ $user->last_login_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Текущая тема:</strong></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $themes[$user->theme ?? 'light'] }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Предварительный просмотр тем -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">🎨 Предварительный просмотр тем</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="theme-preview" data-theme="light">
                            <div class="p-3 border rounded" style="background: #ffffff; color: #000000;">
                                <h6>Светлая тема</h6>
                                <p class="mb-0">Классическая светлая тема для комфортной работы днем</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="theme-preview" data-theme="dark">
                            <div class="p-3 border rounded" style="background: #212529; color: #ffffff;">
                                <h6>Тёмная тема</h6>
                                <p class="mb-0">Тёмная тема для работы в условиях низкой освещенности</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="theme-preview" data-theme="colorblind">
                            <div class="p-3 border rounded" style="background: #f8f9fa; color: #495057; filter: contrast(1.2);">
                                <h6>Для дальтоников</h6>
                                <p class="mb-0">Высококонтрастная тема с улучшенной читаемостью</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewTheme(theme) {
    // Подсветка выбранной темы
    document.querySelectorAll('.theme-preview').forEach(preview => {
        preview.style.opacity = '0.6';
        preview.style.transform = 'scale(0.95)';
    });
    
    const selectedPreview = document.querySelector(`[data-theme="${theme}"]`);
    if (selectedPreview) {
        selectedPreview.style.opacity = '1';
        selectedPreview.style.transform = 'scale(1.05)';
        selectedPreview.style.transition = 'all 0.3s ease';
    }
}

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', function() {
    const currentTheme = document.getElementById('theme').value;
    previewTheme(currentTheme);
});
</script>
@endpush

@push('styles')
<style>
.theme-preview {
    transition: all 0.3s ease;
    cursor: pointer;
}

.theme-preview:hover {
    transform: scale(1.02);
}
</style>
@endpush
@extends('layouts.app')

@section('title', 'Вход - Practice 8')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Вход в систему</h3>
            </div>
            <div class="card-body">
                <!-- Информация о тестовых аккаунтах -->
                <div class="alert alert-info">
                    <h5>Тестовые аккаунты</h5>
                    <p class="mb-2">Для демонстрации используйте:</p>
                    <ul class="mb-0">
                        <li><strong>Администратор:</strong> admin@practice8.local / password123</li>
                        <li><strong>Пользователь:</strong> user@practice8.local / password123</li>
                    </ul>
                </div>
                
                <!-- Форма входа -->
                <form action="{{ route('auth.login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            id="email" 
                            name="email"
                            value="{{ old('email', 'admin@practice8.local') }}"
                            required
                            autofocus
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            id="password" 
                            name="password"
                            value="password123"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Запомнить меня
                        </label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Войти
                        </button>
                    </div>
                </form>
                
                <!-- Быстрый вход -->
                <div class="mt-4">
                    <h6>Быстрый вход:</h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="quickLogin('admin@practice8.local')">
                            Войти как Админ
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="quickLogin('user@practice8.local')">
                            Войти как Пользователь
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function quickLogin(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'password123';
    document.querySelector('form').submit();
}
</script>
@endpush
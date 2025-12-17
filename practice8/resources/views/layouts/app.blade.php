<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Practice 8 - Laravel Integration')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Theme CSS -->
    @if(isset($currentTheme))
        @if($currentTheme === 'dark')
            <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/darkly/bootstrap.min.css" rel="stylesheet">
        @elseif($currentTheme === 'colorblind')
            <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/cerulean/bootstrap.min.css" rel="stylesheet">
            <style>
                body { filter: contrast(1.3) brightness(1.1); }
                .text-muted { color: #495057 !important; }
            </style>
        @endif
    @endif

    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .footer {
            background-color: {{ isset($currentTheme) && $currentTheme === 'dark' ? '#343a40' : '#f8f9fa' }};
            padding: 20px 0;
            margin-top: 50px;
        }
        .chart-container {
            margin: 20px 0;
            text-align: center;
        }
        .chart-container img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .service-card {
            transition: transform 0.2s;
        }
        .service-card:hover {
            transform: translateY(-5px);
        }
        
        /* Тема для дальтоников */
        @if(isset($currentTheme) && $currentTheme === 'colorblind')
        .btn-primary { background-color: #0066cc !important; border-color: #0066cc !important; }
        .btn-success { background-color: #009900 !important; border-color: #009900 !important; }
        .btn-danger { background-color: #cc0000 !important; border-color: #cc0000 !important; }
        .btn-warning { background-color: #ff9900 !important; border-color: #ff9900 !important; color: #000 !important; }
        @endif
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                Practice 8 - Laravel
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">{{ __('app.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('statistics') }}">{{ __('app.statistics') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            {{ __('app.services') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('services.drawer') }}">
                                <i class="bi bi-palette"></i> {{ __('app.svg_generator') }}
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('services.sort') }}">
                                <i class="bi bi-sort-numeric-down"></i> {{ __('app.sorting_algorithms') }}
                            </a></li>
                            @auth
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('files.index') }}">
                                    <i class="bi bi-cloud-upload"></i> {{ __('app.file_management') }}
                                </a></li>
                                @if(Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.index') }}">
                                        <i class="bi bi-shield-check"></i> {{ __('app.admin') }}
                                    </a></li>
                                @endif
                            @endauth
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Language Switcher -->
                    @if(isset($availableLanguages) && count($availableLanguages) > 1)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
                                @if(isset($availableLanguages[app()->getLocale()]))
                                    {{ $availableLanguages[app()->getLocale()]['flag'] ?? '🌐' }} {{ $availableLanguages[app()->getLocale()]['name'] }}
                                @else
                                    🌐 {{ app()->getLocale() }}
                                @endif
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($availableLanguages as $code => $language)
                                    <li>
                                        <a class="dropdown-item {{ app()->getLocale() === $code ? 'active' : '' }}" 
                                           href="{{ route('language.switch', $code) }}">
                                            {{ $language['flag'] ?? '🌐' }} {{ $language['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                    
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <span class="badge bg-{{ Auth::user()->role === 'admin' ? 'danger' : 'primary' }} me-1">
                                    {{ Auth::user()->role === 'admin' ? 'Admin' : 'User' }}
                                </span>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="bi bi-person"></i> {{ __('app.profile') }}
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('settings.index') }}">
                                    <i class="bi bi-gear"></i> {{ __('app.settings') }}
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> {{ __('app.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> {{ __('app.login') }}
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mt-4">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Practice 8 - Laravel Integration</h5>
                    <p class="text-muted">
                        Интеграция функциональности Practice 7 в Laravel фреймворк
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted">
                        © 2024 Practice 8. Все права защищены.
                    </p>
                    <p class="text-muted">
                        <small>Laravel {{ app()->version() }} | PHP {{ PHP_VERSION }}</small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
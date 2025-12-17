<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Practice 8 - Laravel Integration'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Theme CSS -->
    <?php if(isset($currentTheme)): ?>
        <?php if($currentTheme === 'dark'): ?>
            <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/darkly/bootstrap.min.css" rel="stylesheet">
        <?php elseif($currentTheme === 'colorblind'): ?>
            <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/cerulean/bootstrap.min.css" rel="stylesheet">
            <style>
                body { filter: contrast(1.3) brightness(1.1); }
                .text-muted { color: #495057 !important; }
            </style>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .footer {
            background-color: <?php echo e(isset($currentTheme) && $currentTheme === 'dark' ? '#343a40' : '#f8f9fa'); ?>;
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
        <?php if(isset($currentTheme) && $currentTheme === 'colorblind'): ?>
        .btn-primary { background-color: #0066cc !important; border-color: #0066cc !important; }
        .btn-success { background-color: #009900 !important; border-color: #009900 !important; }
        .btn-danger { background-color: #cc0000 !important; border-color: #cc0000 !important; }
        .btn-warning { background-color: #ff9900 !important; border-color: #ff9900 !important; color: #000 !important; }
        <?php endif; ?>
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                Practice 8 - Laravel
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('home')); ?>"><?php echo e(__('app.home')); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('statistics')); ?>"><?php echo e(__('app.statistics')); ?></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            <?php echo e(__('app.services')); ?>

                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo e(route('services.drawer')); ?>">
                                <i class="bi bi-palette"></i> <?php echo e(__('app.svg_generator')); ?>

                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('services.sort')); ?>">
                                <i class="bi bi-sort-numeric-down"></i> <?php echo e(__('app.sorting_algorithms')); ?>

                            </a></li>
                            <?php if(auth()->guard()->check()): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('files.index')); ?>">
                                    <i class="bi bi-cloud-upload"></i> <?php echo e(__('app.file_management')); ?>

                                </a></li>
                                <?php if(Auth::user()->isAdmin()): ?>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.index')); ?>">
                                        <i class="bi bi-shield-check"></i> <?php echo e(__('app.admin')); ?>

                                    </a></li>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Language Switcher -->
                    <?php if(isset($availableLanguages) && count($availableLanguages) > 1): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
                                <?php if(isset($availableLanguages[app()->getLocale()])): ?>
                                    <?php echo e($availableLanguages[app()->getLocale()]['flag'] ?? '🌐'); ?> <?php echo e($availableLanguages[app()->getLocale()]['name']); ?>

                                <?php else: ?>
                                    🌐 <?php echo e(app()->getLocale()); ?>

                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php $__currentLoopData = $availableLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <a class="dropdown-item <?php echo e(app()->getLocale() === $code ? 'active' : ''); ?>" 
                                           href="<?php echo e(route('language.switch', $code)); ?>">
                                            <?php echo e($language['flag'] ?? '🌐'); ?> <?php echo e($language['name']); ?>

                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <span class="badge bg-<?php echo e(Auth::user()->role === 'admin' ? 'danger' : 'primary'); ?> me-1">
                                    <?php echo e(Auth::user()->role === 'admin' ? 'Admin' : 'User'); ?>

                                </span>
                                <?php echo e(Auth::user()->name); ?>

                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo e(route('profile')); ?>">
                                    <i class="bi bi-person"></i> <?php echo e(__('app.profile')); ?>

                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('settings.index')); ?>">
                                    <i class="bi bi-gear"></i> <?php echo e(__('app.settings')); ?>

                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> <?php echo e(__('app.logout')); ?>

                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('login')); ?>">
                                <i class="bi bi-box-arrow-in-right"></i> <?php echo e(__('app.login')); ?>

                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mt-4">
        <!-- Flash Messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
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
                        <small>Laravel <?php echo e(app()->version()); ?> | PHP <?php echo e(PHP_VERSION); ?></small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>
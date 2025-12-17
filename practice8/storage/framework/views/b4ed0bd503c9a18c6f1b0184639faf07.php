<?php $__env->startSection('title', __('app.settings') . ' - Practice 8'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">⚙️ <?php echo e(__('app.user_settings')); ?></h3>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('settings.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5><?php echo e(__('app.personal_info')); ?></h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label"><?php echo e(__('app.name')); ?></label>
                                <input 
                                    type="text" 
                                    class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="name" 
                                    name="name"
                                    value="<?php echo e(old('name', $user->name)); ?>"
                                    required
                                >
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input 
                                    type="email" 
                                    class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="email" 
                                    name="email"
                                    value="<?php echo e(old('email', $user->email)); ?>"
                                    required
                                >
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5><?php echo e(__('app.interface_settings')); ?></h5>
                            
                            <div class="mb-3">
                                <label for="theme" class="form-label"><?php echo e(__('app.theme')); ?></label>
                                <select 
                                    class="form-select <?php $__errorArgs = ['theme'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="theme" 
                                    name="theme"
                                    onchange="previewTheme(this.value)"
                                >
                                    <?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('theme', $user->theme ?? 'light') === $key ? 'selected' : ''); ?>>
                                            <?php echo e($name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['theme'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">
                                    Изменения применятся после сохранения
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="language" class="form-label"><?php echo e(__('app.language')); ?></label>
                                <select 
                                    class="form-select <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="language" 
                                    name="language"
                                >
                                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('language', $user->language ?? 'ru') === $key ? 'selected' : ''); ?>>
                                            <?php echo e(is_array($language) ? $language['name'] : $language); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Назад
                            </a>
                            <a href="<?php echo e(route('settings.export')); ?>" class="btn btn-outline-info">
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
                                <td><?php echo e($user->id); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Роль:</strong></td>
                                <td>
                                    <span class="badge bg-<?php echo e($user->role === 'admin' ? 'danger' : 'primary'); ?>">
                                        <?php echo e($user->role === 'admin' ? 'Администратор' : 'Пользователь'); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Статус:</strong></td>
                                <td>
                                    <span class="badge bg-<?php echo e($user->is_active ? 'success' : 'danger'); ?>">
                                        <?php echo e($user->is_active ? 'Активен' : 'Неактивен'); ?>

                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Дата регистрации:</strong></td>
                                <td><?php echo e($user->created_at->format('d.m.Y H:i')); ?></td>
                            </tr>
                            <?php if($user->last_login_at): ?>
                            <tr>
                                <td><strong>Последний вход:</strong></td>
                                <td><?php echo e($user->last_login_at->format('d.m.Y H:i')); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td><strong>Текущая тема:</strong></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo e($themes[$user->theme ?? 'light']); ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.theme-preview {
    transition: all 0.3s ease;
    cursor: pointer;
}

.theme-preview:hover {
    transform: scale(1.02);
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/settings/index.blade.php ENDPATH**/ ?>
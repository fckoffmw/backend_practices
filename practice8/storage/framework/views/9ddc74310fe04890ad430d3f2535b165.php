<?php $__env->startSection('title', __('app.file_management') . ' - Practice 8'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>📁 <?php echo e(__('app.file_management')); ?></h1>
            <div class="badge bg-info fs-6"><?php echo e($diskUsage['file_count']); ?> файлов</div>
        </div>
    </div>
</div>

<!-- Использование диска -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1"><?php echo e(__('app.disk_usage')); ?></h5>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo e($diskUsage['usage_percent']); ?>%">
                                <?php echo e($diskUsage['usage_percent']); ?>%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted"><?php echo e(__('app.used')); ?>: <?php echo e($diskUsage['total_size_formatted']); ?></small>
                            <small class="text-muted"><?php echo e(__('app.limit')); ?>: <?php echo e($diskUsage['user_limit_formatted']); ?></small>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="text-muted">
                            <strong><?php echo e(__('app.remaining')); ?>:</strong> <?php echo e($diskUsage['remaining_formatted']); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Загрузка файла -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">📤 <?php echo e(__('app.upload_file')); ?></h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('files.upload')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label"><?php echo e(__('app.upload_file')); ?></label>
                                <input 
                                    type="file" 
                                    class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="file" 
                                    name="file"
                                    accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.csv,.xlsx,.svg"
                                    required
                                >
                                <?php $__errorArgs = ['file'];
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
                                    Максимальный размер: 10MB. Разрешенные типы: JPG, PNG, PDF, DOC, TXT, CSV, SVG
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description" class="form-label"><?php echo e(__('app.description')); ?> (<?php echo e(__('app.optional')); ?>)</label>
                                <textarea 
                                    class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="description" 
                                    name="description"
                                    rows="3"
                                    placeholder="Краткое описание файла..."
                                ><?php echo e(old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
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
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cloud-upload"></i> <?php echo e(__('app.upload_file')); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Список файлов -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">📋 <?php echo e(__('app.my_files')); ?></h5>
            </div>
            <div class="card-body">
                <?php if($userFiles->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('app.file')); ?></th>
                                    <th><?php echo e(__('app.file_size')); ?></th>
                                    <th><?php echo e(__('app.file_type')); ?></th>
                                    <th><?php echo e(__('app.description')); ?></th>
                                    <th><?php echo e(__('app.upload_date')); ?></th>
                                    <th><?php echo e(__('app.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $userFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi <?php echo e($file->icon); ?> me-2 text-primary"></i>
                                            <div>
                                                <strong><?php echo e($file->original_name); ?></strong>
                                                <?php if($file->is_image): ?>
                                                    <br><small class="text-muted">Изображение</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e($file->formatted_size); ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo e(strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION))); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($file->description): ?>
                                            <?php echo e(Str::limit($file->description, 50)); ?>

                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($file->created_at->format('d.m.Y H:i')); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('files.download', $file->id)); ?>" class="btn btn-outline-primary" title="Скачать">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <?php if($file->is_image): ?>
                                                <button type="button" class="btn btn-outline-info" onclick="previewImage('<?php echo e(Storage::url($file->path)); ?>', '<?php echo e($file->original_name); ?>')" title="Просмотр">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            <?php endif; ?>
                                            <form action="<?php echo e(route('files.delete', $file->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Удалить файл <?php echo e($file->original_name); ?>?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-outline-danger" title="Удалить">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Пагинация -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($userFiles->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-folder2-open display-1 text-muted"></i>
                        <h4 class="mt-3">Файлов пока нет</h4>
                        <p class="text-muted">Загрузите первый файл, используя форму выше</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для просмотра изображений -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewTitle">Просмотр изображения</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imagePreviewImg" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Функция предварительного просмотра изображений
function previewImage(url, filename) {
    document.getElementById('imagePreviewImg').src = url;
    document.getElementById('imagePreviewTitle').textContent = filename;
    new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
}

// Обновление информации о файле при выборе
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const size = (file.size / 1024 / 1024).toFixed(2);
        const maxSize = 10;
        
        if (size > maxSize) {
            alert(`Файл слишком большой (${size}MB). Максимальный размер: ${maxSize}MB`);
            this.value = '';
            return;
        }
        
        // Можно добавить предварительный просмотр для изображений
        if (file.type.startsWith('image/')) {
            // Логика предварительного просмотра
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/files/index.blade.php ENDPATH**/ ?>
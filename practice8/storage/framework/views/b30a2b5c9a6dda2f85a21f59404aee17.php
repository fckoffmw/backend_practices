<?php $__env->startSection('title', 'SVG Генератор - Practice 8'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">🎨 SVG Генератор</h1>
        <p class="lead">
            Генерация уникальных SVG изображений на основе чисел. 
            Каждое число создает уникальную композицию из геометрических фигур.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Генератор</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('services.drawer.generate')); ?>" method="POST" target="_blank">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="number" class="form-label">Введите число (0-999999):</label>
                        <input 
                            type="number" 
                            class="form-control <?php $__errorArgs = ['number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="number" 
                            name="number" 
                            min="0" 
                            max="999999"
                            value="<?php echo e(old('number', '12345')); ?>"
                            required
                        >
                        <?php $__errorArgs = ['number'];
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
                            Число будет использовано для генерации уникального SVG изображения
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-image"></i> Сгенерировать SVG
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Примеры -->
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Примеры</h4>
            </div>
            <div class="card-body">
                <p>Попробуйте эти числа для примера:</p>
                <div class="d-flex flex-wrap gap-2">
                    <?php $__currentLoopData = [123, 456, 789, 1024, 2048, 9999]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $example): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button 
                            type="button" 
                            class="btn btn-outline-secondary btn-sm"
                            onclick="document.getElementById('number').value = <?php echo e($example); ?>"
                        >
                            <?php echo e($example); ?>

                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Как это работает</h4>
            </div>
            <div class="card-body">
                <h5>Алгоритм генерации:</h5>
                <ol>
                    <li><strong>Цветовая палитра:</strong> Генерируется на основе числа с использованием HSL</li>
                    <li><strong>Геометрические фигуры:</strong> Каждая цифра числа создает определенную фигуру:
                        <ul class="mt-2">
                            <li>0 → Круг</li>
                            <li>1 → Линия</li>
                            <li>2 → Прямоугольник</li>
                            <li>3 → Треугольник</li>
                            <li>4 → Ромб</li>
                            <li>5 → Пятиугольник</li>
                            <li>6 → Шестиугольник</li>
                            <li>7 → Звезда</li>
                            <li>8 → Восьмиугольник</li>
                            <li>9 → Спираль</li>
                        </ul>
                    </li>
                    <li><strong>Расположение:</strong> Фигуры размещаются по кругу вокруг центра</li>
                    <li><strong>Декорации:</strong> Добавляются случайные точки на основе числа</li>
                </ol>
                
                <div class="alert alert-info mt-3">
                    <strong>Совет:</strong> Одинаковые числа всегда генерируют одинаковые изображения, 
                    что позволяет создавать воспроизводимые визуальные представления данных.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Технические детали -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Технические особенности</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h5>Формат вывода</h5>
                        <ul>
                            <li>SVG (Scalable Vector Graphics)</li>
                            <li>Размер: 400×300 пикселей</li>
                            <li>Векторная графика</li>
                            <li>Поддержка всех браузеров</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Ограничения</h5>
                        <ul>
                            <li>Числа от 0 до 999,999</li>
                            <li>Только положительные числа</li>
                            <li>Целые числа</li>
                            <li>Генерация в реальном времени</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Применение</h5>
                        <ul>
                            <li>Визуализация данных</li>
                            <li>Уникальные аватары</li>
                            <li>Генеративное искусство</li>
                            <li>Идентификаторы</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Автоматическая генерация при изменении числа
document.getElementById('number').addEventListener('input', function() {
    const value = this.value;
    if (value && value >= 0 && value <= 999999) {
        // Можно добавить предварительный просмотр
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/services/drawer.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Сортировка массивов - Practice 8'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">🔢 Сортировка массивов</h1>
        <p class="lead">
            Сортировка массивов различными алгоритмами с анализом производительности.
            Сравните эффективность разных алгоритмов сортировки.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Сортировка</h3>
            </div>
            <div class="card-body">
                <form id="sortForm">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="array" class="form-label">Массив для сортировки:</label>
                        <textarea 
                            class="form-control <?php $__errorArgs = ['array'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="array" 
                            name="array" 
                            rows="3"
                            placeholder="Введите числа через запятую или пробел, например: 64, 34, 25, 12, 22, 11, 90"
                            required
                        ><?php echo e(old('array', '64, 34, 25, 12, 22, 11, 90')); ?></textarea>
                        <?php $__errorArgs = ['array'];
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
                            Поддерживаются форматы: "1,2,3" или "1 2 3" или "[1,2,3]"
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="algorithm" class="form-label">Алгоритм сортировки:</label>
                        <select 
                            class="form-select <?php $__errorArgs = ['algorithm'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="algorithm" 
                            name="algorithm"
                            required
                        >
                            <option value="">Выберите алгоритм</option>
                            <?php $__currentLoopData = $algorithms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(old('algorithm') == $key ? 'selected' : ''); ?>>
                                    <?php echo e($name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['algorithm'];
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
                    
                    <button type="submit" class="btn btn-primary" id="sortButton">
                        <span class="spinner-border spinner-border-sm d-none" id="spinner"></span>
                        Сортировать
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Примеры массивов -->
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Примеры массивов</h4>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Случайный:</strong>
                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="setArray('64, 34, 25, 12, 22, 11, 90')">
                        Использовать
                    </button>
                    <br><code>64, 34, 25, 12, 22, 11, 90</code>
                </div>
                
                <div class="mb-2">
                    <strong>Обратно отсортированный:</strong>
                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="setArray('10, 9, 8, 7, 6, 5, 4, 3, 2, 1')">
                        Использовать
                    </button>
                    <br><code>10, 9, 8, 7, 6, 5, 4, 3, 2, 1</code>
                </div>
                
                <div class="mb-2">
                    <strong>Большой массив:</strong>
                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="generateRandomArray(50)">
                        Сгенерировать
                    </button>
                    <br><small class="text-muted">50 случайных чисел</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <!-- Результат сортировки -->
        <div class="card" id="resultCard" style="display: none;">
            <div class="card-header">
                <h4 class="mb-0">Результат сортировки</h4>
            </div>
            <div class="card-body" id="resultContent">
                <!-- Результат будет загружен через AJAX -->
            </div>
        </div>
        
        <!-- Информация об алгоритмах -->
        <div class="card" id="infoCard">
            <div class="card-header">
                <h4 class="mb-0">Алгоритмы сортировки</h4>
            </div>
            <div class="card-body">
                <div class="accordion" id="algorithmsAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#merge">
                                Сортировка слиянием (Merge Sort)
                            </button>
                        </h2>
                        <div id="merge" class="accordion-collapse collapse" data-bs-parent="#algorithmsAccordion">
                            <div class="accordion-body">
                                <strong>Сложность:</strong> O(n log n)<br>
                                <strong>Стабильная:</strong> Да<br>
                                <strong>Описание:</strong> Рекурсивно делит массив пополам и сливает отсортированные части.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#quick">
                                Быстрая сортировка (Quick Sort)
                            </button>
                        </h2>
                        <div id="quick" class="accordion-collapse collapse" data-bs-parent="#algorithmsAccordion">
                            <div class="accordion-body">
                                <strong>Сложность:</strong> O(n log n) в среднем, O(n²) в худшем<br>
                                <strong>Стабильная:</strong> Нет<br>
                                <strong>Описание:</strong> Выбирает опорный элемент и разделяет массив на части.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bubble">
                                Пузырьковая сортировка (Bubble Sort)
                            </button>
                        </h2>
                        <div id="bubble" class="accordion-collapse collapse" data-bs-parent="#algorithmsAccordion">
                            <div class="accordion-body">
                                <strong>Сложность:</strong> O(n²)<br>
                                <strong>Стабильная:</strong> Да<br>
                                <strong>Описание:</strong> Сравнивает соседние элементы и меняет их местами.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($result): ?>
<!-- Отображение результата из серверной части -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Результат сортировки</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Исходный массив:</h5>
                        <div class="bg-light p-2 rounded">
                            <code>[<?php echo e(implode(', ', $result['original'])); ?>]</code>
                        </div>
                        
                        <h5 class="mt-3">Отсортированный массив:</h5>
                        <div class="bg-success bg-opacity-10 p-2 rounded">
                            <code>[<?php echo e(implode(', ', $result['sorted'])); ?>]</code>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Статистика:</h5>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Алгоритм:</strong></td>
                                <td><?php echo e($algorithms[$result['algorithm']]); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Время выполнения:</strong></td>
                                <td><?php echo e($result['execution_time']); ?> мс</td>
                            </tr>
                            <tr>
                                <td><strong>Сравнения:</strong></td>
                                <td><?php echo e(number_format($result['comparisons'])); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Перестановки:</strong></td>
                                <td><?php echo e(number_format($result['swaps'])); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// AJAX сортировка
document.getElementById('sortForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const button = document.getElementById('sortButton');
    const spinner = document.getElementById('spinner');
    const resultCard = document.getElementById('resultCard');
    const infoCard = document.getElementById('infoCard');
    
    // Показываем спиннер
    button.disabled = true;
    spinner.classList.remove('d-none');
    
    const formData = new FormData(this);
    
    fetch('<?php echo e(route("services.sort.sort")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayResult(data.result);
            resultCard.style.display = 'block';
            infoCard.style.display = 'none';
        } else {
            alert('Ошибка: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при сортировке');
    })
    .finally(() => {
        button.disabled = false;
        spinner.classList.add('d-none');
    });
});

function displayResult(result) {
    const algorithms = <?php echo json_encode($algorithms, 15, 512) ?>;
    
    document.getElementById('resultContent').innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h5>Исходный массив:</h5>
                <div class="bg-light p-2 rounded mb-3">
                    <code>[${result.original.join(', ')}]</code>
                </div>
                
                <h5>Отсортированный массив:</h5>
                <div class="bg-success bg-opacity-10 p-2 rounded">
                    <code>[${result.sorted.join(', ')}]</code>
                </div>
            </div>
            
            <div class="col-md-6">
                <h5>Статистика:</h5>
                <table class="table table-sm">
                    <tr>
                        <td><strong>Алгоритм:</strong></td>
                        <td>${algorithms[result.algorithm]}</td>
                    </tr>
                    <tr>
                        <td><strong>Время выполнения:</strong></td>
                        <td>${result.execution_time} мс</td>
                    </tr>
                    <tr>
                        <td><strong>Сравнения:</strong></td>
                        <td>${result.comparisons.toLocaleString()}</td>
                    </tr>
                    <tr>
                        <td><strong>Перестановки:</strong></td>
                        <td>${result.swaps.toLocaleString()}</td>
                    </tr>
                </table>
            </div>
        </div>
    `;
}

function setArray(arrayString) {
    document.getElementById('array').value = arrayString;
}

function generateRandomArray(size) {
    const numbers = [];
    for (let i = 0; i < size; i++) {
        numbers.push(Math.floor(Math.random() * 100) + 1);
    }
    setArray(numbers.join(', '));
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/services/sort.blade.php ENDPATH**/ ?>
<h1><?= htmlspecialchars($title) ?></h1>

<p>Сортировка массивов различными алгоритмами с анализом производительности.</p>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin: 20px 0;">
    
    <!-- Форма ввода -->
    <div>
        <h2>Настройки сортировки</h2>
        
        <form method="POST" action="/services/sort">
            <div class="form-group">
                <label for="array">Массив чисел:</label>
                <textarea id="array" name="array" class="form-control" rows="4" 
                          placeholder="Введите числа через запятую или пробел&#10;Например: 64, 34, 25, 12, 22, 11, 90"><?= htmlspecialchars($array) ?></textarea>
                <small style="color: #666;">Поддерживаются форматы: "1,2,3" или "1 2 3" или "[1,2,3]"</small>
            </div>
            
            <div class="form-group">
                <label for="algorithm">Алгоритм сортировки:</label>
                <select id="algorithm" name="algorithm" class="form-control">
                    <?php foreach ($algorithms as $key => $name): ?>
                        <option value="<?= $key ?>" <?= $algorithm === $key ? 'selected' : '' ?>>
                            <?= htmlspecialchars($name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Сортировать</button>
                <button type="button" onclick="generateRandomArray()" class="btn btn-secondary">Случайный массив</button>
                <button type="button" onclick="clearArray()" class="btn btn-secondary">Очистить</button>
            </div>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <strong>Ошибка:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Информация об алгоритмах -->
        <div style="background: #e9ecef; padding: 15px; border-radius: 4px; margin: 20px 0;">
            <h3>Сложность алгоритмов:</h3>
            <table style="width: 100%; font-size: 14px;">
                <tr><td><strong>Merge Sort:</strong></td><td>O(n log n)</td><td>Стабильная</td></tr>
                <tr><td><strong>Quick Sort:</strong></td><td>O(n log n)</td><td>Нестабильная</td></tr>
                <tr><td><strong>Heap Sort:</strong></td><td>O(n log n)</td><td>Нестабильная</td></tr>
                <tr><td><strong>Insertion Sort:</strong></td><td>O(n²)</td><td>Стабильная</td></tr>
                <tr><td><strong>Selection Sort:</strong></td><td>O(n²)</td><td>Нестабильная</td></tr>
                <tr><td><strong>Bubble Sort:</strong></td><td>O(n²)</td><td>Стабильная</td></tr>
            </table>
        </div>
    </div>

    <!-- Результат -->
    <div>
        <h2>Результат сортировки</h2>
        
        <?php if ($result): ?>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                
                <div class="form-group">
                    <label><strong>Исходный массив:</strong></label>
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; font-family: monospace;">
                        [<?= implode(', ', $result['original']) ?>]
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Отсортированный массив:</strong></label>
                    <div style="background: #d4edda; padding: 10px; border-radius: 4px; font-family: monospace;">
                        [<?= implode(', ', $result['sorted']) ?>]
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Статистика выполнения:</strong></label>
                    <table class="table">
                        <tr>
                            <td>Алгоритм:</td>
                            <td><?= htmlspecialchars($algorithms[$result['algorithm']] ?? $result['algorithm']) ?></td>
                        </tr>
                        <tr>
                            <td>Время выполнения:</td>
                            <td><?= $result['execution_time'] ?> мс</td>
                        </tr>
                        <tr>
                            <td>Количество сравнений:</td>
                            <td><?= number_format($result['comparisons']) ?></td>
                        </tr>
                        <tr>
                            <td>Количество перестановок:</td>
                            <td><?= number_format($result['swaps']) ?></td>
                        </tr>
                        <tr>
                            <td>Размер массива:</td>
                            <td><?= count($result['original']) ?> элементов</td>
                        </tr>
                    </table>
                </div>

                <div class="form-group">
                    <button onclick="copyResult()" class="btn btn-secondary">Копировать результат</button>
                    <button onclick="compareAlgorithms()" class="btn btn-secondary">Сравнить алгоритмы</button>
                </div>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 60px; color: #666; background: #f8f9fa; border-radius: 8px;">
                <p>Введите массив чисел и выберите алгоритм для сортировки</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function generateRandomArray() {
    const size = Math.floor(Math.random() * 15) + 5; // 5-20 элементов
    const array = [];
    
    for (let i = 0; i < size; i++) {
        array.push(Math.floor(Math.random() * 100) + 1);
    }
    
    document.getElementById('array').value = array.join(', ');
}

function clearArray() {
    document.getElementById('array').value = '';
}

function copyResult() {
    const result = document.querySelector('[style*="background: #d4edda"]').textContent;
    navigator.clipboard.writeText(result).then(() => {
        alert('Результат скопирован в буфер обмена!');
    });
}

async function compareAlgorithms() {
    const arrayInput = document.getElementById('array').value;
    if (!arrayInput) {
        alert('Сначала введите массив для сортировки');
        return;
    }
    
    const algorithms = ['merge', 'quick', 'bubble', 'insertion', 'selection', 'heap'];
    const results = [];
    
    for (const algorithm of algorithms) {
        try {
            const response = await fetch('/services/sort/sort', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `array=${encodeURIComponent(arrayInput)}&algorithm=${algorithm}`,
                credentials: 'include'
            });
            
            const data = await response.json();
            if (data.success) {
                results.push({
                    algorithm: algorithm,
                    time: data.result.execution_time,
                    comparisons: data.result.comparisons,
                    swaps: data.result.swaps
                });
            }
        } catch (error) {
            console.error(`Ошибка для алгоритма ${algorithm}:`, error);
        }
    }
    
    // Показываем результаты сравнения
    showComparisonResults(results);
}

function showComparisonResults(results) {
    const algorithms = {
        'merge': 'Merge Sort',
        'quick': 'Quick Sort', 
        'bubble': 'Bubble Sort',
        'insertion': 'Insertion Sort',
        'selection': 'Selection Sort',
        'heap': 'Heap Sort'
    };
    
    let html = '<h3>Сравнение алгоритмов:</h3><table class="table"><tr><th>Алгоритм</th><th>Время (мс)</th><th>Сравнения</th><th>Перестановки</th></tr>';
    
    results.forEach(result => {
        html += `<tr>
            <td>${algorithms[result.algorithm]}</td>
            <td>${result.time}</td>
            <td>${result.comparisons.toLocaleString()}</td>
            <td>${result.swaps.toLocaleString()}</td>
        </tr>`;
    });
    
    html += '</table>';
    
    // Создаем модальное окно
    const modal = document.createElement('div');
    modal.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center;';
    
    const content = document.createElement('div');
    content.style.cssText = 'background: white; padding: 30px; border-radius: 8px; max-width: 600px; max-height: 80%; overflow: auto;';
    content.innerHTML = html + '<button onclick="this.closest(\'div[style*=\"position: fixed\"]\').remove()" class="btn" style="margin-top: 20px;">Закрыть</button>';
    
    modal.appendChild(content);
    document.body.appendChild(modal);
}
</script>
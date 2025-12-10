<h1><?= htmlspecialchars($title) ?></h1>

<p>Генератор SVG изображений на основе числовых данных. Каждая цифра числа преобразуется в уникальную геометрическую фигуру.</p>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin: 20px 0;">
    
    <!-- Форма ввода -->
    <div>
        <h2>Генерация SVG</h2>
        
        <form method="GET" action="/services/drawer">
            <div class="form-group">
                <label for="number">Введите число:</label>
                <input type="number" id="number" name="num" class="form-control" 
                       value="<?= htmlspecialchars($number) ?>" 
                       placeholder="Например: 12345" min="0" max="999999">
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Сгенерировать SVG</button>
                <button type="button" onclick="generateRandom()" class="btn btn-secondary">Случайное число</button>
            </div>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <strong>Ошибка:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div style="background: #e9ecef; padding: 15px; border-radius: 4px; margin: 20px 0;">
            <h3>Как это работает:</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li><strong>0</strong> → Круг</li>
                <li><strong>1</strong> → Линия</li>
                <li><strong>2</strong> → Прямоугольник</li>
                <li><strong>3</strong> → Треугольник</li>
                <li><strong>4</strong> → Ромб</li>
                <li><strong>5</strong> → Пятиугольник</li>
                <li><strong>6</strong> → Шестиугольник</li>
                <li><strong>7</strong> → Звезда</li>
                <li><strong>8</strong> → Восьмиугольник</li>
                <li><strong>9</strong> → Спираль</li>
            </ul>
            <p>Цвета генерируются на основе введенного числа для создания уникальной палитры.</p>
        </div>
    </div>

    <!-- Результат -->
    <div>
        <h2>Результат</h2>
        
        <?php if ($svg): ?>
            <div style="text-align: center; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <?= $svg ?>
            </div>
            
            <div style="margin-top: 15px;">
                <button onclick="downloadSvg()" class="btn btn-secondary">Скачать SVG</button>
                <button onclick="copySvgCode()" class="btn btn-secondary">Копировать код</button>
            </div>
            
            <details style="margin-top: 15px;">
                <summary style="cursor: pointer; font-weight: bold;">Показать SVG код</summary>
                <textarea id="svgCode" readonly style="width: 100%; height: 200px; margin-top: 10px; font-family: monospace; font-size: 12px;"><?= htmlspecialchars($svg) ?></textarea>
            </details>
        <?php else: ?>
            <div style="text-align: center; padding: 60px; color: #666; background: #f8f9fa; border-radius: 8px;">
                <p>Введите число и нажмите "Сгенерировать SVG" для создания изображения</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function generateRandom() {
    const randomNumber = Math.floor(Math.random() * 999999);
    document.getElementById('number').value = randomNumber;
}

function downloadSvg() {
    const svgCode = document.getElementById('svgCode').value;
    const blob = new Blob([svgCode], { type: 'image/svg+xml' });
    const url = URL.createObjectURL(blob);
    
    const a = document.createElement('a');
    a.href = url;
    a.download = `generated_${document.getElementById('number').value || 'image'}.svg`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function copySvgCode() {
    const svgCode = document.getElementById('svgCode');
    svgCode.select();
    document.execCommand('copy');
    
    // Показываем уведомление
    const originalText = svgCode.placeholder;
    svgCode.placeholder = 'SVG код скопирован в буфер обмена!';
    setTimeout(() => {
        svgCode.placeholder = originalText;
    }, 2000);
}

// Автоматическая генерация при изменении числа (с задержкой)
let timeout;
document.getElementById('number').addEventListener('input', function() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        if (this.value && this.value.length > 0) {
            window.location.href = `/services/drawer?num=${this.value}`;
        }
    }, 1000);
});
</script>
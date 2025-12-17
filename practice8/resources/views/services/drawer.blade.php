@extends('layouts.app')

@section('title', 'SVG Генератор - Practice 8')

@section('content')
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
                <form action="{{ route('services.drawer.generate') }}" method="POST" target="_blank">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="number" class="form-label">Введите число (0-999999):</label>
                        <input 
                            type="number" 
                            class="form-control @error('number') is-invalid @enderror" 
                            id="number" 
                            name="number" 
                            min="0" 
                            max="999999"
                            value="{{ old('number', '12345') }}"
                            required
                        >
                        @error('number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Число будет использовано для генерации уникального SVG изображения
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-image"></i> Сгенерировать SVG
                        </button>
                        <button type="button" class="btn btn-success" onclick="downloadSvg()" id="downloadBtn" style="display: none;">
                            <i class="bi bi-download"></i> Скачать SVG
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="copySvgCode()" id="copyBtn" style="display: none;">
                            <i class="bi bi-clipboard"></i> Копировать код
                        </button>
                    </div>
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
                    @foreach([123, 456, 789, 1024, 2048, 9999] as $example)
                        <button 
                            type="button" 
                            class="btn btn-outline-secondary btn-sm"
                            onclick="document.getElementById('number').value = {{ $example }}"
                        >
                            {{ $example }}
                        </button>
                    @endforeach
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
    
    <div class="col-md-6">
        <!-- Результат генерации -->
        <div class="card" id="resultCard" style="display: none;">
            <div class="card-header">
                <h4 class="mb-0">Сгенерированное изображение</h4>
            </div>
            <div class="card-body text-center">
                <div id="svgPreview" class="mb-3"></div>
                <textarea id="svgCode" class="form-control" rows="8" readonly style="font-family: monospace; font-size: 12px;"></textarea>
            </div>
        </div>
        
        <!-- Информация -->
        <div class="card mt-4" id="infoCard">
            <div class="card-header">
                <h4 class="mb-0">Информация</h4>
            </div>
            <div class="card-body">
                <h5>Возможности генератора:</h5>
                <ul>
                    <li><strong>Уникальность:</strong> Каждое число создает уникальное изображение</li>
                    <li><strong>Воспроизводимость:</strong> Одинаковые числа дают одинаковый результат</li>
                    <li><strong>Масштабируемость:</strong> SVG формат позволяет изменять размер без потери качества</li>
                    <li><strong>Экспорт:</strong> Возможность скачивания и копирования кода</li>
                </ul>
                
                <h5 class="mt-3">Применение:</h5>
                <ul>
                    <li>Генерация уникальных аватаров</li>
                    <li>Создание идентификаторов</li>
                    <li>Визуализация числовых данных</li>
                    <li>Генеративное искусство</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Обработка формы генерации
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const number = document.getElementById('number').value;
    if (!number || number < 0 || number > 999999) {
        alert('Введите корректное число от 0 до 999999');
        return;
    }
    
    // Генерируем SVG через AJAX
    fetch('{{ route("services.drawer.generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        },
        body: `number=${encodeURIComponent(number)}`
    })
    .then(response => response.text())
    .then(svgCode => {
        // Отображаем результат
        document.getElementById('svgPreview').innerHTML = svgCode;
        document.getElementById('svgCode').value = svgCode;
        document.getElementById('resultCard').style.display = 'block';
        document.getElementById('infoCard').style.display = 'none';
        document.getElementById('downloadBtn').style.display = 'inline-block';
        document.getElementById('copyBtn').style.display = 'inline-block';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при генерации SVG');
    });
});

// Функция скачивания SVG
function downloadSvg() {
    const number = document.getElementById('number').value;
    const svgCode = document.getElementById('svgCode').value;
    
    if (!svgCode) {
        alert('Сначала сгенерируйте SVG');
        return;
    }
    
    // Создаем ссылку для скачивания
    const blob = new Blob([svgCode], { type: 'image/svg+xml' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `generated_${number || 'image'}.svg`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Функция копирования кода
function copySvgCode() {
    const svgCode = document.getElementById('svgCode').value;
    
    if (!svgCode) {
        alert('Сначала сгенерируйте SVG');
        return;
    }
    
    navigator.clipboard.writeText(svgCode).then(() => {
        // Временно меняем текст кнопки
        const btn = document.getElementById('copyBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i> Скопировано!';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    }).catch(err => {
        console.error('Ошибка копирования:', err);
        alert('Не удалось скопировать код');
    });
}

// Автоматическая генерация при изменении числа
document.getElementById('number').addEventListener('input', function() {
    const value = this.value;
    if (value && value >= 0 && value <= 999999) {
        // Скрываем кнопки до новой генерации
        document.getElementById('downloadBtn').style.display = 'none';
        document.getElementById('copyBtn').style.display = 'none';
    }
});
</script>
@endpush
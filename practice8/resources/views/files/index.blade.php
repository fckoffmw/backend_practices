@extends('layouts.app')

@section('title', __('app.file_management') . ' - Practice 8')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>📁 {{ __('app.file_management') }}</h1>
            <div class="badge bg-info fs-6">{{ $diskUsage['file_count'] }} файлов</div>
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
                        <h5 class="mb-1">{{ __('app.disk_usage') }}</h5>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $diskUsage['usage_percent'] }}%">
                                {{ $diskUsage['usage_percent'] }}%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">{{ __('app.used') }}: {{ $diskUsage['total_size_formatted'] }}</small>
                            <small class="text-muted">{{ __('app.limit') }}: {{ $diskUsage['user_limit_formatted'] }}</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="text-muted">
                            <strong>{{ __('app.remaining') }}:</strong> {{ $diskUsage['remaining_formatted'] }}
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
                <h5 class="mb-0">📤 {{ __('app.upload_file') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">{{ __('app.upload_file') }}</label>
                                <input 
                                    type="file" 
                                    class="form-control @error('file') is-invalid @enderror" 
                                    id="file" 
                                    name="file"
                                    accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.csv,.xlsx,.svg"
                                    required
                                >
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Максимальный размер: 10MB. Разрешенные типы: JPG, PNG, PDF, DOC, TXT, CSV, SVG
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('app.description') }} ({{ __('app.optional') }})</label>
                                <textarea 
                                    class="form-control @error('description') is-invalid @enderror" 
                                    id="description" 
                                    name="description"
                                    rows="3"
                                    placeholder="Краткое описание файла..."
                                >{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cloud-upload"></i> {{ __('app.upload_file') }}
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
                <h5 class="mb-0">📋 {{ __('app.my_files') }}</h5>
            </div>
            <div class="card-body">
                @if($userFiles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('app.file') }}</th>
                                    <th>{{ __('app.file_size') }}</th>
                                    <th>{{ __('app.file_type') }}</th>
                                    <th>{{ __('app.description') }}</th>
                                    <th>{{ __('app.upload_date') }}</th>
                                    <th>{{ __('app.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userFiles as $file)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi {{ $file->icon }} me-2 text-primary"></i>
                                            <div>
                                                <strong>{{ $file->original_name }}</strong>
                                                @if($file->is_image)
                                                    <br><small class="text-muted">Изображение</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $file->formatted_size }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($file->description)
                                            {{ Str::limit($file->description, 50) }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $file->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('files.download', $file->id) }}" class="btn btn-outline-primary" title="Скачать">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            @if($file->is_image)
                                                <button type="button" class="btn btn-outline-info" onclick="previewImage('{{ Storage::url($file->path) }}', '{{ $file->original_name }}')" title="Просмотр">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            @endif
                                            <form action="{{ route('files.delete', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить файл {{ $file->original_name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Удалить">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Пагинация -->
                    <div class="d-flex justify-content-center">
                        {{ $userFiles->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-folder2-open display-1 text-muted"></i>
                        <h4 class="mt-3">Файлов пока нет</h4>
                        <p class="text-muted">Загрузите первый файл, используя форму выше</p>
                    </div>
                @endif
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
@endsection

@push('scripts')
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
@endpush
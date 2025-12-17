<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель пользовательских файлов
 */
class UserFile extends Model
{
    use HasFactory;

    protected $table = 'user_files';

    protected $fillable = [
        'user_id',
        'original_name',
        'filename',
        'path',
        'size',
        'mime_type',
        'description',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить размер файла в читаемом формате
     */
    public function getFormattedSizeAttribute()
    {
        return $this->formatBytes($this->size);
    }

    /**
     * Проверить, является ли файл изображением
     */
    public function getIsImageAttribute()
    {
        return in_array($this->mime_type, [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/svg+xml',
            'image/webp'
        ]);
    }

    /**
     * Получить иконку файла
     */
    public function getIconAttribute()
    {
        if ($this->is_image) {
            return 'bi-image';
        }

        return match($this->mime_type) {
            'application/pdf' => 'bi-file-earmark-pdf',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'bi-file-earmark-word',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'bi-file-earmark-excel',
            'text/plain' => 'bi-file-earmark-text',
            'text/csv' => 'bi-file-earmark-spreadsheet',
            default => 'bi-file-earmark'
        };
    }

    /**
     * Форматирование размера файла
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
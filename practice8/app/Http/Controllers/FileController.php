<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Контроллер для работы с файлами
 * Загрузка, скачивание и управление файлами
 */
class FileController extends Controller
{
    /**
     * Конструктор - требует аутентификации
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Показать страницу управления файлами
     */
    public function index()
    {
        $userFiles = $this->getUserFiles();
        $diskUsage = $this->getDiskUsage();
        
        return view('files.index', compact('userFiles', 'diskUsage'));
    }

    /**
     * Загрузить файл
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Максимум 10MB
            'description' => 'nullable|string|max:255',
        ], [
            'file.required' => 'Выберите файл для загрузки',
            'file.max' => 'Размер файла не должен превышать 10MB',
            'description.max' => 'Описание слишком длинное',
        ]);

        $file = $request->file('file');
        
        // Проверяем тип файла
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'csv', 'xlsx', 'svg'];
        $extension = $file->getClientOriginalExtension();
        
        if (!in_array(strtolower($extension), $allowedTypes)) {
            return back()->withErrors(['file' => 'Недопустимый тип файла. Разрешены: ' . implode(', ', $allowedTypes)]);
        }

        // Генерируем уникальное имя файла
        $filename = Str::uuid() . '.' . $extension;
        $originalName = $file->getClientOriginalName();
        
        // Сохраняем файл
        $path = $file->storeAs('uploads/' . Auth::id(), $filename, 'public');
        
        // Сохраняем информацию о файле в базе данных
        $fileRecord = Auth::user()->files()->create([
            'original_name' => $originalName,
            'filename' => $filename,
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Файл "' . $originalName . '" успешно загружен');
    }

    /**
     * Скачать файл
     */
    public function download($id)
    {
        $file = Auth::user()->files()->findOrFail($id);
        
        if (!Storage::disk('public')->exists($file->path)) {
            return back()->withErrors(['error' => 'Файл не найден на сервере']);
        }

        return Storage::disk('public')->download($file->path, $file->original_name);
    }

    /**
     * Удалить файл
     */
    public function delete($id)
    {
        $file = Auth::user()->files()->findOrFail($id);
        
        // Удаляем файл с диска
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }
        
        // Удаляем запись из базы данных
        $file->delete();

        return back()->with('success', 'Файл "' . $file->original_name . '" удален');
    }

    /**
     * Получить файлы пользователя
     */
    private function getUserFiles()
    {
        return Auth::user()->files()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    /**
     * Получить информацию об использовании диска
     */
    private function getDiskUsage()
    {
        $userFiles = Auth::user()->files;
        $totalSize = $userFiles->sum('size');
        $fileCount = $userFiles->count();
        
        // Лимит на пользователя (100MB)
        $userLimit = 100 * 1024 * 1024;
        $usagePercent = $userLimit > 0 ? round(($totalSize / $userLimit) * 100, 1) : 0;
        
        return [
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize),
            'file_count' => $fileCount,
            'user_limit' => $userLimit,
            'user_limit_formatted' => $this->formatBytes($userLimit),
            'usage_percent' => $usagePercent,
            'remaining' => $userLimit - $totalSize,
            'remaining_formatted' => $this->formatBytes($userLimit - $totalSize),
        ];
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
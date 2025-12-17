<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DrawerService;

/**
 * Контроллер SVG генератора
 * Миграция из Practice 7 с адаптацией под Laravel
 */
class DrawerController extends Controller
{
    private DrawerService $drawerService;

    public function __construct(DrawerService $drawerService)
    {
        $this->drawerService = $drawerService;
    }

    public function index()
    {
        return view('services.drawer');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric|min:0|max:999999'
        ], [
            'number.required' => 'Введите число',
            'number.numeric' => 'Значение должно быть числом',
            'number.min' => 'Число должно быть положительным',
            'number.max' => 'Число слишком большое (максимум 999999)'
        ]);

        try {
            $svg = $this->drawerService->generateSvg($request->number);
            
            return response($svg)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Disposition', 'inline; filename="generated_' . $request->number . '.svg"');
                
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['number' => $e->getMessage()]);
        }
    }

    /**
     * Скачать SVG файл
     */
    public function download(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric|min:0|max:999999'
        ]);

        try {
            $svg = $this->drawerService->generateSvg($request->number);
            $filename = 'generated_' . $request->number . '.svg';
            
            return response($svg)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['number' => $e->getMessage()]);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SortService;

/**
 * Контроллер сортировки массивов
 * Миграция из Practice 7 с адаптацией под Laravel
 */
class SortController extends Controller
{
    private SortService $sortService;

    public function __construct(SortService $sortService)
    {
        $this->sortService = $sortService;
    }

    public function index(Request $request)
    {
        $algorithms = $this->sortService->getAvailableAlgorithms();
        $result = null;

        // Если есть данные для сортировки
        if ($request->has('array') && $request->has('algorithm')) {
            try {
                $result = $this->sortService->sort(
                    $request->input('array'),
                    $request->input('algorithm')
                );
            } catch (\InvalidArgumentException $e) {
                return back()->withErrors(['array' => $e->getMessage()]);
            }
        }

        return view('services.sort', compact('algorithms', 'result'));
    }

    public function sort(Request $request)
    {
        $request->validate([
            'array' => 'required|string',
            'algorithm' => 'required|string|in:merge,quick,bubble,insertion,selection,heap'
        ], [
            'array.required' => 'Введите массив для сортировки',
            'algorithm.required' => 'Выберите алгоритм сортировки',
            'algorithm.in' => 'Неизвестный алгоритм сортировки'
        ]);

        try {
            $result = $this->sortService->sort(
                $request->input('array'),
                $request->input('algorithm')
            );

            return response()->json([
                'success' => true,
                'result' => $result
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
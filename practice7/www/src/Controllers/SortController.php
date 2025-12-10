<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\SortService;

/**
 * Контроллер для сортировки массивов
 */
class SortController extends Controller
{
    private SortService $sortService;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->sortService = new SortService();
    }

    /**
     * Показать форму сортировки
     */
    public function index(): void
    {
        $array = $_POST['array'] ?? '';
        $algorithm = $_POST['algorithm'] ?? 'merge';
        $result = null;
        $error = '';

        if (!empty($array)) {
            try {
                $result = $this->sortService->sort($array, $algorithm);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $this->render('services/sort', [
            'title' => 'Сортировка массивов',
            'user' => $this->getCurrentUser(),
            'array' => $array,
            'algorithm' => $algorithm,
            'result' => $result,
            'error' => $error,
            'algorithms' => $this->sortService->getAvailableAlgorithms(),
        ]);
    }

    /**
     * API для сортировки
     */
    public function sort(): void
    {
        $array = $_POST['array'] ?? '';
        $algorithm = $_POST['algorithm'] ?? 'merge';

        if (empty($array)) {
            $this->json(['error' => 'Массив не указан'], 400);
            return;
        }

        try {
            $result = $this->sortService->sort($array, $algorithm);
            
            $this->json([
                'success' => true,
                'input' => $array,
                'algorithm' => $algorithm,
                'result' => $result,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
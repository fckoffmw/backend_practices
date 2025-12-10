<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\DrawerService;

/**
 * Контроллер для генерации SVG изображений
 */
class DrawerController extends Controller
{
    private DrawerService $drawerService;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->drawerService = new DrawerService();
    }

    /**
     * Показать форму генерации SVG
     */
    public function index(): void
    {
        $number = $_GET['num'] ?? '';
        $svg = '';
        $error = '';

        if (!empty($number)) {
            try {
                $svg = $this->drawerService->generateSvg($number);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $this->render('services/drawer', [
            'title' => 'SVG Генератор',
            'user' => $this->getCurrentUser(),
            'number' => $number,
            'svg' => $svg,
            'error' => $error,
        ]);
    }

    /**
     * API для генерации SVG
     */
    public function generate(): void
    {
        $number = $_POST['number'] ?? $_GET['number'] ?? '';

        if (empty($number)) {
            $this->json(['error' => 'Число не указано'], 400);
            return;
        }

        try {
            $svg = $this->drawerService->generateSvg($number);
            
            $this->json([
                'success' => true,
                'number' => $number,
                'svg' => $svg,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
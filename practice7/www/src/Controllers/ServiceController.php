<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Контроллер сервисов
 */
class ServiceController extends Controller
{
    /**
     * Список сервисов
     */
    public function index(): void
    {
        $this->render('services/index', [
            'title' => 'Сервисы',
            'user' => $this->getCurrentUser(),
        ]);
    }
}
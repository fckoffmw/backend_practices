<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Контроллер главной страницы
 */
class HomeController extends Controller
{
    public function index(): void
    {
        $user = $this->getCurrentUser();
        
        $this->render('home/index', [
            'title' => 'Главная страница',
            'user' => $user,
            'appName' => $this->config['app']['name'],
            'version' => $this->config['app']['version'],
        ]);
    }
}
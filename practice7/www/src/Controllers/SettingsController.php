<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserRepository;
use App\Infrastructure\Database\DatabaseConnection;

/**
 * Контроллер настроек пользователя
 */
class SettingsController extends Controller
{
    private UserRepository $userRepository;

    public function __construct($container)
    {
        parent::__construct($container);
        
        $db = $this->container->get(DatabaseConnection::class);
        $this->userRepository = new UserRepository($db);
    }

    /**
     * Показать настройки
     */
    public function index(): void
    {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        
        $this->render('settings/index', [
            'title' => 'Настройки',
            'user' => $user,
        ]);
    }

    /**
     * Обновить настройки
     */
    public function update(): void
    {
        $this->requireAuth();
        
        $theme = $_POST['theme'] ?? 'light';
        $language = $_POST['language'] ?? 'ru';
        
        // Валидация
        $allowedThemes = ['light', 'dark', 'colorblind'];
        $allowedLanguages = ['ru', 'en', 'de'];
        
        if (!in_array($theme, $allowedThemes)) {
            $theme = 'light';
        }
        
        if (!in_array($language, $allowedLanguages)) {
            $language = 'ru';
        }
        
        // Обновление в БД
        $userId = $_SESSION['user_id'];
        $updated = $this->userRepository->updateSettings($userId, $theme, $language);
        
        if ($updated) {
            // Обновление сессии
            $_SESSION['user_theme'] = $theme;
            $_SESSION['user_language'] = $language;
            
            // Установка cookies для гостей
            setcookie('user_theme', $theme, time() + (86400 * 30), '/');
            setcookie('user_language', $language, time() + (86400 * 30), '/');
            
            $_SESSION['success_message'] = 'Настройки успешно сохранены';
        } else {
            $_SESSION['error_message'] = 'Ошибка при сохранении настроек';
        }
        
        $this->redirect('/settings');
    }
}
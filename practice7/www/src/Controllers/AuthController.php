<?php

namespace App\Controllers;

use App\Core\Controller;
use App\UseCases\AuthenticateUser;
use App\Models\UserRepository;
use App\Infrastructure\Database\DatabaseConnection;

/**
 * Контроллер аутентификации
 */
class AuthController extends Controller
{
    private AuthenticateUser $authenticateUser;

    public function __construct($container)
    {
        parent::__construct($container);
        
        $db = $this->container->get(DatabaseConnection::class);
        $userRepository = new UserRepository($db);
        $this->authenticateUser = new AuthenticateUser($userRepository);
    }

    /**
     * Показать форму входа
     */
    public function showLogin(): void
    {
        // Если уже авторизован - редирект на главную
        if ($this->getCurrentUser()) {
            $this->redirect('/');
        }

        $this->render('auth/login', [
            'title' => 'Вход в систему',
            'error' => $_SESSION['login_error'] ?? null,
        ]);
        
        // Очищаем ошибку после показа
        unset($_SESSION['login_error']);
    }

    /**
     * Обработка входа
     */
    public function login(): void
    {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($login) || empty($password)) {
            $_SESSION['login_error'] = 'Заполните все поля';
            $this->redirect('/login');
        }

        $user = $this->authenticateUser->execute($login, $password);

        if ($user) {
            $this->authenticateUser->createSession($user);
            $this->redirect('/');
        } else {
            $_SESSION['login_error'] = 'Неверный логин или пароль';
            $this->redirect('/login');
        }
    }

    /**
     * Выход из системы
     */
    public function logout(): void
    {
        $this->authenticateUser->destroySession();
        $this->redirect('/');
    }
}
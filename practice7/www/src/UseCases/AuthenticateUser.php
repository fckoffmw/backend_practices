<?php

namespace App\UseCases;

use App\Entities\User;
use App\Models\UserRepository;

/**
 * Use Case для аутентификации пользователя
 */
class AuthenticateUser
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Выполнение аутентификации
     */
    public function execute(string $login, string $password): ?User
    {
        // Поиск пользователя по логину
        $user = $this->userRepository->findByLogin($login);
        
        if (!$user) {
            return null;
        }

        // Проверка пароля
        if (!$user->verifyPassword($password)) {
            return null;
        }

        return $user;
    }

    /**
     * Создание сессии для пользователя
     */
    public function createSession(User $user): void
    {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_login'] = $user->getLogin();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['user_surname'] = $user->getSurname();
        $_SESSION['user_role'] = $user->getRole();
        $_SESSION['user_theme'] = $user->getTheme();
        $_SESSION['user_language'] = $user->getLanguage();
        $_SESSION['authenticated_at'] = time();
    }

    /**
     * Уничтожение сессии
     */
    public function destroySession(): void
    {
        session_destroy();
        session_start();
    }
}
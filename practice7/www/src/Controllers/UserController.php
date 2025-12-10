<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserRepository;
use App\Infrastructure\Database\DatabaseConnection;

/**
 * Контроллер пользователей
 */
class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct($container)
    {
        parent::__construct($container);
        
        $db = $this->container->get(DatabaseConnection::class);
        $this->userRepository = new UserRepository($db);
    }

    /**
     * Список пользователей
     */
    public function index(): void
    {
        $users = $this->userRepository->findAll();
        
        $this->render('users/index', [
            'title' => 'Пользователи',
            'user' => $this->getCurrentUser(),
            'users' => $users,
        ]);
    }

    /**
     * Просмотр пользователя
     */
    public function show(int $id): void
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            $_SESSION['error_message'] = 'Пользователь не найден';
            $this->redirect('/users');
        }

        $this->render('users/show', [
            'title' => 'Пользователь: ' . $user->getFullName(),
            'user' => $this->getCurrentUser(),
            'viewUser' => $user,
        ]);
    }
}
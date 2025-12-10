<?php

namespace App\Models;

use App\Entities\User;
use App\Infrastructure\Database\DatabaseConnection;

/**
 * Repository для работы с пользователями (Interface Adapters Layer)
 */
class UserRepository
{
    private DatabaseConnection $db;

    public function __construct(DatabaseConnection $db)
    {
        $this->db = $db;
    }

    /**
     * Поиск пользователя по ID
     */
    public function findById(int $id): ?User
    {
        $data = $this->db->fetchOne(
            "SELECT u.*, us.theme, us.language 
             FROM users u 
             LEFT JOIN user_settings us ON u.ID = us.user_id 
             WHERE u.ID = ?",
            [$id]
        );

        return $data ? $this->mapToEntity($data) : null;
    }

    /**
     * Поиск пользователя по логину
     */
    public function findByLogin(string $login): ?User
    {
        $data = $this->db->fetchOne(
            "SELECT u.*, us.theme, us.language 
             FROM users u 
             LEFT JOIN user_settings us ON u.ID = us.user_id 
             WHERE u.login = ?",
            [$login]
        );

        return $data ? $this->mapToEntity($data) : null;
    }

    /**
     * Получение всех пользователей
     */
    public function findAll(): array
    {
        $data = $this->db->fetchAll(
            "SELECT u.*, us.theme, us.language 
             FROM users u 
             LEFT JOIN user_settings us ON u.ID = us.user_id 
             ORDER BY u.ID"
        );
        
        return array_map([$this, 'mapToEntity'], $data);
    }

    /**
     * Сохранение пользователя
     */
    public function save(User $user): User
    {
        if ($user->getId()) {
            return $this->update($user);
        } else {
            return $this->create($user);
        }
    }

    /**
     * Создание нового пользователя
     */
    private function create(User $user): User
    {
        $id = $this->db->insert('users', [
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'is_admin' => $user->getRole() === 'admin',
        ]);

        // Создаем настройки пользователя
        $this->db->insert('user_settings', [
            'user_id' => $id,
            'theme' => $user->getTheme(),
            'language' => $user->getLanguage(),
        ]);

        $user->setId($id);
        return $user;
    }

    /**
     * Обновление пользователя
     */
    private function update(User $user): User
    {
        $this->db->update('users', [
            'login' => $user->getLogin(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'is_admin' => $user->getRole() === 'admin',
        ], ['ID' => $user->getId()]);

        // Обновляем настройки пользователя
        $this->db->query(
            "INSERT INTO user_settings (user_id, theme, language) 
             VALUES (?, ?, ?) 
             ON DUPLICATE KEY UPDATE theme = ?, language = ?",
            [
                $user->getId(),
                $user->getTheme(),
                $user->getLanguage(),
                $user->getTheme(),
                $user->getLanguage()
            ]
        );

        return $user;
    }

    /**
     * Удаление пользователя
     */
    public function delete(int $id): bool
    {
        return $this->db->delete('users', ['ID' => $id]) > 0;
    }

    /**
     * Обновление настроек пользователя
     */
    public function updateSettings(int $userId, string $theme, string $language): bool
    {
        $result = $this->db->query(
            "INSERT INTO user_settings (user_id, theme, language) 
             VALUES (?, ?, ?) 
             ON DUPLICATE KEY UPDATE theme = ?, language = ?",
            [$userId, $theme, $language, $theme, $language]
        );
        
        return $result->rowCount() > 0;
    }

    /**
     * Преобразование данных из БД в сущность
     */
    private function mapToEntity(array $data): User
    {
        return new User(
            login: $data['login'],
            password: $data['password'], // уже хешированный
            name: $data['name'] ?? '',
            surname: $data['surname'] ?? '',
            role: ($data['is_admin'] ?? false) ? 'admin' : 'user',
            theme: $data['theme'] ?? 'light',
            language: $data['language'] ?? 'ru',
            id: isset($data['ID']) ? (int) $data['ID'] : null,
            createdAt: isset($data['created_at']) ? new \DateTime($data['created_at']) : null
        );
    }
}
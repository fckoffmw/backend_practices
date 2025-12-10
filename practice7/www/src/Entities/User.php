<?php

namespace App\Entities;

/**
 * Сущность пользователя (Entity в Clean Architecture)
 */
class User
{
    private ?int $id;
    private string $login;
    private string $password;
    private string $name;
    private string $surname;
    private string $role;
    private string $theme;
    private string $language;
    private ?\DateTime $createdAt;

    public function __construct(
        string $login,
        string $password,
        string $name,
        string $surname,
        string $role = 'user',
        string $theme = 'light',
        string $language = 'ru',
        ?int $id = null,
        ?\DateTime $createdAt = null
    ) {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
        $this->role = $role;
        $this->theme = $theme;
        $this->language = $language;
        $this->createdAt = $createdAt ?? new \DateTime();
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setTheme(string $theme): void
    {
        $this->theme = $theme;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    // Бизнес-методы
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'name' => $this->name,
            'surname' => $this->surname,
            'role' => $this->role,
            'theme' => $this->theme,
            'language' => $this->language,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
        ];
    }
}
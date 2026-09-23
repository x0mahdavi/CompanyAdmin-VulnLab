<?php

declare(strict_types=1);

class Auth
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(int $userId): void
    {
        self::startSession();

        session_regenerate_id(true);

        $_SESSION['user_id'] = $userId;
    }

    public static function loginLabUser(PDO $db): void
    {
        self::startSession();

        if (self::check()) {
            return;
        }

        $stmt = $db->prepare(
            'SELECT id FROM users
             WHERE username = :username
             LIMIT 1'
        );

        $stmt->execute([
            'username' => 'trainee',
        ]);

        $userId = $stmt->fetchColumn();

        if ($userId === false) {
            throw new RuntimeException('Lab user not found.');
        }

        self::login((int) $userId);
    }

    public static function logout(): void
    {
        self::startSession();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    public static function id(): ?int
    {
        self::startSession();

        return isset($_SESSION['user_id'])
            ? (int) $_SESSION['user_id']
            : null;
    }

    public static function check(): bool
    {
        return self::id() !== null;
    }
}
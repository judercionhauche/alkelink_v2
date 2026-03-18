<?php
namespace App\Models;

class AuthModel extends BaseModel
{
    public function attempt(string $email, string $password): ?array
    {
        if (self::isDemoMode()) {
            foreach (DemoData::users() as $user) {
                if (strcasecmp($user['email'], $email) === 0 && $user['password'] === $password) {
                    unset($user['password']);
                    return $user;
                }
            }
            return null;
        }

        $stmt = self::db()->prepare('SELECT id, name, email, role, password_hash FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if (!$user) {
            return null;
        }
        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }
        unset($user['password_hash']);
        return $user;
    }
}

<?php
namespace App\Models;

use PDO;

class AdminModel extends BaseModel
{
    public function roles(): array
    {
        return DemoData::roles();
    }

    public function users(): array
    {
        if (self::isDemoMode()) {
            return $this->sessionCollection('users_admin', fn() => DemoData::userDirectory());
        }

        $stmt = self::db()->query('SELECT id, name, email, role, facility, status FROM users ORDER BY name');
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // For UI purposes, keep a placeholder field for password display.
        return array_map(fn($u) => array_merge($u, ['password' => 'hidden']), $users);
    }

    public function addUser(array $data): void
    {
        if (self::isDemoMode()) {
            $users = $this->users();
            $data['id'] = $this->nextId($users);
            $data['password'] = $data['password'] ?? 'password123';
            $users[] = $data;
            $this->setSessionCollection('users_admin', $users);
            return;
        }

        $stmt = self::db()->prepare('INSERT INTO users (name, email, role, password_hash, facility, status) VALUES (:name, :email, :role, :password_hash, :facility, :status)');
        $stmt->execute([
            'name' => trim($data['name'] ?? ''),
            'email' => trim($data['email'] ?? ''),
            'role' => trim($data['role'] ?? 'Clinician'),
            'password_hash' => password_hash(trim($data['password'] ?? 'password123'), PASSWORD_DEFAULT),
            'facility' => trim($data['facility'] ?? ''),
            'status' => trim($data['status'] ?? 'Active'),
        ]);
    }

    public function updateUser(int $id, array $data): void
    {
        if (self::isDemoMode()) {
            $users = $this->users();
            foreach ($users as &$user) {
                if ((int)$user['id'] === $id) {
                    $user = array_merge($user, $data);
                    break;
                }
            }
            unset($user);
            $this->setSessionCollection('users_admin', $users);
            return;
        }

        $fields = [];
        $params = ['id' => $id];

        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $params['name'] = trim($data['name']);
        }
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params['email'] = trim($data['email']);
        }
        if (isset($data['role'])) {
            $fields[] = 'role = :role';
            $params['role'] = trim($data['role']);
        }
        if (isset($data['facility'])) {
            $fields[] = 'facility = :facility';
            $params['facility'] = trim($data['facility']);
        }
        if (isset($data['status'])) {
            $fields[] = 'status = :status';
            $params['status'] = trim($data['status']);
        }
        if (isset($data['password'])) {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = password_hash(trim($data['password']), PASSWORD_DEFAULT);
        }

        if (empty($fields)) {
            return;
        }

        $stmt = self::db()->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id');
        $stmt->execute($params);
    }

    public function deleteUser(int $id): void
    {
        if (self::isDemoMode()) {
            $users = array_values(array_filter($this->users(), fn($u) => (int)$u['id'] !== $id));
            $this->setSessionCollection('users_admin', $users);
            return;
        }

        $stmt = self::db()->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function getUser(int $id): ?array
    {
        if (self::isDemoMode()) {
            foreach ($this->users() as $user) {
                if ((int)$user['id'] === $id) return $user;
            }
            return null;
        }

        $stmt = self::db()->prepare('SELECT id, name, email, role, facility, status FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        $user['password'] = 'hidden';
        return $user;
    }

    public function reports(): array
    {
        return DemoData::reports();
    }

    public function permissions(): array
    {
        return DemoData::permissionMatrix();
    }
}

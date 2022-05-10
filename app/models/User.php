<?php

namespace app\models;

use app\core\Model;

class User extends Model
{
    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;

    public $roles = [
        self::ROLE_USER => 'Пользователь',
        self::ROLE_ADMIN => 'Администратор',
    ];

    public $id;
    public $username;
    public $password_hash;
    public $token;
    public $first_name;
    public $last_name;
    public $email;
    public $role;

    public static function tableName(): string
    {
        return 'users';
    }

    public static function isGuest(): bool
    {
        return static::identify() === null;
    }

    public static function identify()
    {
        if(isset($_SESSION['todolist_token'])) {
            $user = User::select('id, role')
                ->findOne(['token' => $_SESSION['todolist_token']]);
            if (isset($user)) {
                return $user;
            }
        }
        return null;
    }

    public function isAdmin(): bool
    {
        return $this->role == 1;
    }
}
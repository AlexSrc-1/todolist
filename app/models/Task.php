<?php

namespace app\models;

use app\core\Model;

class Task extends Model
{
    const STATUS_CREATED = 0;
    const STATUS_IN_WORK = 1;
    const STATUS_DONE = 2;

    public static $statuses = [
        self::STATUS_CREATED => 'New task',
        self::STATUS_IN_WORK => 'Task in work',
        self::STATUS_DONE => 'Task done',
    ];

    public static $statusesClass = [
        self::STATUS_CREATED => 'badge badge-secondary',
        self::STATUS_IN_WORK => 'badge badge-warning',
        self::STATUS_DONE => 'badge badge-success',
    ];

    public static $sortFields = [
        'created_at' => 'Datetime ascending',
        '-created_at' => 'Datetime descending',
        'email' => 'Mail from A to Z',
        '-email' => 'Mail from Z to A',
        'username' => 'Username from A to Z',
        '-username' => 'Username from Z to A',
        'status' => 'Status ascending',
        '-status' => 'Status descending',
    ];

    public $id;
    public $username;
    public $text;
    public $email;
    public $status;
    public $created_at;
    public $updated_at;
    public $updated_by;

    public static function tableName(): string
    {
        return 'tasks';
    }

    public function beforeSave(): bool
    {
        if (!User::isGuest()) {
            $this->updated_at = date('Y-m-d H:i:s');
            $this->updated_by = User::identify()->id;
            return parent::beforeSave();
        }
    }
}
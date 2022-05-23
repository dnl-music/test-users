<?php


namespace app\models;

use app\storages\StorageInterface;
use yii\base\Model;

class UserData extends Model
{
    public string $fio;
    public string $email;
    public string $phone;

    public function save(StorageInterface $storage)
    {
        $storage->add($this);
    }

    public static function list(StorageInterface $storage)
    {
        return $storage->list();
    }
}
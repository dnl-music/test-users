<?php

namespace app\storages;
use app\models\UserData;

interface StorageInterface {
    public function add(UserData $user);
    public function list();
}

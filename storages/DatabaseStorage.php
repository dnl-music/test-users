<?php


namespace app\storages;
use app\models\UserData;
use Yii;



class DatabaseStorage implements  StorageInterface
{
    public function add(UserData $user)
    {
        Yii::$app->db->createCommand()->insert('user', $user->attributes)->execute();
    }

    public function list()
    {
        $rows = Yii::$app->db->createCommand("SELECT * FROM user")->queryAll();
        $users = [];
        foreach($rows as $row) {
            $users[] = new UserData($row);
        }

        return $users;
    }
}
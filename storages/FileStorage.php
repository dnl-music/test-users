<?php


namespace app\storages;


use app\models\UserData;
use Yii;
use yii\db\Exception;

class FileStorage implements StorageInterface
{
    private string $fileName;

    public function __construct()
    {
        $this->fileName = Yii::$app->params['jsonStorageFile'];
    }

    public function add(UserData $user)
    {
        if(!file_exists($this->fileName)) {
            file_put_contents($this->fileName, '[]');
        }
        $data = file_get_contents($this->fileName);
        $data = json_decode($data, true);
        $data[] = $user->attributes;
        file_put_contents($this->fileName, json_encode($data));
    }

    public function list()
    {
        try {
            $data = file_get_contents($this->fileName);
            if(!$data) throw new Exception('');
        }
        catch(\Exception $e) {
            return [];
        }
        $data = json_decode($data, true);
        $users = [];
        foreach($data as $row) {
            $user = new UserData($row);
            $users[] = $user;
        }
        return $users;
    }
}

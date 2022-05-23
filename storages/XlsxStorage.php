<?php


namespace app\storages;


use Yii;
use app\models\UserData;

class XlsxStorage implements StorageInterface
{
    public string $fileName;

    public function __construct()
    {
        $this->fileName = Yii::$app->params['xlsxStorageFile'];
    }

    public function add(UserData $user)
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->fileName);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        if(!$data) {
            $data[] = ['fio', 'email', 'phone'];
        }
        $data[] = [$user->fio, $user->email, $user->phone];

        $sheet->fromArray($data);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($this->fileName);
    }

    public function list()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->fileName);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        $users = [];
        $keys = [];
        foreach($data as $ind => $row) {
            if($ind == 0) {
                $keys = $row;
            }
            else {
                $user = new UserData();
                $attributes = [];
                foreach($keys as $i => $key) {
                    $attributes[$key] = $row[$i];
                }
                $user->attributes = $attributes;
                $users[] = $user;
            }
        }

        return $users;
    }
}
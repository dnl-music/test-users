<?php

namespace app\modules\api\controllers;

use app\storages\CacheStorage;
use app\storages\DatabaseStorage;
use app\storages\FileStorage;
use app\storages\XlsxStorage;
use app\models\UserData;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\UnauthorizedHttpException;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    public $enableCsrfValidation = false;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionCreate($storageType)
    {
        if(\Yii::$app->user->isGuest) {
            throw new UnauthorizedHttpException();
        }
        $user = new UserData($this->request->post());
        if($user->validate()) {
            $user->save($this->getStorageByType($storageType));
            return $this->asJson([]);
        }
        $this->response->statusCode = 400;
        return $this->asJson($user->errors);
    }

    public function actionList($storageType)
    {
        return $this->asJson(UserData::list($this->getStorageByType($storageType)));
    }

    private function getStorageByType($storageType)
    {
        switch ($storageType) {
            case 'xlsx':
                return new XlsxStorage();
                break;
            case 'db':
                return new DatabaseStorage();
                break;
            case 'cache':
                return new CacheStorage();
                break;
            case 'file':
                return new FileStorage();
                break;
            default: throw new BadRequestHttpException();
        }
    }
}

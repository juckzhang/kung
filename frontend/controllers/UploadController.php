<?php
namespace frontend\controllers;

use Yii;
use common\services\LeShiVideoService;
use yii\web\Response;

class UploadController extends BaseController
{
    public function actionLeshiVideo()
    {
        $return = LeShiVideoService::getService()->uploadVideo($this->paramData);

        if(is_array($return)) {
            $response = Yii::$app->response;
            $response->data = $return;
            $response->format = Response::FORMAT_JSON;
            return $response;
        }

        return $this->returnError($return);
    }
}
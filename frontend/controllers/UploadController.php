<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/17
 * Time: 21:14
 */

namespace frontend\controllers;

use Yii;
use common\services\LeShiVideoService;
use yii\web\Response;

class UploadController extends BaseController
{
    /**
     * 乐视视频上传初始化
     * @return string
     */
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
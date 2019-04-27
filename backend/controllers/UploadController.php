<?php
namespace backend\controllers;

use backend\services\IndexService;
use common\services\UploadService;
use Yii;
use common\models\LoginForm;
use yii\helpers\ArrayHelper;
/**
 * Site controller
 */
class UploadController extends BaseController
{
    /**
     * 文件上传
     * @return string
     */
    public function actionUploadFile()
    {
        $type = ArrayHelper::getValue($this->paramData,'type');

        $result = UploadService::getService()->upload($type);

        if(is_array($result))
            return $this->returnSuccess($result);

        return $this->returnError($result);
    }
}

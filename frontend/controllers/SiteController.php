<?php
namespace frontend\controllers;

use common\constants\CodeConstant;
use common\services\MediaService;
use frontend\services\UserService;
use yii\helpers\ArrayHelper;

class SiteController extends BaseController
{
    // 首页推荐
    public function actionRecommendList()
    {
        $ret = MediaService::getService()->recommendList();
        return $this->returnSuccess($ret);
    }

    // 登陆
    public function actionLogin()
    {
        $userService = UserService::getService();
        $params = $this->parseParam();

        $ret = $userService->login($params);
        if(is_numeric($ret)){
            return $this->returnError($ret);
        }

        return $this->returnSuccess(CodeConstant::SUCCESS, $ret);
    }

    public function actionTranslate()
    {
        $text = ArrayHelper::getValue($this->paramData,'text');
        $target  = ArrayHelper::getValue($this->paramData,'target');
        $ret = \Yii::$app->get('trans')->translate($text, $target);
        return $this->returnSuccess();
    }
}

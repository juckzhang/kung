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
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = MediaService::getService()->recommendList($page, $count);
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

    public function actionContact()
    {
        return $this->returnSuccess();
    }
}

<?php
namespace frontend\controllers;

use common\constants\CodeConstant;
use common\services\VideoService;
use frontend\services\UserService;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    // 首页推荐
    public function actionIndex()
    {
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = VideoService::getService()->recommendList($page, $count);
        return $this->returnSuccess($ret);
    }
    /**
     * 登陆
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
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

    /**
     * 联系我们
     * @return string
     */
    public function actionContact()
    {
        return $this->returnSuccess();
    }
}

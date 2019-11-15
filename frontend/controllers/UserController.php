<?php
namespace frontend\controllers;

use common\services\MediaService;
use frontend\services\UserService;
use yii\helpers\ArrayHelper;

class UserController extends BaseController{
    //收藏列表
    public function actionCollectionList()
    {
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $lang = ArrayHelper::getValue($this->paramData,'lang');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = MediaService::getService()->collectionList($userId, $lang,$page, $count);
        return $this->returnSuccess($ret);
    }

    //收藏列表
    public function actionLookList()
    {
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $lang = ArrayHelper::getValue($this->paramData,'lang');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = MediaService::getService()->lookList($userId, $lang,$page, $count);
        return $this->returnSuccess($ret);
    }

    //收藏列表
    public function actionDownloadList()
    {
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $lang = ArrayHelper::getValue($this->paramData,'lang');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = MediaService::getService()->downloadList($userId, $lang,$page, $count);
        return $this->returnSuccess($ret);
    }

    //用户反馈
    public function actionFeedback()
    {
        $params = $this->parseParam();

        $ret = UserService::getService()->feedback($params);
        if(is_numeric($ret)){
            return $this->returnError($ret);
        }

        return $this->returnSuccess($ret);
    }

    // 反馈列表
    public function actionFeedbackList()
    {
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = UserService::getService()->feedbackList($userId, $page, $count);
        if(is_numeric($ret)){
            return $this->returnError($ret);
        }
        return $this->returnSuccess($ret);
    }

    // 返回字库
    public function charsAction(){
        $chars = \common\helpers\CommonHelper::loadConfig('main.php',['@common','@frontend']);
        shuffle($chars);
        $chars = array_slice($chars, 0, 80);

        return $this->returnSuccess($chars);
    }

    // 记录当前用户汉字识别率
    public function knowCharsAction()
    {
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');
        $data = ArrayHelper::getValue($this->paramData,'data');

        UserService::getService()->knowChars($userId, $data);

        return $this->returnSuccess();
    }

    public function showCharsAction(){
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');
        $ret = UserService::getService()->showChars($userId);

        return $this->returnSuccess($ret);
    }
}

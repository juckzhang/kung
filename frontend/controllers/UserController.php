<?php
namespace frontend\controllers;

use common\services\VideoService;
use yii\helpers\ArrayHelper;

/**
 * User controller
 */
class UserController extends BaseController{

    //收藏列表
    public function actionCollectionList()
    {
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = VideoService::getService()->collectionList($userId, $page, $count);
        return $this->returnSuccess($ret);
    }

    //收藏列表
    public function actionLookList()
    {
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = VideoService::getService()->collectionList($userId, $page, $count);
        return $this->returnSuccess($ret);
    }

    //收藏列表
    public function actionDownloadList()
    {
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = VideoService::getService()->downloadList($userId, $page, $count);
        return $this->returnSuccess($ret);
    }

    //用户反馈
    public function actionFeedback()
    {
        $userService = UserService::getService();
        $params = $this->parseParam();

        $ret = $userService->feedback($params);
        if(is_numeric($ret)){
            return $this->returnError($ret);
        }

        return $this->returnSuccess(CodeConstant::SUCCESS, $ret);
    }

    // 反馈列表
    public function actionFeedbackList()
    {
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = UserService::getService()->feedbackList($userId, $page, $count);
        return $this->returnSuccess($ret);
    }
}

<?php
namespace frontend\controllers;

use common\services\VideoService;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class VideoController extends BaseController
{
    // 视频/音频列表
    public function actionVideoList()
    {
        $cateId = ArrayHelper::getValue($this->paramData,'cate_id');
        $sourceType = ArrayHelper::getValue($this->paramData,'source_type');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');
        $order = ArrayHelper::getValue($this->paramData,'order', 'create_time');

        $ret = VideoService::getService()->videoList($cateId,$page,$count,$sourceType, $order);

        return $this->returnSuccess($ret);
    }

    // 视频/音频详情
    public function actionVideoDetails()
    {
        $videoId = ArrayHelper::getValue($this->paramData,'source_id');
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');

        $ret = VideoService::getService()->videoDetails($videoId, $userId);

        return $this->returnSuccess($ret);
    }

    //音频视频台词列表
    public function actionVideoLines()
    {
        $videoId = ArrayHelper::getValue($this->paramData,'source_id');
        $lang  = ArrayHelper::getValue($this->paramData,'lang');

        $ret = VideoService::getService()->videoLines($videoId, $lang);

        return $this->returnSuccess($ret);
    }

    // 评论列表
    public function actionCommentList()
    {
        $videoId = ArrayHelper::getValue($this->paramData,'source_id');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $return  = VideoService::getService()->commentList($videoId,$page,$count);
        if(is_array($return)) return $this->returnSuccess($return);
        return $this->returnError($return);
    }

    //视频/音频评论
    public function actionCommentVideo()
    {
        $videoId = ArrayHelper::getValue($this->paramData,'source_id');
        $userId = ArrayHelper::getValue($this->paramData,'user_id');
        $content = ArrayHelper::getValue($this->paramData,'content');

        $ret = VideoService::getService()->videoComment($videoId, $content, $userId);
        if($ret === true) return $this->returnSuccess();
        return $this->returnError($ret);
    }

    // 收藏与取消收藏
    public function actionCollectVideo()
    {
        $sourced = ArrayHelper::getValue($this->paramData,'source_id');
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');

        $return = VideoService::getService()->collectVideo($sourced,$userId);
        if($return === true) return $this->returnSuccess();
        return $this->returnError($return);
    }

    // 视频下载
    public function actionDownloadVideo()
    {
        $sourceId = ArrayHelper::getValue($this->paramData,'source_id');
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');

        $return = VideoService::getService()->downloadVideo($sourceId,$userId);
        if($return === true) return $this->returnSuccess();
        return $this->returnError($return);
    }
}

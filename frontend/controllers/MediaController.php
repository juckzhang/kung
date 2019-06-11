<?php
namespace frontend\controllers;

use common\constants\CodeConstant;
use common\services\MediaService;
use Yii;
use yii\helpers\ArrayHelper;

class MediaController extends BaseController
{
    //分类接口
    public function actionCategoryList()
    {
        $sourceType = ArrayHelper::getValue($this->paramData,'source_type');
        $lang = ArrayHelper::getValue($this->paramData,'lang');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $ret = MediaService::getService()->categoryList($sourceType,$lang,$page,$count);

        return $this->returnSuccess($ret);
    }
    // 视频/音频列表
    public function actionMediaList()
    {
        $cateId = ArrayHelper::getValue($this->paramData,'cate_id');
        $sourceType = ArrayHelper::getValue($this->paramData,'source_type');
        $lang = ArrayHelper::getValue($this->paramData,'lang');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');
        $order = ArrayHelper::getValue($this->paramData,'order', 'create_time');

        $ret = MediaService::getService()->mediaList($cateId,$lang,$page,$count,$sourceType, $order);

        return $this->returnSuccess($ret);
    }

    // 视频/音频详情
    public function actionMediaDetails()
    {
        $videoId = ArrayHelper::getValue($this->paramData,'source_id');
        $lang = ArrayHelper::getValue($this->paramData,'lang');
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');

        $ret = MediaService::getService()->mediaDetails($videoId, $lang, $userId);

        return $this->returnSuccess($ret);
    }

    //音频视频台词列表
    public function actionMediaLines()
    {
        $mediaId = ArrayHelper::getValue($this->paramData,'source_id');
        $lang  = ArrayHelper::getValue($this->paramData,'lang');
        $page = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');
        $ret = MediaService::getService()->mediaLines($mediaId, $lang, $page, $count);

        return $this->returnSuccess($ret);
    }

    // 评论列表
    public function actionCommentList()
    {
        $videoId = ArrayHelper::getValue($this->paramData,'source_id');
        $page    = ArrayHelper::getValue($this->paramData,'page');
        $count = ArrayHelper::getValue($this->paramData,'count');

        $return  = MediaService::getService()->commentList($videoId,$page,$count);
        if(is_array($return)) return $this->returnSuccess($return);
        return $this->returnError($return);
    }

    //视频/音频评论
    public function actionCommentMedia()
    {
        $videoId = ArrayHelper::getValue($this->paramData,'source_id');
        $userId = ArrayHelper::getValue($this->paramData,'user_id');
        $content = ArrayHelper::getValue($this->paramData,'content');

        $ret = MediaService::getService()->mediaComment($videoId, $content, $userId);
        if(is_numeric($ret)){
            return $this->returnError($ret);
        }

        return $this->returnSuccess($ret);
    }

    // 用户完成查看记录
    public function actionCompleteView(){
        $videoId = ArrayHelper::getValue($this->paramData,'source_id');
        $userId = ArrayHelper::getValue($this->paramData,'user_id');
        $ret = MediaService::getService()->completeView($videoId, $userId);

        if(is_numeric($ret)){
            return $this->returnError($ret);
        }

        return $this->returnSuccess();
    }

    // 收藏与取消收藏
    public function actionCollectMedia()
    {
        $sourced = ArrayHelper::getValue($this->paramData,'source_id');
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');

        $return = MediaService::getService()->collectMedia($sourced,$userId);
        if($return === true) return $this->returnSuccess();
        return $this->returnError($return);
    }

    // 视频下载
    public function actionDownloadMedia()
    {
        $sourceId = ArrayHelper::getValue($this->paramData,'source_id');
        $userId  = ArrayHelper::getValue($this->paramData,'user_id');

        $return = MediaService::getService()->downloadMedia($sourceId,$userId);
        if($return === true) return $this->returnSuccess();
        return $this->returnError($return);
    }
}

<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\AlbumTagsModel;
use common\models\mysql\VideoAlbumModel;
use common\models\mysql\VideoCategoryModel;
use common\models\mysql\VideoModel;
use JPush\Exceptions\JPushException;
use Yii;
use backend\services\VideoService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

require '../../common/components/jpush/autoload.php';
use JPush\Client as JPush;

/**
 * Site controller
 */
class VideoController extends BaseController
{
    //卡片信息管理开始{{{
    /**
     * 卡片列表
     * @return string
     */
    public function actionVideoList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $_category = ArrayHelper::getValue($this->paramData,'category',false);
        $_recommend = ArrayHelper::getValue($this->paramData,'recommend',false);
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder(VideoModel::tableName().'.');
        $data = VideoService::getService()->videoList($_keyWord,$_other,$_order,$_page,$_prePage,$_category,$_recommend);
        return $this->render('video-list',$data);
    }

    /**
     * 编辑卡片
     * @return string
     */
    public function actionEditVideo()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $albumId = ArrayHelper::getValue($this->paramData,'albumId');
            $result = VideoService::getService()->editVideo($id,$albumId);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'video-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['video/video-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = VideoModel::find()->where(['id' => $id])->asArray()->one();
            $album = [];
            if(! empty($model)) $album = VideoAlbumModel::find()
                ->where(['id' => $model['album_id'],'status' => VideoAlbumModel::STATUS_ACTIVE])->asArray()->one();
            return $this->render('edit-video',['model' => $model,'album' => $album]);
        }
    }

    /**
     * 删除卡片
     * @return mixed
     */
    public function actionDeleteVideo()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = VideoService::getService()->deleteVideo($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'video-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/video-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    /**
     * 推送视频
     */
    public function actionPushVideo()
    {
        if (!Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);
        $id = ArrayHelper::getValue($this->paramData,'id');
        $title        = urldecode(ArrayHelper::getValue($this->paramData, 'name'));
        $url          = "https://mobile.moviest.com/channel/video-play.html?id=$id";
        $auth         = '454821c0b73180fdb900ffb7:dc11ff114c7745ddba0c04bb';

        $headers      = array('Content-Type: application/json', 'Connection: Keep-Alive');
        $extras       = array('title' => $title, 'url' => $url);
        $android      = array('extras' => $extras);
        $ios          = array('badge' => '1', 'extras' => $extras);
        $options      = array('apns_production' => true);
        $notification = array('alert' => "最影推荐：$title", 'android' => $android, 'ios' => $ios);
        $fields       = array('platform' => 'all', 'audience' => 'all', 'notification' => $notification, 'options' => $options);

        $api_url = 'https://api.jpush.cn/v3/push';
        $ch      = curl_init() ;
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, '');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3');
        $result = curl_exec($ch);
        $code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code == 200) {
            return $this->returnAjaxSuccess([
                'message' => '推送成功',
                'navTabId' => 'video-push',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/video-list'])
            ]);
        } else {
            $this->returnAjaxError($result);
        }
    }

    /*
     * 审核通过
     * @return mixed
     */
    public function actionAuditingVideo(){
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = VideoService::getService()->auditingVideo($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '审核通过',
                'navTabId' => 'video-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/video-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    /*
     * 发布专辑
     * @return mixed
     */
    public function actionPublishAlbum(){
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');
        $return = VideoService::getService()->publishAlbum($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '发布成功',
                'navTabId' => 'video-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/video-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    /**
     * 视频推荐
     * @return array
     *
     */
    public function actionRecommendVideo()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');
        $column = ArrayHelper::getValue($this->paramData,'column');
        $value = ArrayHelper::getValue($this->paramData,'value');

        $return = VideoService::getService()->recommendVideo($ids,$column,$value);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '操作',
                'navTabId' => 'video-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/video-list'])
            ]);
        return $this->returnAjaxError($return);
    }
    //卡片信息管理结束}}}

    //卡片系列信息管理开始{{{
    /**
     * 文章列表
     * @return string
     */
    public function actionAlbumList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();
        $data = VideoService::getService()->albumList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('album-list',$data);
    }

    /**
     * 编辑产品信息
     * @return array|string
     */
    public function actionEditAlbum()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = VideoService::getService()->editAlbum($id);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'album-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['video/album-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            //获取广告id
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = VideoAlbumModel::find()->where(['id' => $id])->asArray()->one();
            return $this->render('edit-album',['model' => $model]);
        }
    }

    /**
     * 删除卡片
     * @return mixed
     */
    public function actionDeleteAlbum()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = VideoService::getService()->deleteAlbum($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'album-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/album-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    //视频专辑信息管理结束}}}

    //视频分类信息管理结束{{{
    /**
     * 球员信息列表
     * @return string
     */
    public function actionCategoryList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();
        $data = VideoService::getService()->categoryList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('category-list',$data);
    }

    public function actionAppDownload()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_order = $this->_sortOrder(VideoAlbumModel::tableName().'.');
        $data = VideoService::getService()->appDownloadVideoList($_order,$_page,$_prePage);
        return $this->render('app-download', $data);
    }

    public function actionDeleteAppDownload()
    {
        $id = ArrayHelper::getValue($this->paramData,'id');
        $return = VideoService::getService()->deleteAppDownload($id);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'app-download',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/app-download'])
            ]);
        return $this->returnAjaxError($return);
    }

    public function actionAddAppDownload()
    {
        $id = ArrayHelper::getValue($this->paramData,'id');
        $return = VideoService::getService()->addAppDownload($id);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '添加成功',
                'navTabId' => 'app-download',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/app-download'])
            ]);
        return $this->returnAjaxError($return);
    }

    /**
     * 编辑视频分类信息
     * @return array|string
     */
    public function actionEditCategory()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = VideoService::getService()->editCategory($id);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'category-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['video/category-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            //获取广告id
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = VideoCategoryModel::find()->where(['id' => $id])->asArray()->one();
            $categories = VideoCategoryModel::find()->where(['parent_id' => 0,'status' => VideoCategoryModel::STATUS_ACTIVE])
                ->asArray()->all();
            return $this->render('edit-category',['model' => $model,'categories' => $categories]);
        }
    }

    /**
     * 删除卡片
     * @return mixed
     */
    public function actionDeleteCategory()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = VideoService::getService()->deleteCategory($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'category-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/category-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    /*
     * 添加视频到专辑的列表
     */
    public function actionAddvideoList(){
        $albumid = ArrayHelper::getValue($this->paramData,'album');
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $_category = ArrayHelper::getValue($this->paramData,'category','');
        $_recommend = ArrayHelper::getValue($this->paramData,'recommend','');
        $_order = $this->_sortOrder(VideoModel::tableName().'.');
        $data = VideoService::getService()->videoToalbum($albumid,$_keyWord,$_order,$_page,$_prePage,$_category,$_recommend);
        
        return $this->render('addvideo-list',$data);
    }

    /*
     * 可移除专辑的视频列表
     */
    public function actionDelvideoList(){
        $albumid = ArrayHelper::getValue($this->paramData,'album');
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $_category = ArrayHelper::getValue($this->paramData,'category','');
        $_recommend = ArrayHelper::getValue($this->paramData,'recommend','');
        $_order = $this->_sortOrder(VideoModel::tableName().'.');
        $data = VideoService::getService()->delvideoToalbum($albumid,$_keyWord,$_order,$_page,$_prePage,$_category,$_recommend);

        return $this->render('delvideo-list',$data);
    }

    /*
     *将视频添加到某个专辑下面
     */
    public function actionToalbum(){
        $albumid = ArrayHelper::getValue($this->paramData,'albumid');
        $videoid = ArrayHelper::getValue($this->paramData,'videoid');

        $result = VideoService::getService()->toalbum($albumid,$videoid);

        if($result){
            return $this->returnAjaxSuccess([
                'message' => '添加成功',
                'navTabId' => 'album-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/album-list'])
            ]);
        }
        return $this->returnAjaxError($result);

    }

    /*
     * 将视频从某个专辑下移除
     */
    public function actionDeltoalbum(){
        $albumid = ArrayHelper::getValue($this->paramData,'albumid');
        $videoid = ArrayHelper::getValue($this->paramData,'videoid');

        $result = VideoService::getService()->deltoalbum($albumid,$videoid);

        if($result){
            return $this->returnAjaxSuccess([
                'message' => '移除成功',
                'navTabId' => 'album-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/album-list'])
            ]);
        }
        return $this->returnAjaxError($result);
    }

    /*
     * 修改视频排序
     */
    public function actionSort(){
        $videoid = ArrayHelper::getValue($this->paramData,'videoid');
        $sort = ArrayHelper::getValue($this->paramData,'sort');

        $result = VideoService::getService()->editSort($videoid,$sort);

        if($result){
            return $this->returnAjaxSuccess([
                'message' => '修改成功',
                'navTabId' => 'album-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['video/album-list'])
            ]);
        }
        return $this->returnAjaxError($result);
    }


    //视频分类信息管理结束}}}
}

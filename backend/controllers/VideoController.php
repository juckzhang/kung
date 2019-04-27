<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\VideoCategoryModel;
use common\models\mysql\VideoCommentModel;
use common\models\mysql\VideoModel;
use Yii;
use backend\services\VideoService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class VideoController extends BaseController
{
    public function actionVideoList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $_category = ArrayHelper::getValue($this->paramData,'category');
        $_recommend = ArrayHelper::getValue($this->paramData,'recommend');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder(VideoModel::tableName().'.');
        $data = VideoService::getService()->videoList($_keyWord,$_other,$_order,$_page,$_prePage,$_category,$_recommend);
        return $this->render('video-list',$data);
    }

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

    public function actionCommentList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder(VideoCommentModel::tableName().'.');
        $data = VideoService::getService()->commentList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('comment-list',$data);
    }

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
}

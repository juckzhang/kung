<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\MediaCategoryModel;
use common\models\mysql\MediaCommentModel;
use common\models\mysql\MediaModel;
use Yii;
use backend\services\MediaService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MediaController extends BaseController
{
    public function actionMediaList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $_category = ArrayHelper::getValue($this->paramData,'category');
        $_recommend = ArrayHelper::getValue($this->paramData,'recommend');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder(MediaModel::tableName().'.');
        $data = MediaService::getService()->MediaList($_keyWord,$_other,$_order,$_page,$_prePage,$_category,$_recommend);
        return $this->render('media-list',$data);
    }

    public function actionEditMedia()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $albumId = ArrayHelper::getValue($this->paramData,'albumId');
            $result = MediaService::getService()->editMedia($id,$albumId);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'media-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['media/media-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = MediaModel::find()->where(['id' => $id])->asArray()->one();
            $album = [];
            if(! empty($model)) $album = VideoAlbumModel::find()
                ->where(['id' => $model['album_id'],'status' => VideoAlbumModel::STATUS_ACTIVE])->asArray()->one();
            return $this->render('edit-media',['model' => $model,'album' => $album]);
        }
    }

    public function actionDeleteMedia()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = MediaService::getService()->deleteMedia($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'media-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['media/media-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    public function actionRecommendMedia()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');
        $column = ArrayHelper::getValue($this->paramData,'column');
        $value = ArrayHelper::getValue($this->paramData,'value');

        $return = MediaService::getService()->recommendMedia($ids,$column,$value);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '操作',
                'navTabId' => 'media-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['media/media-list'])
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
        $data = MediaService::getService()->categoryList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('category-list',$data);
    }

    public function actionEditCategory()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = MediaService::getService()->editCategory($id);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'category-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['media/category-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = MediaCategoryModel::find()->where(['id' => $id])->asArray()->one();
            return $this->render('edit-category',['model' => $model]);
        }
    }

    public function actionDeleteCategory()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = MediaService::getService()->deleteCategory($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'category-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['media/category-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    public function actionCommentList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder(MediaCommentModel::tableName().'.');
        $data = MediaService::getService()->commentList($_keyWord,$_page,$_prePage,$_other,$_order);
        return $this->render('comment-list',$data);
    }

    public function actionSort(){
        $mediaid = ArrayHelper::getValue($this->paramData,'mediaid');
        $sort = ArrayHelper::getValue($this->paramData,'sort');

        $result = MediaService::getService()->editSort($mediaid,$sort);

        if($result){
            return $this->returnAjaxSuccess([
                'message' => '修改成功',
                'navTabId' => 'album-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['media/media-list'])
            ]);
        }
        return $this->returnAjaxError($result);
    }
}

<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\ActiveRecord;
use common\models\mysql\CategoryModel;
use common\models\mysql\ArticleModel;
use Yii;
use backend\services\ArticleService;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Site controller
 */
class ArticleController extends BaseController
{
    //文章分类信息管理开始{{{
    /**
     * 文章分类
     * @return string
     */
    public function actionCategoryList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyWord');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();

        $data = ArticleService::getService()->categoryList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('category-list',$data);
    }

    /**
     * 编辑文章分类
     * @return string
     */
    public function actionEditCategory()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = ArticleService::getService()->editCategory($id);

            if($result instanceof ActiveRecord)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'category-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['article/category-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            //获取广告id
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = CategoryModel::find()->where(['id' => $id])->asArray()->one();
            return $this->render('edit-category',['model' => $model]);
        }
    }

    /**
     * 删除文章分类
     * @return mixed
     */
    public function actionDeleteCategory()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = ArticleService::getService()->deleteCategory($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'category-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['article/category-list'])
            ]);
        return $this->returnAjaxError($return);
    }
    //文章分类信息管理结束}}}

    //文章信息管理开始{{{
    /**
     * 文章列表
     * @return string
     */
    public function actionArticleList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyWord');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();

        $data = ArticleService::getService()->articleList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('article-list',$data);
    }

    /**
     * 编辑文章
     * @return string
     */
    public function actionEditArticle()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = ArticleService::getService()->editArticle($id);

            if($result instanceof ActiveRecord)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'article-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['article/article-list'])
                ]);
            return $this->returnAjaxError($result);
        }else {
            $id = ArrayHelper::getValue($this->paramData, 'id');
            $model = ArticleModel::find()->where(['id' => $id])->asArray()->one();
            //获取所有的文章分类信息
            $cateModels = ArticleService::getService()->categories();
            return $this->render('edit-article', ['model' => $model, 'cateModels' => $cateModels]);
        }
    }

    /**
     * 删除文章
     * @return mixed
     */
    public function actionDeleteArticle()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = ArticleService::getService()->deleteArticle($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'article-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['article/article-list'])
            ]);
        return $this->returnAjaxError($return);
    }


    /**
     * 意见反馈列表
     * @return string
     */
    public function actionFeedbackList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyWord');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();

        $data = ArticleService::getService()->FeedbackList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('feedback-list',$data);
    }

    /*
     * 删除意见反馈
     */
    public function actionDeleteFeedback(){
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = ArticleService::getService()->deleteFeedback($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'feedback-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['article/feedback-list'])
            ]);
        return $this->returnAjaxError($return);
    }
    //文章信息管理结束}}}
}

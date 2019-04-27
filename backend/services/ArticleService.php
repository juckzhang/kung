<?php
namespace backend\services;

use common\models\mysql\ArticleModel;
use Yii;
use backend\services\base\BackendService;
use common\models\mysql\CategoryModel;
use common\models\mysql\FeedbackModel;
use yii\helpers\ArrayHelper;

class ArticleService extends BackendService
{
    //文章分类信息管理开始{{{
    /**
     * 获取文章分类列表
     * @param $keyWord
     * @param $other
     * @param array $order
     * @param $page
     * @param $prePage
     * @return array
     */
    public function categoryList($keyWord,$other,array $order = [],$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $parentId = ArrayHelper::getValue($other,'parentId');

        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = CategoryModel::find()->where(['status' => CategoryModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','name',$keyWord])
            ->andFilterWhere(['parent_id' => $parentId]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    /**
     * 获取所有的分类
     * @return array|\yii\db\ActiveRecord[]
     */
    public function categories()
    {
        $models = CategoryModel::find()
            ->where(['status' => CategoryModel::STATUS_ACTIVE,'parent_id' => 0])
            ->select(['id','name'])
            ->asArray()->all();

        return $models;
    }

    /**
     * 编辑文章分类
     * @param $id
     * @return null
     */
    public function editCategory($id)
    {
         return $this->editInfo($id,CategoryModel::className());
    }



    /**
     * 删除文章分类
     * @param $ids
     * @return bool
     */
    public function deleteCategory($ids)
    {
        $num = $this->deleteInfo($ids,CategoryModel::className());
        if($num > 0) return true;
        return false;
    }
    //文章分类信息管理结束}}}

    //文章信息管理开始{{{
    /**
     * 获取文章列表
     * @param $keyWord
     * @param $other
     * @param array $order
     * @param $page
     * @param $prePage
     * @return array
     */
    public function articleList($keyWord,$other,array $order = [],$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $cateId = ArrayHelper::getValue($other,'cateId');

        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = ArticleModel::find()->where(['status' => ArticleModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','title',$keyWord])
            ->andFilterWhere(['catet_id' => $cateId]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    /**
     * 获取意见反馈列表
     */
    public function FeedbackList($keyWord,$other,array $order = [],$page,$prePage){
        list($offset,$limit) = $this->parsePageParam($page,$prePage);

        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = FeedbackModel::find()->where(['status' => FeedbackModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','center',$keyWord]);


        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    /**
     * 编辑文章
     * @param $id
     * @return bool
     */
    public function editArticle($id)
    {
        return $this->editInfo($id,ArticleModel::className());
    }

    /**
     * 删除文章
     * @param $ids
     * @return bool
     */
    public function deleteArticle($ids)
    {
        $num = $this->deleteInfo($ids,ArticleModel::className());
        if( $num > 0 ) return true;

        return false;
    }

    /*
     * 删除意见反馈
     */
    public function deleteFeedback($ids){
        $num = $this->deleteInfo($ids,FeedbackModel::className());
        if( $num > 0 ) return true;

        return false;
    }
    //文章信息管理结束}}}
}


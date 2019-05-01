<?php
namespace backend\services;

use common\events\MediaEvent;
use common\helpers\TreeHelper;
use common\models\mysql\UserModel;
use common\models\mysql\MediaCategoryModel;
use common\models\mysql\MediaCommentModel;
use common\models\mysql\MediaModel;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;

class MediaService extends BackendService
{
    const ON_AFTER_EDIT_MEDIA  = 'on_after_edit_media';
    const ON_AFTER_DELETED_MEDIA = 'on_after_deleted_media';

    public function behaviors()
    {
        return [
            'mediaBehavior' => [
                'class' => 'common\behaviors\MediaBehavior',
            ],
        ];
    }

    public function mediaList($keyWord,$other,array $order = [],$page,$prePage,$_category,$_recommend)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = MediaModel::find()
            ->where(['!=','status' , MediaModel::STATUS_DELETED])
            ->andFilterWhere(['like','title',$keyWord])
            ->andFilterWhere(['cate_id' => $_category])
            ->andFilterWhere(['source_type' => ArrayHelper::getValue($other, 'source_type')])
            ->andFilterWhere(['is_recommend' => $_recommend]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        $data['categorys']  = $_category;
        $data['recommends']= $_recommend;

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->asArray()->all();

        return $data;
    }

    //编辑排序
    public function editSort($mediaId,$sort){
        $result =  MediaModel::updateAll(['sort_order' => $sort],['id' => $mediaId]);
        return $result;
    }
    

    //编辑视频 音频
    public function editMedia($id, $albumId)
    {
        $bodyParams = \Yii::$app->request->post();
        $bodyParams['VideoAlbumModel']['name'] = $bodyParams['VideoAlbumModel']['name'] ?: $bodyParams['VideoModel']['title'];
        \Yii::$app->request->setBodyParams($bodyParams);
        $newAlbum   = ArrayHelper::getValue($bodyParams,'VideoModel.album_id');
        //编辑专辑信息
        $albumModel = $this->editAlbum($newAlbum);

        if($albumModel == false) return CodeConstant::EDIT_VIDEO_FAILED;
        //设置albumId
        $bodyParams['VideoModel']['album_id'] = $albumModel->id;
        \Yii::$app->request->setBodyParams($bodyParams);

        $return = $this->editInfo($id,MediaModel::className());

        if($return == false) return $return;

        //判断是否将一个视频转移到一个专辑列表(也可能是将一个视频从一个专辑转移都另一个专辑下)
        if($albumId != null and $newAlbum != $albumId)
            $this->onAfterEditVideo($albumId,$newAlbum);

        return $return;
    }

    // 删除
    public function deleteMedia($id)
    {
        $result = $this->deleteInfo($id,MediaModel::className());
        //if($result == true) $this->onAfterDeleteVideo($id);
        return $result;
    }

    public function recommendMedia($ids,$column,$value)
    {
        $num = MediaModel::updateAll([$column => $value],['id' => $ids]);
        if($num > 0) return true;
        return false;
    }

    public function categoryList($keyWord,array $other = [],array $order,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);

        $data = ['dataList' => [],'dataCount' => 0,'pageCount' => 0];

        $models = MediaCategoryModel::find()->where(['status' => MediaCategoryModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','name',$keyWord])
            ->andFilterWhere(['source_type' => ArrayHelper::getValue($other,'source_type')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']) {
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();
        }

        return $data;
    }

    public function editCategory($id)
    {
        return $this->editInfo($id,MediaCategoryModel::className());
    }

    public function deleteCategory($id)
    {
        return $this->deleteInfo($id,MediaCategoryModel::className());
    }

    public function deleteComment($id)
    {
        return $this->deleteInfo($id,MediaCommentModel::className());
    }

    //获取分类
    public function categories($sourceType = 1)
    {
        $model = MediaCategoryModel::find()->select(['id','name','parent_id'])
            ->where(['status' => MediaCategoryModel::STATUS_ACTIVE])
            ->andWhere(['source_type' => $sourceType])
            ->asArray()
            ->all();

        $treeHelper = new TreeHelper();
        $categories = $treeHelper->getTree($model);

        return $categories;
    }

    public function commentList($_keyWord,$_page,$_prePage,$_other = [],$_order = [])
    {
        list($offset,$limit) = $this->parsePageParam($_page,$_prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = MediaCommentModel::find()
            ->where(['!=','status' , MediaCommentModel::STATUS_DELETED])
            ->andFilterWhere(['content','title',$_keyWord])
            ->andFilterWhere(['source_id' => ArrayHelper::getValue($_other, 'source_id')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $_page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($_order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    public function getUser()
    {
        $model = UserModel::find()->select(['id','nick_name'])
            ->asArray()
            ->all();

        return $model;
    }

    private function onAfterEditVideo($oldAlbumId,$newAlbumId)
    {
        $event = new VideoChangeEvent(['oldAlbumId' => $oldAlbumId,'newAlbumId' => $newAlbumId]);
        $this->trigger(static::ON_AFTER_EDIT_VIDEO,$event);
    }

    private function onAfterDeleteVideo($id)
    {
        $id = is_array($id) ? $id : (array)$id;
        $event = new MediaEvent(['videoId' => $id]);
        $this->trigger(static::ON_AFTER_DELETED_VIDEO,$event);
    }

    private function onAfterDeleteAlbum($id)
    {
        $id = is_array($id) ? $id : (array)$id;
        $event = new MediaEvent(['AlbumId' => $id]);
        $this->trigger(static::ON_AFTER_DELETED_ALBUM,$event);
    }
}


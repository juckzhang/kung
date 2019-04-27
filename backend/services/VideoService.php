<?php
namespace backend\services;

use backend\events\AlbumEvent;
use common\components\leshi\Video;
use common\constants\CodeConstant;
use common\events\VideoChangeEvent;
use common\events\VideoEvent;
use common\helpers\TreeHelper;
use common\models\mongodb\CardAlbumModel;
use common\models\mongodb\CardFirmsModel;
use common\models\mongodb\CardTypeNameModel;
use common\models\mongodb\ClubModel;
use common\models\mongodb\IssuingDateModel;
use common\models\mongodb\PlayerModel;
use common\models\mongodb\ProductRangeModel;
use common\models\mongodb\SellCardModel;
use common\models\mongodb\TeamModel;
use common\models\mysql\AdretationModel;
use common\models\mysql\UserModel;
use common\models\mysql\VideoAppDownloadModel;
use common\models\mysql\VideoAlbumModel;
use common\models\mysql\VideoCategoryModel;
use common\models\mysql\VideoModel;
use common\models\User;
use Yii;
use common\models\mongodb\CardModel;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;
use common\services\LeShiVideoService;

class VideoService extends BackendService
{
    const ON_AFTER_EDIT_VIDEO  = 'on_after_edit_video';
    const ON_AFTER_DELETED_VIDEO = 'on_after_deleted_video';
    const ON_AFTER_DELETED_ALBUM = 'on_after_deleted_album';

    public function behaviors()
    {
        return [
            'albumBehavior' => [
                'class' => 'common\behaviors\VideoBehavior',
            ],
        ];
    }
    //卡片信息管理开始{{{
    /**
     * 获取文章分类列表
     * @param $keyWord
     * @param $other
     * @param array $order
     * @param $page
     * @param $prePage
     * @param $_category
     * @return array
     */
    public function videoList($keyWord,$other,array $order = [],$page,$prePage,$_category,$_recommend)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = VideoModel::find()
            ->select(VideoModel::tableName().'.*')
            ->where(['!=','status' , VideoModel::STATUS_DELETED])
            ->andFilterWhere(['like','title',$keyWord])
            ->andFilterWhere(['cate_id' => $_category])
            ->andFilterWhere(['source_type' => $other['source_type']])
            ->andFilterWhere(['is_recommd' => $_recommend]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        $data['categorys']  = $_category;
        $data['recommends']= $_recommend;

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->asArray()->all();

        return $data;
    }

    /*
     * 修改视频排序
     */
    public function editSort($videoid,$sort){
        $result =  VideoModel::updateAll(['sort_order' => $sort],['id' => $videoid]);
        return $result;
    }
    

    //编辑视频 音频
    public function editVideo($id,$albumId)
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

        $return = $this->editInfo($id,VideoModel::className());

        if($return == false) return $return;

        //判断是否将一个视频转移到一个专辑列表(也可能是将一个视频从一个专辑转移都另一个专辑下)
        if($albumId != null and $newAlbum != $albumId)
            $this->onAfterEditVideo($albumId,$newAlbum);

        return $return;
    }

    // 删除
    public function deleteVideo($id)
    {
        $result = $this->deleteInfo($id,VideoModel::className());
        //if($result == true) $this->onAfterDeleteVideo($id);
        return $result;
    }

    /**
     * 视频推荐操作
     * @param $ids
     * @param $column
     * @param $value
     * @return bool
     */
    public function recommendVideo($ids,$column,$value)
    {
        $num = VideoModel::updateAll([$column => $value],['id' => $ids]);
        if($num > 0) return true;
        return false;
    }
    //视频信息管理结束

    //视频分类信息管理开始{{{
    public function categoryList($keyWord,array $other = [],array $order,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);

        $data = ['dataList' => [],'dataCount' => 0,'pageCount' => 0];

        $models = VideoCategoryModel::find()->where(['status' => VideoCategoryModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','name',$keyWord]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']) {
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();
        }

        return $data;
    }

    public function appDownloadVideoList(array $order, $page, $prePage) {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);

        $models = VideoAlbumModel::find()->where(['status' => VideoAlbumModel::STATUS_ACTIVE])->andFilterWhere(['can_download' => 1]);
        $data = ['dataList' => [],'dataCount' => 0,'pageCount' => 0];
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']) {
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();
        }

        return $data;
    }

    public function deleteAppDownload($id)
    {
        $video = VideoModel::find()->where(['album_id' => $id])->one();
        $leShi = LeShiVideoService::getService();
        $r = $leShi->updateVideoDownloadStatus(['video_id' => $video->video_id, 'status' => 0]);

        $album = VideoAlbumModel::find()->where(['id' => $id])->one();
        $album->can_download = 0;
        $album->save();
        return true;
    }

    public function addAppDownload($id)
    {
        $video = VideoModel::find()->where(['album_id' => $id])->one();
        $leShi = LeShiVideoService::getService();
        $r = $leShi->updateVideoDownloadStatus(['video_id' => $video->video_id, 'status' => 1]);

        $album = VideoAlbumModel::find()->where(['id' => $id])->one();
        $album->can_download = 1;
        $album->save();
        return true;
    }

    /**
     * 编辑分类
     * @param $id
     * @return bool
     */
    public function editCategory($id)
    {
        return $this->editInfo($id,VideoCategoryModel::className());
    }

    /**
     * 删除分类
     * @param $id
     * @return bool
     */
    public function deleteCategory($id)
    {
        return $this->deleteInfo($id,VideoCategoryModel::className());
    }

    //获取分类
    public function categories($sourceType = 1)
    {
        $model = VideoCategoryModel::find()->select(['id','name','parent_id'])
            ->where(['status' => VideoCategoryModel::STATUS_ACTIVE])
            ->andWhere(['source_type' => $sourceType])
            ->asArray()
            ->all();

        $treeHelper = new TreeHelper();
        $categories = $treeHelper->getTree($model);

        return $categories;
    }

    /**
     * 获取所有的分类
     * @return array
     */
    public function getUser()
    {
        $model = UserModel::find()->select(['id','nick_name'])
            ->asArray()
            ->all();


        return $model;
    }

    //卡片相册管理开始{{{
    public function albumList($keyWord,$other,array $order = [],$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $cateId = ArrayHelper::getValue($other,'category');

        $data = ['dataList' => [],'dataCount' => 0,'pageCount' => 0];

        $models = VideoAlbumModel::find()->where(['status' => VideoAlbumModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','name',$keyWord])
            ->andFilterWhere(['cate_id' => $cateId,'is_album' => VideoAlbumModel::IS_ALBUM]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();
        }

        return $data;
    }

    public function editAlbum($id)
    {
        return $this->editInfo($id,VideoAlbumModel::className());
    }

    /**
     * 删除专辑
     * @param $ids
     * @return bool
     */
    public function deleteAlbum($ids)
    {
        $result = $this->deleteInfo($ids,VideoAlbumModel::className());
        if($result == true) $this->onAfterDeleteAlbum($ids);
        return $result;
    }

    /**
     * 将视频转移到一个专辑列表
     * @param $oldAlbumId
     * @param $newAlbumId
     */
    private function onAfterEditVideo($oldAlbumId,$newAlbumId)
    {
        $event = new VideoChangeEvent(['oldAlbumId' => $oldAlbumId,'newAlbumId' => $newAlbumId]);
        $this->trigger(static::ON_AFTER_EDIT_VIDEO,$event);
    }

    private function onAfterDeleteVideo($id)
    {
        $id = is_array($id) ? $id : (array)$id;
        $event = new VideoEvent(['videoId' => $id]);
        $this->trigger(static::ON_AFTER_DELETED_VIDEO,$event);
    }

    private function onAfterDeleteAlbum($id)
    {
        $id = is_array($id) ? $id : (array)$id;
        $event = new VideoEvent(['AlbumId' => $id]);
        $this->trigger(static::ON_AFTER_DELETED_ALBUM,$event);
    }
}


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
use common\models\mysql\VideoCommentModel;
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

    //编辑排序
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

    public function recommendVideo($ids,$column,$value)
    {
        $num = VideoModel::updateAll([$column => $value],['id' => $ids]);
        if($num > 0) return true;
        return false;
    }

    public function categoryList($keyWord,array $other = [],array $order,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);

        $data = ['dataList' => [],'dataCount' => 0,'pageCount' => 0];

        $models = VideoCategoryModel::find()->where(['status' => VideoCategoryModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','name',$keyWord])
            ->andFilterWhere(['source_type' => $other['source_type']]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']) {
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();
        }

        return $data;
    }

    public function editCategory($id)
    {
        return $this->editInfo($id,VideoCategoryModel::className());
    }

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

    public function commentList($_keyWord,$_other = [],$_order = [],$_page,$_prePage)
    {
        $_other['source_id'] = null;
        list($offset,$limit) = $this->parsePageParam($_page,$_prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = VideoCommentModel::find()
            ->where(['!=','status' , VideoCommentModel::STATUS_DELETED])
            ->andFilterWhere(['content','title',$_keyWord])
            ->andFilterWhere(['source_id' => $_other['source_id']]);

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


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
        //过滤出所有的条件
        $album = ArrayHelper::getValue($other,'album');

        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = VideoModel::find()
            ->select(VideoModel::tableName().'.*')
            ->where(['!=','zuiying_video.status' , VideoModel::STATUS_DELETED])
            ->andFilterWhere(['like','title',$keyWord])
            ->andFilterWhere(['album_id' => $album])
            ->joinWith(['album'=>function($query) use($_category,$_recommend){
                $query->andWhere(['is_album'=>0]);
                if(isset($_category) && $_category)
                    $query->andWhere(['cate_id'=>$_category]);

                    //$query->andWhere("cate_id=$_category");
                if(isset($_recommend) && $_recommend)
                    $query->andWhere(['is_recommend'=>$_recommend]);

                    //$query->andWhere("is_recommend=$_recommend");
                $query->with('category');
            }]);
//            ->joinWith('album')->where("cate_id=$_category");

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        $data['categorys']  = $_category;
        $data['recommends']= $_recommend;
        

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->asArray()->all();
//        $commandQuery = clone $models;
//        echo $commandQuery->createCommand()->getRawSql();
//        var_dump($data);exit;
//        var_dump($data['dataList'][0]['album']);exit;
        foreach ($data['dataList'] as $key=>$value){
            $user = UserModel::find()->where(['id'=>$value['album']['user_id']])->one();
//            echo "<pre>";
//            var_dump($user);exit();
            if($user){
                $data['dataList'][$key]['username'] = $user->nick_name;
            }else{
                $data['dataList'][$key]['username'] = 'root';
            }
        }
        
        return $data;
    }

    /*
     * 获取可以给专辑添加的视频
     * @param $userid
     * @param $albumid
     * $return array
     */
    public function videoToalbum($albumid,$_keyWord,$_order,$_page,$_prePage,$_category,$_recommend){
        list($offset,$limit) = $this->parsePageParam($_page,$_prePage);
        $album_videoid = AdretationModel::find()
            ->select('video_id')
            ->where(['album_id'=>$albumid])
            ->asArray()->all();

        $model = VideoModel::find()
            ->select(VideoModel::tableName().".*")
            ->where([VideoModel::tableName().'.status'=>VideoModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','title',$_keyWord])
            ->joinWith(['album'=>function($query) use($_category,$_recommend){
                $query->andWhere(['is_album'=>0]);
                if(isset($_category) && $_category)
                    $query->andWhere(['cate_id'=>$_category]);

                //$query->andWhere("cate_id=$_category");
                if(isset($_recommend) && $_recommend)
                    $query->andWhere(['is_recommend'=>$_recommend]);

                //$query->andWhere("is_recommend=$_recommend");
                $query->with('category');
            }]);
        $data['dataCount'] = $model->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        $data['categorys']  = $_category;
        $data['recommends']= $_recommend;
        if($album_videoid && count($album_videoid)>0){
            foreach($album_videoid as $value) $data_[] = $value['video_id'];
            $model = $model->andWhere(['not in',VideoModel::tableName().'.id',$data_]);
        }

        $data['dataList'] = $model->orderBy($_order)->offset($offset)->limit($limit)->asArray()->all();

        return $data;

    }

    public function delvideoToalbum($albumid,$_keyWord,$_order,$_page,$_prePage,$_category,$_recommend){
        list($offset,$limit) = $this->parsePageParam($_page,$_prePage);
        $album_videoid = AdretationModel::find()
            ->select('video_id')
            ->where(['album_id'=>$albumid])
            ->asArray()->all();

        $model = VideoModel::find()
            ->select(VideoModel::tableName().".*")
            ->where([VideoModel::tableName().'.status'=>VideoModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','title',$_keyWord])
            ->joinWith(['album'=>function($query) use($_category,$_recommend){
                $query->andWhere(['is_album'=>0]);
                if(isset($_category) && $_category)
                    $query->andWhere(['cate_id'=>$_category]);

                //$query->andWhere("cate_id=$_category");
                if(isset($_recommend) && $_recommend)
                    $query->andWhere(['is_recommend'=>$_recommend]);

                //$query->andWhere("is_recommend=$_recommend");
                $query->with('category');
            }]);
        $data['dataCount'] = $model->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        $data['categorys']  = $_category;
        $data['recommends']= $_recommend;
        if($album_videoid && count($album_videoid)>0){
            foreach($album_videoid as $value) $data_[] = $value['video_id'];
            $model = $model->andFilterWhere([VideoModel::tableName().'.id'=>$data_]);
            $data['dataList'] = $model->orderBy($_order)->offset($offset)->limit($limit)->asArray()->all();
        }else{
            $data['dataList'] = array();
        }


        return $data;

    }

    /*
     * 获取可以删除专辑视频的列表
     */
    
    /*
     * 将视频转移到专辑下面
     */
    public function toalbum($albumid,$videoid){
        if(!is_array($videoid) && empty($videoid)){
            return false;
        }
        $result = true;
        foreach ($videoid as $value){
            $model = new AdretationModel();
            $model->video_id = $value;
            $model->album_id = $albumid;
            $result = $result && $model->save();
        }

        return $result;

    }

    /*
     * 将视频从某个专辑下面移除
     */
    public function deltoalbum($albumid,$videoid){
        if(!is_array($videoid) && empty($videoid)){
            return false;
        }

        $result = AdretationModel::deleteAll(['album_id'=>$albumid,'video_id'=>$videoid]);

        return $result;
    }

    /*
     * 修改视频排序
     */
    public function editSort($videoid,$sort){
        $result =  VideoModel::updateAll(['sort_order' => $sort],['id' => $videoid]);
        return $result;
    }
    

    /**
     * 编辑视频
     * 1：编辑一个新视频并且选择了一个专辑
     * 2：编辑一个信息视频新创建一个单独的专辑
     * 3：编辑一个已有的视频并且修改了专辑信息
     * 4：编辑一个已有的视频重新选择了一个专辑
     * @param $id
     * @param $albumId
     * @return bool
     */
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

    /**
     * 删除卡片
     * @param $id
     * @return bool
     */
    public function deleteVideo($id)
    {
        $result = $this->deleteInfo($id,VideoModel::className());
        if($result == true) $this->onAfterDeleteVideo($id);
        return $result;
    }

    /*
     * 审核
     * @param $id
     * @return bool
     */
    public function auditingVideo($id)
    {
        $result = $this->checkInfo($id,VideoModel::className(),0);
        return $result;
    }

    /*
     * 发布专辑
     * @param $id
     * @return bool
     */
    public function publishAlbum($id)
    {
        $result = $this->checkInfo($id,VideoAlbumModel::className(),0);
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
        $albumIds = VideoModel::find()->select('album_id')->where(['id' => $ids])->column();
        $num = VideoAlbumModel::updateAll([$column => $value],['id' => $albumIds]);
        if($num > 0) return true;
        return false;
    }
    //视频信息管理结束

    //视频分类信息管理开始{{{
    /**
     * 视频分类
     * @param $keyWord
     * @param array $other
     * @param array $order
     * @param $page
     * @param $prePage
     * @return array
     */
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

    /**
     * 获取所有的分类
     * @return array
     */
    public function categories()
    {
        $model = VideoCategoryModel::find()->select(['id','name','parent_id'])
            ->where(['status' => VideoCategoryModel::STATUS_ACTIVE])
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


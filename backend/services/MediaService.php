<?php
namespace backend\services;

use common\events\MediaEvent;
use common\helpers\ExcelHelper;
use common\helpers\TreeHelper;
use common\models\mysql\MediaLinesModel;
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

    public function linesList($sourceId, $lang, $page, $count)
    {
        list($offset,$limit) = $this->parsePageParam($page,$count);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = MediaLinesModel::find()
            ->where([
                'source_id' => $sourceId,
                'status' =>  MediaLinesModel::STATUS_ACTIVE,
            ])
            ->andFilterWhere([])
            ->andFilterWhere(['lang_type' => $lang]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy(['line_number' => SORT_ASC,'lang_type' => SORT_ASC])
                ->limit($limit)
                ->offset($offset)
                ->all();

        return $data;
    }

    public function editLines($id)
    {
        $model = $this->editInfo($id,MediaLinesModel::className());
        return $model;
    }

    public function deleteLines($id)
    {
        $result = $this->deleteInfo($id,MediaLinesModel::className());
        return $result;
    }

    public function addLinesFromExcel($sourceId, $file)
    {
        $lines = ExcelHelper::readExcel($file);
        //删除文件
//        unlink($file);
        foreach ($lines as $key => $line){
            if($key == 0) continue;
            $data = [
                'line_number' => $line[0],
                'start_time' => $line[1],
                'end_time' => $line[2],
                'lang_type' => $line[3],
                'content' => $line[4],
                'source_id' => $sourceId,
            ];
            //先删除存在的台词
            MediaLinesModel::updateAll(['status' => MediaLinesModel::STATUS_DELETED],[
                'source_id' => $sourceId,
                'line_number' => $data['line_number'],
                'lang_type' => $data['lang_type'],
            ]);
            //插入新纪录
            $model = new MediaLinesModel();
            $model->add($data);
        }
    }

    public function addLinesFromFile($sourceId, $lang, $file){
        $lines = explode("\n", file_get_contents($file));
        $item = ['source_id' => $sourceId];
        foreach ($lines as $key => $value){
            $index = $key % 5;
            if(!$index and !trim($value)) break;

            switch ($index){
                case 0: $item['line_number'] = trim($value); break;
                case 1:
                    $time = explode('-->', $value);
                    $item['start_time'] = trim($time[0]);
                    $item['end_time'] = trim($time[1]);
                    break;
                case 2: //处理中文
                case 3: //处理其他语言
                    $item['lang_type'] = $index == 2 ? 'zh_CN' : $lang;
                    $item['content'] = trim($value);
                    //先删除存在的台词
                    MediaLinesModel::deleteAll([
                        'source_id' => $sourceId,
                        'line_number' => $item['line_number'],
                        'lang_type' => $item['lang_type'],
                    ]);
                    //插入新纪录
                    $model = new MediaLinesModel();
                    $model->add($item);
                    break;
                default:
                    break;
            }
        }
    }
    //编辑视频 音频
    public function editMedia($id)
    {
        $model = $this->editInfo($id,MediaModel::className());
        //判断是否有台词文件
        $lineFile = \Yii::$app->request->post('lines');
        $lang = \Yii::$app->request->post('MediaModel')['lang_type'];
        if($model->source_type != 3 and $lineFile){
            $lineFile = realpath(__DIR__.'/../../frontend/web/upload/media-pdf/'.basename($lineFile));
            if(file_exists($lineFile))
                $this->addLinesFromFile($model->id, $lang, $lineFile);
        }

        return $model;
    }

    // 删除
    public function deleteMedia($id)
    {
        $result = $this->deleteInfo($id,MediaModel::className());
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


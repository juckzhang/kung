<?php
namespace common\services;

use common\constants\CodeConstant;
use common\events\MediaEvent;
use common\models\mysql\MediaCommentModel;
use common\models\mysql\MediaLinesModel;
use common\models\mysql\MediaModel;
use common\services\base\OperationService;

class MediaService extends OperationService
{
    const AFTER_SCANNED_MEDIA  = 'after_scanned_media';
    const AFTER_COLLECT_MEDIA  = 'after_collect_media';
    const AFTER_CANCEL_COLLECT_MEDIA = 'after_cancel_collect_media';
    const AFTER_DOWNLOAD_MEDIA = 'after_download_media';

    protected $collectModel = 'common\models\mysql\MediaCollectionModel';//收藏model
    protected $scanModel = 'common\models\mysql\MediaLookModel'; //浏览model
    protected $downloadModel = 'common\models\mysql\MediaDownloadModel'; //下载model
    protected $sourceModel = 'common\models\mysql\MediaModel';//操作的资源model

    public function behaviors()
    {
        return [
            'mediaBehavior' => [
                'class' => 'common\behaviors\MediaBehavior',
            ],
        ];
    }

    //视频/音频列表
    public function mediaList($cateId,$page,$prePage,$sourceType = null, $order = 'create_time')
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = MediaModel::find()
            ->where(['status' => MediaModel::STATUS_ACTIVE])
            ->andFilterWhere(['source_type' => $sourceType])
            ->andFilterWhere(['cate_id' => $cateId]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $models = $models->orderBy([$order => SORT_DESC])
                ->offset($offset)->limit($limit)->all();
            foreach($models as $item)
                $data['dataList'][] = $item->toArray();
        }

        return $data;
    }

    //推荐列表
    public function recommendList($page, $count)
    {
        list($offset,$limit) = $this->parsePageParam($page, $count);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = MediaModel::find()
            ->where(['status' => MediaModel::STATUS_ACTIVE]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $models = $models->orderBy(['sort_order' => SORT_ASC,'create_time' => SORT_DESC])
                ->asArray()
                ->offset($offset)->limit($limit)->all();
            $data['dataList'] = $models;
        }

        return $data;
    }

    // 台词列表
    public function mediaLines($id, $lang){
        $models = MediaLinesModel::find()
            ->where(['source_id' => $id, 'lang_type' => ['zh_CN', $lang]])
            ->andWhere(['status' => MediaLinesModel::STATUS_ACTIVE])
            ->orderBy(['line_number' => SORT_ASC, 'lang_type' => SORT_ASC])
            ->asArray()
            ->all();
        $data = [];
        foreach($models as $line){
            $lineNumber = $line['line_number'];
            if(! empty($lines[$lineNumber])){
                $data[$lineNumber]['content'][$line['lang_type']] = $line['content'];
            }else{
                $data[$lineNumber] = [
                    'start_time' => $line['start_time'],
                    'end_time' => $line['end_time'],
                    'line' => $line['line_number'],
                    'content' => [$line['lang_type'] => $line['content']]
                ];
            }
        }

        return $data;
    }

    //视频音频资源详情
    public function mediaDetails($id, $uid){
        $data = MediaModel::find()
            ->where(['id' => $id])
            ->andWhere(['status'=>MediaModel::STATUS_ACTIVE])
            ->asArray()
            ->one();
        if($uid){
            $this->onAfterScannedMedia($id, $uid);
        }
        return $data;
    }

    //评论列表
    public function commentList($media,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = MediaCommentModel::find()
            ->select(['id','user_id','content','create_time'])
            ->where(['source_id' => $media,'status' => MediaCommentModel::STATUS_ACTIVE]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page < $data['pageCount'])
        {
            $models = $models->with('user')->orderBy(['create_time' => SORT_DESC])->offset($offset)->limit($limit)->all();
            foreach($models as $item){
                $album = $item->toArray();
                $album['user'] = $item->user->toArray();
                $data['dataList'][] = $album;
            }
        }

        return $data;
    }

    //评论
    public function mediaComment($media,$content,$userId)
    {
        $check = $this->check($media, $userId);
        if($check !== true){
            return $check;
        }
        $model = new MediaCommentModel();
        $id = $model->add(['user_id' => $userId, 'source_id' => $media, 'content' => $content]);
        if (is_numeric($id)) return true;
        return CodeConstant::MEDIA_COMMENT_FAILED;
    }

    //收藏与取消收藏
    public function collectMedia($media,$userId)
    {
        $return = $this->collect($media,$userId);
        if(is_numeric($return)) return $return;
        //判断是收藏还是取消收藏
        if($return['operation'] == static::POSITIVE_OPERATION)
        {
            if($return['status'] == true){
                $this->onAfterCollect($return['operationId'],$media,$userId);
                return true;
            }
            return CodeConstant::COLLECT_ALBUM_FAILED;
        }

        if($return['status'] == true){
            $this->onAfterCancelCollect($return['operationId'],$media,$userId);
            return true;
        }
        return CodeConstant::CANCEL_COLLECT_ALBUM_FAILED;
    }

    //下载
    public function downloadMedia($media,$userId)
    {
        $return = $this->download($media,$userId);
        if($return != true) {
            $this->onAfterDownload($media, $userId);
            return CodeConstant::DOWNLOAD_MEDIA_FAILED;
        }

        return true;
    }

    private function onAfterDownload($media,$userId)
    {
        $event = new MediaEvent(['mediaId' => $media,'userId' => $userId]);
        $this->trigger(static::AFTER_DOWNLOAD_MEDIA,$event);
    }

    private function onAfterCollect($operationId,$media,$userId)
    {
        $event = new MediaEvent(['operationId' => $operationId,'mediaId' => $media,'userId' => $userId]);
        $this->trigger(static::AFTER_COLLECT_MEDIA,$event);
    }

    private function onAfterCancelCollect($operationId,$media,$userId)
    {
        $event = new MediaEvent(['operationId' => $operationId,'mediaId' => $media,'userId' => $userId]);
        $this->trigger(static::AFTER_CANCEL_COLLECT_MEDIA,$event);
    }

    private function onAfterScannedMedia($media,$userId)
    {
        $event = new MediaEvent(['mediaId' => $media,'userId' => $userId]);
        $this->trigger(static::AFTER_SCANNED_MEDIA,$event);
    }
}
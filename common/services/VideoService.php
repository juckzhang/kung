<?php
namespace common\services;

use common\constants\CodeConstant;
use common\events\VideoEvent;
use common\models\mysql\VideoCommentModel;
use common\models\mysql\VideoLinesModel;
use common\models\mysql\VideoModel;
use common\services\base\OperationService;

class VideoService extends OperationService
{
    const AFTER_SCANNED_VIDEO  = 'after_scanned_video';
    const AFTER_COLLECT_VIDEO  = 'after_collect_video';
    const AFTER_CANCEL_COLLECT_VIDEO = 'after_cancel_collect_video';
    const AFTER_DOWNLOAD_VIDEO = 'after_download_video';

    protected $collectModel = 'common\models\mysql\VideoCollectionModel';//收藏model
    protected $scanModel = 'common\models\mysql\VideoLookModel'; //浏览model
    protected $downloadModel = 'common\models\mysql\VideoDownloadModel'; //下载model
    protected $sourceModel = 'common\models\mysql\VideoModel';//操作的资源model

    public function behaviors()
    {
        return [
            'videoBehavior' => [
                'class' => 'common\behaviors\VideoBehavior',
            ],
        ];
    }

    //视频/音频列表
    public function videoList($cateId,$page,$prePage,$sourceType = null, $order = 'create_time')
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = VideoModel::find()
            ->where(['status' => VideoModel::STATUS_ACTIVE])
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
        $models = VideoModel::find()
            ->where(['status' => VideoModel::STATUS_ACTIVE]);
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
    public function videoLines($id, $lang){
        $models = VideoLinesModel::find()
            ->where(['source_id' => $id, 'lang_type' => ['zh_CN', $lang]])
            ->andWhere(['status' => VideoLinesModel::STATUS_ACTIVE])
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
    public function videoDetails($id, $uid){
        $data = VideoModel::find()
            ->where(['id' => $id])
            ->andWhere(['status'=>VideoModel::STATUS_ACTIVE])
            ->asArray()
            ->one();
        if($uid){
            $this->onAfterScannedVideo($id, $uid);
        }
        return $data;
    }

    //评论列表
    public function commentList($video,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = VideoCommentModel::find()
            ->select(['id','user_id','content','create_time'])
            ->where(['source_id' => $video,'status' => VideoCommentModel::STATUS_ACTIVE]);
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
    public function videoComment($video,$content,$userId)
    {
        $check = $this->check($video, $userId);
        if($check !== true){
            return $check;
        }
        $model = new VideoCommentModel();
        $id = $model->add(['user_id' => $userId, 'source_id' => $video, 'content' => $content]);
        if (is_numeric($id)) return true;
        return CodeConstant::VIDEO_COMMENT_FAILED;
    }

    //收藏与取消收藏
    public function collectVideo($video,$userId)
    {
        $return = $this->collect($video,$userId);
        if(is_numeric($return)) return $return;
        //判断是收藏还是取消收藏
        if($return['operation'] == static::POSITIVE_OPERATION)
        {
            if($return['status'] == true){
                $this->onAfterCollect($return['operationId'],$video,$userId);
                return true;
            }
            return CodeConstant::COLLECT_ALBUM_FAILED;
        }

        if($return['status'] == true){
            $this->onAfterCancelCollect($return['operationId'],$video,$userId);
            return true;
        }
        return CodeConstant::CANCEL_COLLECT_ALBUM_FAILED;
    }

    //下载
    public function downloadVideo($video,$userId)
    {
        $return = $this->download($video,$userId);
        if($return != true) {
            $this->onAfterDownload($video, $userId);
            return CodeConstant::DOWNLOAD_VIDEO_FAILED;
        }

        return true;
    }

    private function onAfterDownload($video,$userId)
    {
        $event = new VideoEvent(['videoId' => $video,'userId' => $userId]);
        $this->trigger(static::AFTER_DOWNLOAD_VIDEO,$event);
    }

    private function onAfterCollect($operationId,$video,$userId)
    {
        $event = new VideoEvent(['operationId' => $operationId,'videoId' => $video,'userId' => $userId]);
        $this->trigger(static::AFTER_COLLECT_VIDEO,$event);
    }

    private function onAfterCancelCollect($operationId,$video,$userId)
    {
        $event = new VideoEvent(['operationId' => $operationId,'videoId' => $video,'userId' => $userId]);
        $this->trigger(static::AFTER_CANCEL_COLLECT_VIDEO,$event);
    }

    private function onAfterScannedVideo($video,$userId)
    {
        $event = new VideoEvent(['videoId' => $video,'userId' => $userId]);
        $this->trigger(static::AFTER_SCANNED_VIDEO,$event);
    }
}
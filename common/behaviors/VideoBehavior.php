<?php
namespace common\behaviors;

use common\models\mysql\VideoModel;
use common\services\VideoService;
use yii\base\Behavior;

class VideoBehavior extends Behavior{

    public function events()
    {
        return [
            VideoService::AFTER_SCANNED_VIDEO => 'afterScanned',
            VideoService::AFTER_CANCEL_COLLECT_VIDEO => 'afterCancelCollect',
            VideoService::AFTER_COLLECT_VIDEO => 'afterCollect',
            VideoService::AFTER_DOWNLOAD_VIDEO => 'afterDownload',
        ];
    }

    public function afterScanned($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof VideoService)
            $event->sender->scanned($event->videoId,$event->userId);

        //将数据添加增加1
        VideoModel::updateAllCounters(['play_num' => 1, 'real_play_num' => 1],['id' => $event->videoId]);
    }

    public function afterCancelCollect($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof VideoService){
            //将数据添加增加1
            VideoModel::updateAllCounters(['collection_num' => -1, 'real_collection_num' => -1],['id' => $event->videoId]);
        }
    }

    public function afterCollect($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof VideoService){
            //将数据添加增加1
            VideoModel::updateAllCounters(['collection_num' => 1, 'real_collection_num' => 1],['id' => $event->videoId]);
        }
    }

    public function afterDownload($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof VideoService){
            //将数据添加增加1
            VideoModel::updateAllCounters(['download_num' => 1, 'real_download_num' => 1],['id' => $event->videoId]);
        }
    }
}
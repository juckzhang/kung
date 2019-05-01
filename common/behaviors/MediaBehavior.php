<?php
namespace common\behaviors;

use common\models\mysql\MediaModel;
use common\services\MediaService;
use yii\base\Behavior;

class MediaBehavior extends Behavior{

    public function events()
    {
        return [
            MediaService::AFTER_SCANNED_MEDIA => 'afterScanned',
            MediaService::AFTER_CANCEL_COLLECT_MEDIA => 'afterCancelCollect',
            MediaService::AFTER_COLLECT_MEDIA => 'afterCollect',
            MediaService::AFTER_DOWNLOAD_MEDIA => 'afterDownload',
        ];
    }

    public function afterScanned($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof MediaService)
            $event->sender->scanned($event->mediaId,$event->userId);

        //将数据添加增加1
        MediaModel::updateAllCounters(['play_num' => 1, 'real_play_num' => 1],['id' => $event->mediaId]);
    }

    public function afterCancelCollect($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof MediaService){
            //将数据添加增加1
            MediaModel::updateAllCounters(['collection_num' => -1, 'real_collection_num' => -1],['id' => $event->mediaId]);
        }
    }

    public function afterCollect($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof MediaService){
            //将数据添加增加1
            MediaModel::updateAllCounters(['collection_num' => 1, 'real_collection_num' => 1],['id' => $event->mediaId]);
        }
    }

    public function afterDownload($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof MediaService){
            //将数据添加增加1
            MediaModel::updateAllCounters(['download_num' => 1, 'real_download_num' => 1],['id' => $event->mediaId]);
        }
    }
}
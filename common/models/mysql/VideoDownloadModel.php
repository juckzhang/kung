<?php
/**
 * Created by PhpStorm.
 * User: dongbin
 * Date: 11/05/2017
 * Time: 5:59 PM
 */

namespace common\models\mysql;


class VideoDownloadModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%video_download}}";
    }

    public function getVideo()
    {
        return $this->hasOne(VideoModel::className(),['id' => 'source_id']);
    }
}
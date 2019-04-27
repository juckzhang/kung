<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 12:14
 */

namespace common\models\mysql;


class VideoCollectionModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%video_collection}}";
    }

    public function getVideo()
    {
        return $this->hasOne(VideoModel::className(),['id' => 'source_id']);
    }
}
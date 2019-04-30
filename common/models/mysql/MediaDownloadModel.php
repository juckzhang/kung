<?php
namespace common\models\mysql;

class MediaDownloadModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%media_download}}";
    }

    public function getMedia()
    {
        return $this->hasOne(MediaModel::className(),['id' => 'source_id'])
            ->where(['status' => MediaModel::STATUS_ACTIVE]);
    }
}
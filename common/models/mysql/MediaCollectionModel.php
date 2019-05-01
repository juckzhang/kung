<?php
namespace common\models\mysql;

class MediaCollectionModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%media_collection}}";
    }

    public function getMedia()
    {
        return $this->hasOne(MediaModel::className(),['id' => 'source_id'])
            ->where(['status' => MediaModel::STATUS_ACTIVE])
            ->with('category');
    }
}
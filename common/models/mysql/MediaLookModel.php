<?php
namespace common\models\mysql;

class MediaLookModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%media_look}}";
    }

    public function getMedia()
    {
        return $this->hasOne(MediaModel::className(),['id' => 'source_id'])
            ->where(['status' => MediaModel::STATUS_ACTIVE]);
    }
}
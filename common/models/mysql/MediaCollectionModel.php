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
            ->select([
                'id','cate_id','source_type','title',
                'title_en','total_time','level','poster_url',
                'play_link','play_num','collection_num',
                'download_num','create_time'
            ])
            ->where(['status' => MediaModel::STATUS_ACTIVE])
            ->with('category');
    }
}
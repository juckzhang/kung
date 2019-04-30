<?php
namespace common\models\mysql;

class MediaModel extends ActiveRecord
{
    const IS_RECOMMEND = 1;
    const NOT_RECOMMEND = 0;

    public static function tableName()
    {
        return "{{%media}}";
    }

    public function getLines()
    {
        return $this->hasMany(MediaLinesModel::className(),['source_id' => 'id'])
            ->where(['status' => MediaLinesModel::STATUS_ACTIVE])
            ->select(['source_id','lang_type','line_number','content','start_time','end_time'])
            ->orderBy(['line_number' => SORT_ASC,'lang_type' => SORT_ASC])
            ->asArray();
    }
}
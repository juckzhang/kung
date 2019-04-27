<?php
namespace common\models\mysql;


class VideoLinesModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%video_lines}}";
    }


}
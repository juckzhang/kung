<?php
namespace common\models\mysql;


class MediaLinesModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%media_lines}}";
    }


}
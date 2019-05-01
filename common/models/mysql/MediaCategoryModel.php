<?php
namespace common\models\mysql;

class MediaCategoryModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%media_category}}";
    }
}
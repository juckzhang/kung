<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 12:14
 */

namespace common\models\mysql;


class MediaCategoryModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%media_category}}";
    }
}
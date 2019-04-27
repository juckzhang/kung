<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 12:14
 */

namespace common\models\mysql;


class AdminOperationModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%admin_operation}}";
    }
}
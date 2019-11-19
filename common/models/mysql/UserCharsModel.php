<?php
namespace common\models\mysql;

class UserCharsModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%user_chars}}";
    }
}
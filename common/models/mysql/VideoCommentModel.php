<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 12:14
 */

namespace common\models\mysql;


class VideoCommentModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%video_comment}}";
    }

    public function getUser(){
        return $this->hasOne(UserModel::className(),['id' => 'user_id'])->select(['id', 'nick_name','icon_url']);
    }
}
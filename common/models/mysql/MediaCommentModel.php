<?php
namespace common\models\mysql;

class MediaCommentModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%media_comment}}";
    }

    public function getUser(){
        return $this->hasOne(UserModel::className(),['id' => 'user_id'])->select(['id', 'nick_name','icon_url']);
    }

    public function getMedia(){
        return $this->hasOne(MediaModel::className(),['id' => 'source_id'])->select(['id', 'title']);
    }
}
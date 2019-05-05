<?php
namespace common\models\mysql;

class FeedbackModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%feedback}}";
    }

    public function getFeedback()
    {
        return $this->hasOne(FeedbackModel::className(), ['parent_id' => 'id'])
            ->select(['id','parent_id','user_id','content','create_time'])
            ->where(['status' => FeedbackModel::STATUS_ACTIVE])
            ->asArray();
    }
}
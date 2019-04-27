<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 12:14
 */

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
            ->where(['status' => FeedbackModel::STATUS_ACTIVE])
            ->asArray();
    }
}
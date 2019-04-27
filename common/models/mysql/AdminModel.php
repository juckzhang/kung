<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 12:14
 */

namespace common\models\mysql;


class AdminModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%admin}}";
    }

    public function rules()
    {
        parent::rules();
        return [
            [['username','password'],'required'],
            ['password','encryptPassword'],
        ];
    }

    /**
     * @param $attribute
     */
    public function encryptPassword($attribute)
    {
        if($attribute == 'password')
            $this->password = \Yii::$app->security->generatePasswordHash($this->password);
    }
}
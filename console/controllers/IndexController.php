<?php
namespace console\controllers;

use common\models\mysql\AdminModel;

class IndexController extends BaseController{
    // 注册用户
    public function actionRegister($username, $password)
    {
//        $username = 'admin';
//        $password = 'admin123';
        $model = new AdminModel();
        $password = \Yii::$app->security->generatePasswordHash($password);
        $model->add(['username' => $username, 'password' => $password]);
    }
}
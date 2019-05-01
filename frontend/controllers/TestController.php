<?php
namespace frontend\controllers;

class TestController extends BaseController
{
    public function actionTest(){
        var_dump(\Yii::$app->request->headers->get('Access-Token'));
    }
}

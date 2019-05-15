<?php
namespace frontend\controllers;

use common\models\mysql\UserModel;
use Yii;
use common\controllers\CommonController;
use yii\helpers\ArrayHelper;

class BaseController extends CommonController
{
    protected $paramData = [];

    public $enableCsrfValidation = false;
    //public $layout = true;

    public function beforeAction($action)
    {
        if(YII_ENV == 'product'){
            $_GET['user_id'] = $_POST['user_id'] = null;
        }

        $accessToken = \Yii::$app->request->headers->get('Access-Token');
        $lang = \Yii::$app->request->headers->get('App-Lang','zh_CN');
//        $_GET['lang'] = $lang;
        if($accessToken){
            $model = UserModel::findOne(['access_token' => $accessToken]);
            if($model instanceof UserModel){
                $_GET['user_id'] = $_POST['user_id'] = $model->id;
            }
        }
        $this->paramData = $this->parseParam();

        return parent::beforeAction($action);
    }

    public function getParamData()
    {
        return $this->paramData;
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    protected function parseParam()
    {
        $_requestParam = Yii::$app->getRequest()->getPost();
        if(is_object($_requestParam)){
            $_requestParam = json_decode($_requestParam,true);
        }

        return $_requestParam;
    }

    protected function _sortOrder($prefix='')
    {
        $orderFiled = ArrayHelper::getValue($this->paramData,'orderFiled',$prefix.'create_time');
        $orderDesc  = ArrayHelper::getValue($this->paramData,'orderDirection','desc');

        $desc = SORT_DESC;
        if($orderDesc == 'asc')  $desc = SORT_ASC;
        return [$orderFiled => $desc];
    }
}

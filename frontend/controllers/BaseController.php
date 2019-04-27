<?php
namespace frontend\controllers;

use Yii;
use common\controllers\CommonController;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class BaseController extends CommonController
{
    protected $paramData = [];

    public $enableCsrfValidation = false;
    //public $layout = true;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->paramData = $this->parseParam();
            return true;
        }
        return false;
    }

    public function getParamData()
    {
        return $this->paramData;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 解析参数
     *
     * @return mixed
     */
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

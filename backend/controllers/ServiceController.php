<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\ActiveRecord;
use common\models\mysql\ServiceModel;
use common\models\mysql\ServiceOrderModel;
use Yii;
use backend\services\SpaceService;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Service controller
 */
class ServiceController extends BaseController
{
    //场景管理开始{{{
    /**
     * 场景列表
     * @return string
     */
    public function actionServiceList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyWord');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();

        $data = SpaceService::getService()->serviceList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('service-list',$data);
    }

    /**
     * 编辑场景
     * @return string
     */
    public function actionEditService()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = SpaceService::getService()->editService($id);

            if($result instanceof ServiceModel)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'service-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['service/service-list'])
                ]);
            return $this->returnAjaxError($result);
        }else {
            $id = ArrayHelper::getValue($this->paramData, 'id');
            $model = ServiceModel::find()->where(['id' => $id])->asArray()->one();
            return $this->render('edit-service', ['model' => $model]);
        }
    }

    /**
     * 删除场景
     * @return mixed
     */
    public function actionDeleteService()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = SpaceService::getService()->deleteService($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'service-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['service/service-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    /*
     * 订单列表
     * @return array
     */
    public function actionServiceOrder()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyWord');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();

        $data = SpaceService::getService()->serviceOrder($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('service-order',$data);
    }

    /*
     * 删除订单
     * @return mixed
     */
    public function actionDeleteOrder()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = SpaceService::getService()->deleteOrder($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'service-order',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['service/service-order'])
            ]);
        return $this->returnAjaxError($return);
    }

    /**
     * 编辑订单
     * @return string
     */
    public function actionEditOrder()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = SpaceService::getService()->editOrder($id);

            if($result instanceof ServiceOrderModel)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'service-order',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['service/service-order'])
                ]);
            return $this->returnAjaxError($result);
        }else {
            $id = ArrayHelper::getValue($this->paramData, 'id');
            $model = ServiceOrderModel::find()->where(['id' => $id])->asArray()->one();
            $disbales = SpaceService::getService()->serviceDisableTime();
            return $this->render('edit-order', ['model' => $model,'disbales' => \GuzzleHttp\json_encode($disbales)]);
        }
    }











    //场景管理结束}}}
}
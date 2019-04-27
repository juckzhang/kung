<?php
/**
 * Created by PhpStorm.
 * User: dongbin
 * Date: 15/05/2017
 * Time: 5:58 PM
 */

namespace backend\controllers;

use Yii;
use common\constants\CodeConstant;
use common\models\mysql\CouponModel;
use common\models\mysql\CouponTemplateModel;
use common\services\CouponService;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class CouponController extends BaseController
{
    public function actionMake() {
        $template_id = ArrayHelper::getValue($this->paramData, 'template_id');
        $count = ArrayHelper::getValue($this->paramData, 'count');
        $result = CouponService::getService()->make($template_id, $count);
        if ($result === CouponModel::PROCESS_SUCCESS) {
            return $this->returnAjaxSuccess([
                'message' => '添加成功',
                'navTabId' => 'coupon-list',
                'callbackType' => 'forward',
                'forwardUrl' => Url::to(['coupon/coupon-list'])
            ]);
        } else {
            return $this->returnAjaxError(false);
        }
    }

    /*
     * 获取代金券模板列表
     */
    public function actionTemplateList() {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyWord');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();
        $data    = CouponService::getService()->templateList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('template-list', $data);
    }



    public function actionEditTemplate() {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = CouponService::getService()->editTemplate($id);

            if($result instanceof CouponTemplateModel)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'template-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['coupon/template-list'])
                ]);
            return $this->returnAjaxError($result);
        }else {
            $id = ArrayHelper::getValue($this->paramData, 'id');
            $model = CouponTemplateModel::find()->where(['id' => $id])->asArray()->one();
            return $this->render('edit-template', ['model' => $model]);
        }
    }

    /**
     * 删除模板
     * @return mixed
     */
    public function actionDeleteTemplate()
    {
        if(! Yii::$app->request->getIsAjax())
            return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = CouponService::getService()->deleteTemplate($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'template-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['coupon/template-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    /*
     * 删除代金券
     */
    public function actionDeleteCoupon(){
        if(! Yii::$app->request->getIsAjax())
            return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = CouponService::getService()->deleteCoupon($ids);
        if($return instanceof CouponService)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'coupon-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['coupon/coupon-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    /*
     * 代金券列表
     */
    public function actionList(){
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyWord');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();
        $data    = CouponService::getService()->couponList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('coupon-list', $data);
    }

    /*
     * 代金券生成
     */
    public function actionEditCoupon()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $coupon = ArrayHelper::getValue($this->paramData,'coupon');
            $result = true;
            foreach ($coupon as $key => $value){
                $result = $result && CouponService::getService()->make($value['template_id'],$value['num']);
            }

            if($result)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'coupon-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['coupon/coupon-list'])
                ]);
            return $this->returnAjaxError($result);
        }else {
            //获取出所有代金券模板
            $data    = CouponTemplateModel::find()
                ->where(['status'=>CouponTemplateModel::STATUS_ACTIVE])
                ->andFilterWhere(['>','end_time',time()])
                ->asArray()->all();
            return $this->render('edit-coupon', ['dataList'=>$data]);
        }
    }

    /*
     * 代金券发放
     */
    public function actionAssign()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $coupon = ArrayHelper::getValue($this->paramData,'coupon');
            $result = true;
            $data = [];
            foreach ($coupon as $key => $value){
                $code = CouponService::getService()->assign($value['template_id'],$value['num']);
                foreach ($code as $key => $value){
                    $data[] = $value['code'];
                }
            }
            $source = implode(',',$data);
            //var_dump($source);exit;


            if(count($data)>0){

                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'assign',
                    'callbackType' => 'closeCurrent',
                    'url' => Url::to(['coupon/assign.html','coupon'=>$source])
                ]);
            }

            return $this->returnAjaxError($result);
        }else {
            //获取出所有代金券模板
            $data    = CouponTemplateModel::find()
                ->where(['status'=>CouponTemplateModel::STATUS_ACTIVE])
                ->andFilterWhere(['>','end_time',time()])
                ->asArray()->all();
            foreach ($data as $key => $value){
                $count = CouponModel::find()->where(['template_id'=>$value['id'],'assign_time'=>null])->count();
                $data[$key]['count'] = $count;
            }
            return $this->render('assign', ['dataList'=>$data]);
        }
    }


}

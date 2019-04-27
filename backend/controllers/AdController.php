<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\AdMobileScreenModel;
use common\models\mysql\AdModel;
use Yii;
use backend\services\AdService;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class AdController extends BaseController
{
    /**
     * 广告列表
     * @return string
     * @throws \yii\mongodb\Exception
     */
    public function actionAdList()
    {
        $_positionId = ArrayHelper::getValue($this->paramData,'positionId');
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_order = $this->_sortOrder();

        $data = AdService::getService()->adList($_positionId,$_order,$_page,$_prePage);
        return $this->render('index',$data);
    }

    public function actionMobileScreenList()
    {
        $_positionId = ArrayHelper::getValue($this->paramData,'positionId');
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_order = $this->_sortOrder();

        $data = AdService::getService()->mobileScreenList($_positionId,$_order,$_page,$_prePage);
        return $this->render('mobile-screen-list', $data);
    }

    /**
     * 编辑广告
     * @return string
     */
    public function actionEditAd()
    {
        $id = ArrayHelper::getValue($this->paramData,'id');
        if(\Yii::$app->request->getIsPost())
        {
            $result = AdService::getService()->editAd($id);

            if($result instanceof AdModel)
                return $this->returnAjaxSuccess([
                    'message' => '广告编辑成功',
                    'navTabId' => 'ad-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['ad/ad-list','positionId' => $result->id])
                ]);
            return $this->returnAjaxError($result);
        }else{
            //获取广告id
            $model = AdModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-ad',['model' => $model]);
        }
    }

    public function actionEditMobileScreen()
    {
        $id = ArrayHelper::getValue($this->paramData,'id');
        if(\Yii::$app->request->getIsPost())
        {
            $result = AdService::getService()->editMobileScreen($id);

            if($result instanceof AdMobileScreenModel)
                return $this->returnAjaxSuccess([
                    'message' => '移动端开屏广告编辑成功',
                    'navTabId' => 'mobile-screen-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['ad/mobile-screen-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            //获取广告id
            $model = AdMobileScreenModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-mobile-screen',['model' => $model]);
        }
    }

    /**
     * 删除广告
     * @return string
     */
    public function actionDeleteAd()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        //批量删除
        $return = AdService::getService()->deleteAd($ids);

        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '广告编辑成功',
                'navTabId' => 'ad-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['ad/ad-list'])
            ]);
        return $this->returnAjaxError(-100);
    }
}
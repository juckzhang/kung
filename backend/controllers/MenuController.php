<?php
namespace backend\controllers;

use backend\services\MenuService;
use common\constants\CodeConstant;
use common\models\mysql\MenuModel;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MenuController extends BaseController
{
    /**
     * 广告列表
     * @return string
     * @throws \yii\mongodb\Exception
     */
    public function actionMenuList()
    {
        $_positionId = ArrayHelper::getValue($this->paramData,'positionId');
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_order = $this->_sortOrder();
        $data = MenuService::getService()->menuList($_positionId,$_order,$_page,$_prePage);
        return $this->render('menu-list',$data);
    }

    /**
     * 编辑广告
     * @return string
     */
    public function actionEditMenu()
    {
        $id = ArrayHelper::getValue($this->paramData,'id');
        if(\Yii::$app->request->getIsPost())
        {
            $result = MenuService::getService()->editMenu($id);

            if($result instanceof ActiveRecord)
                return $this->returnAjaxSuccess([
                    'message' => '广告编辑成功',
                    'navTabId' => 'menu-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['menu/menu-list','positionId' => $result->position_id])
                ]);
            return $this->returnAjaxError($result);
        }else{
            //获取广告id
            $model = MenuModel::find()->where(['id' => $id])->asArray()->one();
            $menus = MenuService::getService()->menus();
            return $this->render('edit-menu',['model' => $model,'menus' => $menus]);
        }
    }

    /**
     * 删除广告
     * @return string
     */
    public function actionDeleteMenu()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        //批量删除
        $return = MenuService::getService()->deleteMenu($ids);

        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '广告编辑成功',
                'navTabId' => 'menu-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['menu/menu-list'])
            ]);
        return $this->returnAjaxError(-100);
    }
}
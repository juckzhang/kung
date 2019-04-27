<?php
/**
 * Created by PhpStorm.
 * User: dongbin
 * Date: 13/07/2017
 * Time: 4:34 PM
 */

namespace backend\controllers;

use backend\services\FilmfestService;
use common\models\mysql\FilmfestModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Site controller
 */
class FilmfestController extends BaseController
{
    public function actionSignupList()
    {
        $service = FilmfestService::getService();
        $data    = $service->signupList();
        return $this->render('filmfest-signup-list',['dataList' => $data, 'dataCount' => count($data)]);
    }

    public function actionShowSignupInfo()
    {
        $service = FilmfestService::getService();
        $id      = ArrayHelper::getValue($this->paramData, 'id');
        $data    = $service->signupInfo($id);
        return $this->render('filmfest-signup-info', ['data' => $data]);
    }

    public function actionFilmfestList()
    {
        $_prePage = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page    = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord = ArrayHelper::getValue($this->paramData,'keyWord');
        $_order   = $this->_sortOrder();
        $data     = FilmfestService::getService()->filmfestList($_keyWord, $_order, $_page, $_prePage);
        return $this->render('filmfest-list', $data);
    }

    public function actionEditFilmfest()
    {
        if(\Yii::$app->request->getIsPost()) {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $filmfest = ArrayHelper::getValue($this->paramData, 'FilmfestModel', '');
            $price = $filmfest['price'];
            if (intval($price < 1.0)) {
                return $this->returnAjaxError(500);
            }

            $result = FilmfestService::getService()->editFilmfest($id);
            if($result instanceof FilmfestModel)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'filmfest-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['filmfest/filmfest-list'])
                ]);
            return $this->returnAjaxError($result);
        } else {
            $id    = ArrayHelper::getValue($this->paramData, 'id');
            $model = FilmfestModel::find()->where(['id' => $id])->asArray()->one();
            return $this->render('edit-filmfest', ['model' => $model]);
        }
    }
}

<?php
namespace backend\controllers;

use common\models\mysql\UserModel;
use Yii;
use common\models\LoginForm;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * 首页
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) return $this->redirect(['site/login']);

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        //return $this->goHome();
        $model = new LoginForm();
        return $this->render('login',[
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionChangePassword()
    {
        if(\Yii::$app->request->getIsPost()) {
            $userId = ArrayHelper::getValue($this->paramData,'userId');
            $model = UserModel::findOne($userId);
            if ($model->load(Yii::$app->request->post()) && $model->save())
                return $this->returnAjaxSuccess([
                    'message'  => '密码修改成功',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => '',
                ]);
            return $this->returnAjaxError(-100);
        } else {
            return $this->render('change-password');
        }
    }
}

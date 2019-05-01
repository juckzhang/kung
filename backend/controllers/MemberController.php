<?php
namespace backend\controllers;

use backend\services\MemberService;
use common\constants\CodeConstant;
use common\models\mysql\QualificationModel;
use common\models\mysql\UserModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MemberController extends BaseController
{
    public function actionMemberList()
    {
        $_keyWord = ArrayHelper::getValue($this->paramData,'keyword');
        $_other   = ArrayHelper::getValue($this->paramData,'other',[]);
        $_prePage = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page    = ArrayHelper::getValue($this->paramData,'pageNum');
        $_order   = $this->_sortOrder();
        $data     = MemberService::getService()->memberList($_keyWord, $_other, $_order, $_page, $_prePage);
        return $this->render('member-list',$data);
    }

    public function actionEditMember()
    {
        $id = ArrayHelper::getValue($this->paramData,'id');
        if(\Yii::$app->request->getIsPost())
        {
            $result = MemberService::getService()->editMember($id);

            if($result instanceof UserModel)
                return $this->returnAjaxSuccess([
                    'message' => '会员编辑成功',
                    'navTabId' => 'member-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['member/member-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            //
            $model = UserModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-member',['model' => $model]);
        }
    }

    public function actionDeleteMember()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        //批量删除
        $return = MemberService::getService()->deleteMember($ids);

        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '会员编辑成功',
                'navTabId' => 'member-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['member/member-list'])
            ]);
        return $this->returnAjaxError(-100);
    }
}

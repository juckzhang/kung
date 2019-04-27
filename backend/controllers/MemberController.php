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
    /*
     * 个人认证列表
     */
    public function actionQualificationList() {
        $_prePage = ArrayHelper::getValue($this->paramData, 'numPerPage');
        $_page = ArrayHelper::getValue($this->paramData, 'pageNum');
        $_order = $this->_sortOrder(QualificationModel::tableName().'.');
        $data = MemberService::getService()->qualificationList($_order, $_page, $_prePage);
        return $this->render('qualification-list', $data);
    }

    /*
     * 个人认证审核编辑
     */
    public function actionEditQualification() {
        $param = $this->paramData;
        $user_id = ArrayHelper::getValue($param, 'user_id');
        if (\Yii::$app->request->getIsPost()) {
            $status = ArrayHelper::getValue($param, 'status');
            if (MemberService::getService()->editQualification($user_id, $status)) {
                return $this->returnAjaxSuccess([
                    'message' => '审核完成',
                    'navTabId' => 'qualification-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['member/qualification-list'])
                ]);
            } else {
                return $this->returnAjaxError(false);
            }
        } else {
            $qual = QualificationModel::find()->where(['user_id'=>$user_id])->asArray()->one();
            return $this->render('edit-qualification', ['model'=>$qual]);
        }
    }

    public function actionProfitList() {
        $_keyWord = ArrayHelper::getValue($this->paramData,'keyword');
        $_other   = ArrayHelper::getValue($this->paramData,'other',[]);
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_order = $this->_sortOrder();
        $data = MemberService::getService()->profitList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('profit-list', $data);
    }

    public function actionParticipatorList() {
        $_keyWord = ArrayHelper::getValue($this->paramData,'keyword');
        $_other   = ArrayHelper::getValue($this->paramData,'other',[]);
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_order = $this->_sortOrder();
        $data = MemberService::getService()->participatorList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('participator-list',$data);
    }

    public function actionUpdateParticipator() {
        if (\Yii::$app->request->getIsPost()) {
            $user_id = ArrayHelper::getValue($this->paramData, 'user_id');
            $state = ArrayHelper::getValue($this->paramData, 'state');
            $result = MemberService::getService()->updateParticipator($user_id, $state);
            if ($result === true) {
                return $this->returnAjaxSuccess([
                    'message' => '成功',
                    'navTabId' => 'participator-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['member/participator-list'])
                ]);
            }
        } else {
            return $this->returnAjaxError(-100);
        }
    }

    public function actionAssignProfit()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $result = MemberService::getService()->assignProfit($this->paramData);
            if($result === true) {
                return $this->returnAjaxSuccess([
                    'message' => '成功',
                    'navTabId' => 'participator-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['member/participator-list'])
                ]);
            } else {
                return $this->returnAjaxError($result);
            }
        } else {
            $id = ArrayHelper::getValue($this->paramData,'user_id');
            $nick_name = ArrayHelper::getValue($this->paramData, 'nick_name');
            $mobile = ArrayHelper::getValue($this->paramData, 'mobile');
            return $this->render('assign-profit',['user_id' => $id, 'nick_name' => $nick_name, 'mobile' => $mobile]);
        }
    }

    public function actionDeleteProfit()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        //批量删除
        $return = MemberService::getService()->deleteProfit($ids);

        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '分成删除成功',
                'navTabId' => 'profit-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['member/profit-list'])
            ]);
        return $this->returnAjaxError(-100);
    }

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

    public function actionWithdrawApplyList() {
        $_keyWord = ArrayHelper::getValue($this->paramData,'keyword');
        $_other   = ArrayHelper::getValue($this->paramData,'other',[]);
        $_prePage = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page    = ArrayHelper::getValue($this->paramData,'pageNum');
        $_order   = $this->_sortOrder();
        $data     = MemberService::getService()->withdrawApplyList($_keyWord, $_other, $_order, $_page, $_prePage);
        return $this->render('withdraw-apply-list', $data);
    }

    public function actionUpdateWithdrawApply() {
        $id = ArrayHelper::getValue($this->paramData, 'id');
        $process = ArrayHelper::getValue($this->paramData, 'process');
        $r = MemberService::getService()->updateWithdrawApply($id, $process);
        if ($r === true) {
            return $this->returnAjaxSuccess([
                'message' => '更新成功',
                'navTabId' => 'withdraw-apply-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['member/withdraw-apply-list'])
            ]);
        } else {
            return $this->returnAjaxError(-100);
        }
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

    /*
     * 编辑用户信息
     * @return string
     */
    public function actionEditUser(){
        $user_id = ArrayHelper::getValue($this->paramData,'user_id');
        if(\Yii::$app->request->getIsPost())
        {
            $result = MemberService::getService()->edituser($user_id);
            if($result) {
                return $this->returnAjaxSuccess([
                    'message' => '成功',
                    'navTabId' => 'participator-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['member/participator-list'])
                ]);
            } else {
                return $this->returnAjaxError($result);
            }
        } else {
            $user = UserModel::find()->where(['id'=>$user_id])->asArray()->one();
            return $this->render('edit-user',['user' => $user]);
        }

    }


    /**
     * 编辑会员信息
     * @return string
     */
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
                    'forwardUrl' => Url::to(['member/member-list','positionId' => $result->id])
                ]);
            return $this->returnAjaxError($result);
        }else{
            //
            $model = UserModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-member',['model' => $model]);
        }
    }

    /**
     * 删除广告
     * @return string
     */
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

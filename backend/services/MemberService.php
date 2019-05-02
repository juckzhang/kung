<?php
namespace backend\services;

use backend\services\base\BackendService;
use common\models\mysql\FeedbackModel;
use common\models\mysql\QualificationModel;
use common\models\mysql\UserModel;
use common\models\mysql\ProfitModel;
use common\models\mysql\WithdrawApplyModel;
use yii\helpers\ArrayHelper;
use Yii;

class MemberService extends BackendService
{
    //用户列表
    public function memberList($keyWord,array $other = [],array $sortOrder,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = UserModel::find()->where(['status' => UserModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','nick_name',$keyWord]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $data['dataList'] = $models->orderBy($sortOrder)->offset($offset)->limit($limit)->all();
        }

        return $data;
    }

    public function editMember($useid){
        return $this->editInfo($useid,UserModel::className());
    }

    public function deleteMember($id)
    {
        $num = $this->deleteInfo($id, UserModel::className());

        if($num > 0) return true;

        return false;
    }

    //用户列表
    public function feedbackList($keyWord,array $other = [],array $sortOrder,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = FeedbackModel::find()->where(['status' => FeedbackModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','content',$keyWord]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $data['dataList'] = $models->orderBy($sortOrder)->offset($offset)->limit($limit)->all();
        }

        return $data;
    }

    public function editFeedback($id){
        return $this->editInfo($id,FeedbackModel::className());
    }

    public function deleteFeedback($id)
    {
        $num = $this->deleteInfo($id, FeedbackModel::className());

        if($num > 0) return true;

        return false;
    }
}


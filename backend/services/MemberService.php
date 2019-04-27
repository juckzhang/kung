<?php
namespace backend\services;

use backend\services\base\BackendService;
use common\models\mysql\QualificationModel;
use common\models\mysql\UserModel;
use common\models\mysql\ProfitModel;
use common\models\mysql\WithdrawApplyModel;
use yii\helpers\ArrayHelper;
use Yii;

class MemberService extends BackendService
{
    /**
     * 获取帖子列表
     * @param $keyWord
     * @param array $other
     * @param array $sortOrder
     * @param array $page
     * @param $prePage
     * @return array
     * @throws \yii\mongodb\Exception
     */
    public function memberList($keyWord,array $other = [],array $sortOrder,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = UserModel::find()->where(['status' => UserModel::STATUS_ACTIVE])
            ->andFilterWhere(['or',
                ['like','mobile',$keyWord],
                ['like','nick_name',$keyWord]
            ]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $data['dataList'] = $models->orderBy($sortOrder)->offset($offset)->limit($limit)->all();
        }

        return $data;
    }

    public function qualificationList($sortOrder, $page, $prePage) {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList'=>[], 'pageCount'=>0, 'dataCount'=>0];
        $quals = QualificationModel::find();
        $data['dataCount'] = $quals->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'], $limit);
        if ($data['pageCount'] > 0 AND $page <= $data['pageCount']) {
            $data['dataList'] = $quals->orderBy($sortOrder)->offset($offset)->limit($limit)->all();
        }

        return $data;
    }

    public function editQualification($user_id, $status) {
        $db = \Yii::$app->db;
        $trans = $db->beginTransaction();
        try {
            $cols = ['status'=>$status];
            $db->createCommand()->update(QualificationModel::tableName(), $cols, "`user_id`=$user_id")->execute();
            if($status == 2){
                $db->createCommand()->update(UserModel::tableName(), ['type'=>$status], "`id`=$user_id")->execute();
            }else{
                $db->createCommand()->update(UserModel::tableName(), ['type'=>1], "`id`=$user_id")->execute();
            }
            $trans->commit();
            return true;

        } catch (\Exception $e) {
            $trans->rollBack();

            throw $e;
            return false;
        }
    }

    public function profitList($keyWord,array $other = [],array $sortOrder,$page,$prePage) {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList'=>[], 'pageCount'=>0, 'dataCount'=>0];
        $profits = ProfitModel::find()->where(['status' => ProfitModel::STATUS_ACTIVE]);
        $data['dataCount'] = $profits->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'], $limit);
        if ($data['pageCount'] > 0 AND $page <= $data['pageCount']) {
            $data['dataList'] = $profits->orderBy($sortOrder)->offset($offset)->limit($limit)->all();
        }

        return $data;
    }

    public function assignProfit($paramData) {
        $userId = ArrayHelper::getValue($paramData, 'user_id');
        $type = ArrayHelper::getValue($paramData, 'type');
        $desc = ArrayHelper::getValue($paramData, 'desc');
        $sum = ArrayHelper::getValue($paramData, 'sum');
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();

        try {
            $time = time();
            $cols = ['user_id'=>$userId, 'type'=>$type, 'desc'=>$desc, 'sum'=>$sum, 'create_time'=>$time, 'update_time'=>$time];
            $db->createCommand()->insert(ProfitModel::tableName(), $cols)
                ->execute();
            $trans->commit();
            return true;

        } catch (\Exception $e) {
            $trans->rollBack();
            return $e;
        }
    }

    public function deleteProfit($id) {
        $num = $this->deleteInfo($id, ProfitModel::className());
        if($num > 0) return true;
        return false;
    }

    public function participatorList($keyWord,array $other = [],array $sortOrder,$page,$prePage) {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $userModels = UserModel::find()->where(['status' => UserModel::STATUS_ACTIVE])
            ->andFilterWhere(['is_participator' => 1])
            ->andFilterWhere(['or',
                ['like','mobile',$keyWord],
                ['like','nick_name',$keyWord]
            ]);
        $data['dataCount'] = $userModels->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        if($data['pageCount'] > 0 AND $page <= $data['pageCount']) {
            $data['dataList'] = $userModels->orderBy($sortOrder)->offset($offset)->limit($limit)->all();
        }

        return $data;
    }

    public function updateParticipator($user_id, $state)
    {
        $value = $state == 1 ? 1 : 0;
        $user = UserModel::findOne($user_id);
        $user->is_participator = $value;
        return $user->save();
    }

    public function withdrawApplyList($keyWord,array $other = [],array $sortOrder,$page,$prePage) {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = WithdrawApplyModel::find()->where(['status' => UserModel::STATUS_ACTIVE]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        if($data['pageCount'] > 0 AND $page <= $data['pageCount']) {
            $data['dataList'] = $models->orderBy($sortOrder)->offset($offset)->limit($limit)->all();
        }

        return $data;
    }

    public function updateWithdrawApply($id, $process) {
        $withdrawApply = WithdrawApplyModel::findOne($id);
        if ($withdrawApply !== null) {
            $withdrawApply->process = $process;
            $r1 = $withdrawApply->save();
            if ($process == 4) {
                $profits = ProfitModel::find()->where(['withdraw_apply_id' => $id]);
                if ($profits->count() == 0) {
                    $profit = new ProfitModel();
                    $profit->type = 2;
                    $profit->desc = '';
                    $profit->sum = -($withdrawApply->sum);
                    $profit->user_id = $withdrawApply->user_id;
                    $profit->status = ProfitModel::STATUS_ACTIVE;
                    $profit->withdraw_apply_id = $withdrawApply->id;
                    return $profit->save();
                } else {
                    return $r1;
                }
            } else {
                return $r1;
            }
        }  else {
            return false;
        }
    }

    /**
     * 编辑广告
     * @param $id
     * @return bool
     */
    public function editAd($id)
    {
        return $this->editInfo($id,AdModel::className());
    }

    /*
     * 编辑用户信息
     */
    public function edituser($useid){
        return $this->editInfo($useid,UserModel::className());
    }

    /*
     * 编辑用户信息
     */
    public function editMember($useid){
        return $this->editInfo($useid,UserModel::className());
    }

    /**
     * 删除广告
     * @param $id
     * @return bool
     */
    public function deleteMember($id)
    {
        $num = $this->deleteInfo($id, UserModel::className());

        if($num > 0) return true;

        return false;
    }

}


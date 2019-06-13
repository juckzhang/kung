<?php

namespace frontend\services;

use common\models\mysql\FeedbackModel;
use common\models\mysql\UserModel;
use frontend\services\base\FrontendService;
use common\constants\CodeConstant;
use yii\helpers\ArrayHelper;

class UserService extends FrontendService{

    public function login(array $params){
        $data = [
            'third_account' => ArrayHelper::getValue($this->paramData, 'third_account'),
            'account_type' => ArrayHelper::getValue($this-$this->paramData, 'account_type'),
            'nick_name' => ArrayHelper::getValue($this->paramData, 'nick_name'),
            'icon_url' => ArrayHelper::getValue($this->paramData,'icon_url', \Yii::$app->params['domain'].'upload/default_icon_url.jpg'),

        ];
        $userId      = ArrayHelper::getValue($this->paramData, 'user_id');
        if(!$data['third_account'] or ! in_array($data['account_type'], ['1', '2', '3'])){
            return CodeConstant::PARAM_ERROR;
        }
        //判断账号是否存在
        $where = ['third_account' => $data['third_account'], 'account_type' => $data['account_type']];
        if($userId){
            $where = ['id' => $userId];
        }
        $model = UserModel::find()
            ->select(['id','nick_name','icon_url','third_account','account_type'])
            ->where($where)
            ->one();

        if(!($model instanceof UserModel)){
            $model = new UserModel();
        }
        $params['access_token'] = md5($params['third_account'] .'-'.$params['account_type'].'-'.time().'-'.mt_rand());
        if($model->load(['UserModel' => $data]) and $model->save()){
            $model->id = (string)$model->id;
            return $model->toArray();
        }

        return CodeConstant::USER_LOGIN_FAILED;
    }

    // 用户反馈
    public function feedback($params)
    {
        if(empty($params['user_id'])){
            return CodeConstant::USER_TOKEN_NOT_EXISTS;
        }
        $model = new FeedbackModel();
        if($model->load($params,'') and $model->save()){
            $model->id = (string)$model->id;
            return $model->toArray();
        }

        return CodeConstant::FEED_BACK_FAILED;
    }

    // 我的反馈列表
    public function feedbackList($userId, $page, $count)
    {
        if(empty($userId)){
            return CodeConstant::USER_TOKEN_NOT_EXISTS;
        }
        list($offset,$limit) = $this->parsePageParam($page, $count);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = FeedbackModel::find()
            ->where([
                'user_id' => $userId,
                'status' => FeedbackModel::STATUS_ACTIVE,
                'parent_id' => 0,
            ]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $models = $models->orderBy(['create_time' => SORT_DESC])->asArray()
                ->with('feedback')
                ->offset($offset)->limit($limit)->all();
            $data['dataList'] = $models;
        }

        return $data;
    }
}
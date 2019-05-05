<?php

namespace frontend\services;

use common\models\mysql\FeedbackModel;
use common\models\mysql\UserModel;
use frontend\services\base\FrontendService;
use common\constants\CodeConstant;

class UserService extends FrontendService{

    public function login(array $params){
        //判断账号是否存在
        $model = UserModel::find(['id','nick_name','icon_url'])
            ->where(['third_account' => $params['third_account'], 'account_type' => $params['account_type']])
            ->one();

        if(!($model instanceof UserModel)){
            $model = new UserModel();
        }
        $params['access_token'] = md5('token-'.time().'-'.mt_rand(1000,9999));
        if($model->load($params, '') and $model->save()){
            $model->id = (string)$model->id;
            return $model->toArray();
        }

        return CodeConstant::USER_LOGIN_FAILED;
    }

    // 用户反馈
    public function feedback($params)
    {
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
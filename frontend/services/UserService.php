<?php

namespace frontend\services;


use common\models\mysql\FeedbackModel;
use common\models\mysql\UserModel;
use frontend\services\base\FrontendService;
use common\constants\CodeConstant;

/**
 * Class UserService
 * @package backend\services\users
 * @author zhangchao
 * @since	Version 1.0.0
 */
class UserService extends FrontendService{

    /**
     * 登陆
     * @param array $params
     * @return array|UserModel|int|null|\yii\db\ActiveRecord
     */
    public function login(array $params){
        //判断账号是否存在
        $model = UserModel::find(['id','nick_name','icon_url'])
            ->where(['third_account' => $params['third_account'], 'account_type' => $params['account_type']])
            ->asArray()
            ->one();

        if(!empty($model)){//账号存在，返回uid
            return $model;
        }

        //新建账号
        $model = new UserModel();
        if($model->load($params, '') and $model->save()){
            return $model->toArray();
        }

        return CodeConstant::USER_LOGIN_FAILED;
    }

    /**
     * 添加feedback
     * @param $params
     * @return int
     */
    public function feedback($params)
    {
        $model = new FeedbackModel();
        if($model->add($params,'')){
            return CodeConstant::SUCCESS;
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
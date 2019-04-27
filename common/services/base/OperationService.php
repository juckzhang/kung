<?php
namespace common\services\base;

use common\constants\CodeConstant;
use common\models\mysql\UserModel;

class OperationService extends Service
{
    const POSITIVE_OPERATION = 1;
    const MINUS_OPERATION = 0;

    protected $collectModel;//收藏model
    protected $scanModel; //浏览model
    protected $downloadModel; //订阅model
    protected $sourceModel;//操作的资源model

    const IS_OPERATED = 1;
    const NOT_OPERATED = 0;

    /**
     * 收藏（取消收藏）资源
     * @param $id
     * @param $userId
     * @return array|int
     */
    protected function collect($id,$userId)
    {
        return $this->operateTwoWay($id,$userId,$this->collectModel);
    }

    /**
     * 查看是否已经有记录
     * @param $id
     * @param $userId
     * @return mixed
     */
    protected function isCollected($id,$userId)
    {
        return $this->checkOperated($id,$userId,$this->collectModel);
    }

    /**
     * 查看是否已经有记录
     * @param $id
     * @param $userId
     * @return mixed
     */
    protected function isDownload($id,$userId)
    {
        return $this->checkOperated($id,$userId,$this->downloadModel);
    }
    /**
     * 查看是否已经有记录
     * @param $videoid
     * @param $userId
     * @return mixed
     */
    protected function isScanned($videoid,$userId)
    {
        return $this->checkOperated($videoid,$userId,$this->scanModel);
    }

    /**
     * 查询是否已经有记录
     * @param $id
     * @param $userId
     * @param $opModel
     * @return int
     */
    private function checkOperated($id,$userId,$opModel)
    {
        $model = $opModel::find()
            ->where(['source_id' => $id,'user_id' => $userId,'status' => $opModel::STATUS_ACTIVE])
            ->exists();

        if($model) return static::IS_OPERATED;

        return static::NOT_OPERATED;
    }

    /**
     * 查看数量
     * @param $id
     * @return mixed
     */
    protected function subscribeNum($id)
    {
        return $this->userNum($id,$this->downloadModel);
    }

    /**
     * 查看数量
     * @param $id
     * @param $opModel
     * @return mixed
     */
    private function userNum($id,$opModel)
    {
        $model = $opModel::find()->where(['source_id' => $id,'status' => $opModel::STATUS_ACTIVE]);
        return $model->count();
    }
    /**
     * 下载
     * @param $id
     * @param $userId
     * @return array|int
     */
    protected function download($id,$userId)
    {
        return $this->operateOneWay($id,$userId,$this->downloadModel);
    }

    public function scanned($id,$userId)
    {
        return $this->operateOneWay($id,$userId,$this->scanModel);
    }

    /**
     * 单向操作
     * @param $id
     * @param $userId
     * @param $opModel
     * @return bool|int
     */
    protected function operateOneWay($id,$userId,$opModel)
    {
        $check = $this->check($id,$userId);
        if($check !== true) return $check;

        //判断是否已经浏览过
        $model = $opModel::findOne(['source_id' => $id,'user_id' => $userId,'status' => $opModel::STATUS_ACTIVE]);
        if($model) return true;
        $model = new $opModel();
        if($model->add(['user_id' => $userId,'source_id' => $id])) return true;
        return false;
    }
    /**
     * 操作与取消操作
     * @param $id
     * @param $userId
     * @param $opModel
     * @return array|int
     */
    protected function operateTwoWay($id,$userId,$opModel)
    {
        $check = $this->check($id,$userId);
        if($check !== true) return $check;
        $opType = static::POSITIVE_OPERATION;
        $status = true;
        //判断是否已经收藏过
        $model = $opModel::findOne(['user_id' => $userId,'source_id' => $id,'status' => $opModel::STATUS_ACTIVE]);
        if($model)
        {
            $opType = static::MINUS_OPERATION;
            $model->status = $opModel::STATUS_DELETED;
            if( ! $model->update()) $status = false;
        }else{
            $model = new $opModel();
            if(! $model->add(['source_id' => $id,'user_id' => $userId])) $status = false;
        }

        return ['operation' => $opType,'status' => $status,'operationId' => $model->id];
    }

    /**
     * 资源检查
     * @param $sourceId
     * @param $userId
     * @return bool|int
     */
    protected function check($sourceId,$userId)
    {
        $sourceModel = $this->sourceModel;
        //检查资源是否存在
        if(!$this->checkSource($sourceId,$sourceModel::className()))
            return CodeConstant::PARAM_ERROR;

        if(!$this->checkSource($userId,UserModel::className()))
            return CodeConstant::USER_TOKEN_NOT_EXISTS;

        return true;
    }

    //收藏列表
    public function collectionList($uid, $page, $count, $order = null)
    {
        return $this->sourceList($this->collectModel,$uid, $page, $count, $order);
    }

    //浏览列表
    public function lookList($uid, $page, $count, $order = null)
    {
        return $this->sourceList($this->scanModel,$uid, $page, $count, $order);
    }

    //下载列表
    public function downloadList($uid, $page, $count, $order = null)
    {
        return $this->sourceList($this->downloadModel,$uid, $page, $count, $order);
    }

    // 资源列表
    protected function sourceList($model,$uid, $page, $count, $order)
    {
        list($offset,$limit) = $this->parsePageParam($page, $count);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = $model::find()
            ->where(['user_id' => $uid, 'status' => $model::STATUS_ACTIVE]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            if(is_array($order)) $order = ['create_time' => SORT_DESC];
            $models = $models->orderBy($order)->asArray()
                ->with('video')
                ->offset($offset)->limit($limit)->all();
            $data['dataList'] = $models;
        }

        return $data;
    }
}
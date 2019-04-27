<?php
namespace backend\services;

use common\models\mysql\ServiceModel;
use common\models\mysql\ServiceOrderModel;
use common\models\mysql\UserModel;
use common\models\mysql\AdminModel;
use Yii;
use backend\services\base\BackendService;
use common\models\mysql\CategoryModel;
use yii\helpers\ArrayHelper;

class SpaceService extends BackendService
{
    /**
     * 获取场景列表
     * @param $keyWord
     * @param $other
     * @param array $order
     * @param $page
     * @param $prePage
     * @return array
     */
    public function serviceList($keyWord,$other,array $order = [],$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        //$cateId = ArrayHelper::getValue($other,'cateId');

        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = ServiceModel::find()->where(['status' => ServiceModel::STATUS_ACTIVE]);


        //超级管理员
        //\Yii::$app->user->Identity->id
//        echo "<pre>";
//        var_dump(\Yii::$app->user->identity);exit;
        $admin_id = \Yii::$app->user->identity->id;
        if(\Yii::$app->user->identity->role_id > 0){
            $models->andFilterWhere(['admin_id' => $admin_id]);
        }
        $models->andFilterWhere(['like','title',$keyWord]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    /**
     * 编辑场景
     * @param $id
     * @return bool
     */
    public function editService($id)
    {
        return $this->editInfo($id,ServiceModel::className());
    }

    /*
     * 编辑订单
     * @param $id
     * @return bool
     */
    public function editOrder($id)
    {
        return $this->editInfo($id,ServiceOrderModel::className());
    }

    /**
     * 删除场景
     * @param $ids
     * @return bool
     */
    public function deleteService($ids)
    {
        $num = $this->deleteInfo($ids,ServiceModel::className());
        if( $num > 0 ) return true;

        return false;
    }

    /*
     * 删除订单
     * @param $ids
     * @return bool
     */

    public function deleteOrder($ids)
    {
        $num = $this->deleteInfo($ids,ServiceOrderModel::className());
        if( $num > 0 ) return true;

        return false;
    }


    /*
     * 获取订单列表
     * @param $keyWord
     * @param $other
     * @param array $order
     * @param $page
     * @param $prePage
     * @return array
     */
    public function serviceOrder($keyWord,$other,array $order = [],$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);

        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];
        $admin_id = \Yii::$app->user->identity->id;
        $service_id = array();
        $datas = ServiceModel::find()->select('id')->where(['admin_id'=>$admin_id])->asArray()->all();

        foreach ($datas as $key => $val){
            $service_id[] = $val['id'];
        }
        $models = ServiceOrderModel::find();
        if(\Yii::$app->user->identity->role_id){
            $models->where(['in','service_id',$service_id]);
        }
        $models->andFilterWhere(['!=','status',ServiceOrderModel::STATUS_DELETED]);

        $models->andFilterWhere(['like','order_no',$keyWord]);


        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->asArray()->all();

        foreach ($data['dataList'] as $key => $value){
            $user =  UserModel::find()->select('nick_name,mobile')->where(['id'=>$value['user_id']])->asArray()->one();
            $data['dataList'][$key]['nick_name'] = $user['nick_name'];
            $data['dataList'][$key]['mobile'] = $user['mobile'];

            $service = ServiceModel::find()->where(['id'=>$value['service_id']])->asArray()->one();
            $admin = AdminModel::find()->where(['id'=>$service['admin_id']])->asArray()->one();

            $data['dataList'][$key]['title'] = $service['title'];
            $data['dataList'][$key]['admin'] = $admin['username'];
        }

        return $data;
    }

    /*
     * 查找锁定的时间
     */
    public function serviceDisableTime()
    {
        $data = ServiceOrderModel::find()->select("*")
            ->where(['in','status',[0,1,3]])
            ->asArray()->all();
        $disable = array();
        if($data && count($data)>0){
            foreach ($data as $key => $value){
                for ($i = $value['start_time'];$i<=$value['end_time'];$i+=3600*24){
                    $disable[] = date("Y-m-d",$i);
                }
            }
        }
        return $disable;
    }
}
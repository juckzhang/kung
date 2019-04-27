<?php
namespace backend\services;

use backend\services\base\BackendService;
use common\helpers\TreeHelper;
use common\models\mysql\MenuModel;

class MenuService extends BackendService
{
    /**
     * 获取帖子列表
     * @param $positionId
     * @param array $sortOrder
     * @param array $page
     * @param $prePage
     * @return array
     * @throws \yii\mongodb\Exception
     */
    public function menuList($positionId,array $sortOrder,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $positionId = $positionId ? [$positionId,MenuModel::POSITION_ALL] : null;
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $adModels = MenuModel::find()->where(['status' => MenuModel::STATUS_ACTIVE])
            ->andFilterWhere(['position_id' => $positionId]);

        $data['dataCount'] = $adModels->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $data['dataList'] = $adModels->orderBy($sortOrder)->offset($offset)->limit($limit)->all();
        }

        return $data;
    }

    /**
     * 编辑广告
     * @param $id
     * @return bool
     */
    public function editMenu($id)
    {
        return $this->editInfo($id,MenuModel::className());
    }

    /**
     * 删除广告
     * @param $id
     * @return bool
     */
    public function deleteMenu($id)
    {
        $num = $this->deleteInfo($id,MenuModel::className());

        if($num > 0) return true;

        return false;
    }

    /**
     * 获取菜单列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public function menus()
    {
        $models = MenuModel::find()->where(['status' => MenuModel::STATUS_ACTIVE,'parent_id' => 0])
            ->select(['id','parent_id','name'])
            ->orderBy(['parent_id' => SORT_ASC])->asArray()->all();

        return $models;
    }

}


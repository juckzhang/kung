<?php
namespace backend\services;

use backend\services\base\BackendService;
use common\models\mysql\AdModel;
use common\models\mysql\AdMobileScreenModel;

class AdService extends BackendService
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
    public function adList($positionId,array $sortOrder,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);

        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $adModels = AdModel::find()->where(['status' => AdModel::STATUS_ACTIVE])
            ->andFilterWhere(['position_id' => $positionId]);

        $data['dataCount'] = $adModels->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $data['dataList'] = $adModels->orderBy($sortOrder)->offset($offset)->limit($limit)->all();
        }

        return $data;
    }

    public function mobileScreenList($positionId,array $sortOrder,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);

        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $adModels = AdMobileScreenModel::find()->where(['status' => AdMobileScreenModel::STATUS_ACTIVE]);

        $data['dataCount'] = $adModels->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $data['dataList'] = $adModels->orderBy($sortOrder)->offset($offset)->limit($limit)->all();
        }

        return $data;
    }

    public function editMobileScreen($id)
    {
        return $this->editInfo($id, AdMobileScreenModel::className());
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

    /**
     * 删除广告
     * @param $id
     * @return bool
     */
    public function deleteAd($id)
    {
        return $this->deleteInfo($id,AdModel::className());
    }

}


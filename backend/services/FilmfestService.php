<?php
/**
 * Created by PhpStorm.
 * User: dongbin
 * Date: 13/07/2017
 * Time: 4:50 PM
 */

namespace backend\services;

use backend\services\base\BackendService;
use common\models\mysql\FilmfestSignupModel;
use common\models\mysql\FilmfestModel;
use common\models\mysql\UserModel;

class FilmfestService extends BackendService
{
    public function signupList()
    {
        $items = FilmfestSignupModel::find()->all();
        foreach ($items as $item) {
            $item->filmfest = FilmfestModel::find()->where(['id' => $item->filmfest_id])->one();
            $item->user = UserModel::find()->where(['id' => $item->user_id])->one();
        }
        return $items;
    }

    public function signupInfo($id)
    {
        $data = FilmfestSignupModel::find()->where(['id' => $id])->one();
        return $data;
    }

    public function filmfestList($keyWord, array $order = [], $page, $prePage,$country='',$month='',$status='')
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];
        $models = FilmfestModel::find()
            ->where(['status' => FilmfestModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','title',$keyWord])
            ->andFilterWhere(['like','country',$country])
            ->andFilterWhere(['like','month',$month]);

        $data['page'] = $page;
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']) {
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();
        }
        
        if(count($data['dataList']) > 0){
            foreach ($data['dataList'] as $key => $value){
                $data['dataList'][$key]['start_time'] = date('Y-m-d',$value['start_time']);
                $data['dataList'][$key]['end_time'] = date('Y-m-d',$value['end_time']);
            }
        }

        return $data;
    }

    public function editFilmfest($id)
    {
        return $this->editInfo($id, FilmfestModel::className());
    }

    public function filmfestDetail($id)
    {
        $model = FilmfestModel::find()->select(['id','title','sub_title','author','source','create_time','content','banner_url','price'])
            ->where(['id' => $id,'status' => FilmfestModel::STATUS_ACTIVE])->one();

        if($model == null)
            throw new InvalidArgumentException('电影节不存在！');

        return $model;
    }
}
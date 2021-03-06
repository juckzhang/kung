<?php
namespace common\services;

use common\constants\CodeConstant;
use common\events\MediaEvent;
use common\helpers\CommonHelper;
use common\models\mysql\MediaCategoryModel;
use common\models\mysql\MediaCommentModel;
use common\models\mysql\MediaLinesModel;
use common\models\mysql\MediaModel;
use common\services\base\OperationService;
use yii\log\EmailTarget;

class MediaService extends OperationService
{
    const AFTER_SCANNED_MEDIA  = 'after_scanned_media';
    const AFTER_COLLECT_MEDIA  = 'after_collect_media';
    const AFTER_CANCEL_COLLECT_MEDIA = 'after_cancel_collect_media';
    const AFTER_DOWNLOAD_MEDIA = 'after_download_media';

    protected $collectModel = 'common\models\mysql\MediaCollectionModel';//收藏model
    protected $scanModel = 'common\models\mysql\MediaLookModel'; //浏览model
    protected $downloadModel = 'common\models\mysql\MediaDownloadModel'; //下载model
    protected $sourceModel = 'common\models\mysql\MediaModel';//操作的资源model

    public function behaviors()
    {
        return [
            'mediaBehavior' => [
                'class' => 'common\behaviors\MediaBehavior',
            ],
        ];
    }

    //分类接口
    public function categoryList($sourceType, $lang, $page, $count)
    {
        list($offset,$limit) = $this->parsePageParam($page,$count);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $lang = $this->changeLang($lang);
        $column = ['id','source_type'];
        $column['name'] = $lang == 'zh_CN' ? 'name' : 'name_'.$lang;
        $models = MediaCategoryModel::find()
            ->select($column)
            ->where(['status' => MediaCategoryModel::STATUS_ACTIVE])
            ->andFilterWhere(['source_type' => $sourceType]);
        $data['dataCount'] = (int)$models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $models = $models->orderBy(['sort_order' => SORT_DESC,'create_time' => SORT_ASC])
                ->asArray()->all();
            array_unshift($models, ['id' => '', 'source_type' => $sourceType, 'name' => CommonHelper::t('app', 'all')]);
            $data['dataList'] = $models;
        }

        return $data;
    }

    // 完整播放次数
    public function completeView($videoId, $userId){
        //检查资源是否存在
        $check = $this->check($videoId, $userId);

        if($check !== true){
            return $check;
        }

        MediaModel::updateAllCounters(['complete_play_num' => 1], ['id' => $videoId]);
        return true;
    }

    //视频/音频列表
    public function mediaList($cateId,$lang,$page,$prePage,$sourceType = null, $order = 'create_time')
    {
        $order != 'play_num' && $order = 'create_time';
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $column = ['id','cate_id','source_type','level','poster_url','download_link','play_link','play_num','collection_num','download_num'];
        $formatLang = $this->changeLang($lang);
        $column['title']  = $formatLang != 'zh_CN' ? 'title_'.$formatLang : 'title';
        $models = MediaModel::find()
            ->select($column)
            ->where(['and',
                ['status' => MediaModel::STATUS_ACTIVE],
                ['or',
                    ['like','lang_type',$lang],
                    ['lang_type' => 'zh_CN'],
                ],
            ])
            ->andFilterWhere(['source_type' => $sourceType])
            ->andFilterWhere(['cate_id' => $cateId]);
        $data['dataCount'] = (int)$models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $models = $models->orderBy([$order => SORT_DESC])
                ->asArray()
                ->with('category')
                ->offset($offset)->limit($limit)->all();
            foreach ($models as $key => $model){
                $model['level_name'] = CommonHelper::t('app', 'level-'.$model['level']);
                $model['category']['name'] = $model['category']['name_'.$formatLang] ?: $model['category']['name'];
                $model['poster_url'] = $this->formatLink($model['poster_url']);
                if($model['source_type'] != 1){
                    $model['play_link'] = $this->formatLink($model['play_link']);
                    $model['download_link'] = $this->formatLink($model['download_link']);
                }
                $models[$key] = $model;
            }
            $data['dataList'] = $models;
        }

        return $data;
    }

    //推荐列表
    public function recommendList($lang, $userLevel)
    {
        $userLevel = $userLevel ?: 3;
        $data = [];
        $column = ['id','cate_id','source_type','level','poster_url','play_link','download_link','play_num','collection_num','download_num'];
        $formatLang = $this->changeLang($lang);
        $column['title']  = $formatLang != 'zh_CN' ? 'title_'.$formatLang : 'title';
        foreach (['videoList', 'autoList','pdfList'] as $key => $item){
            $models = MediaModel::find()
                ->select($column)
                ->where(['and',
                    ['source_type' => $key + 1],
                    ['or',
                        ['like','lang_type',$lang],
                        ['lang_type' => 'zh_CN'],
                    ],
                    ['status' => MediaModel::STATUS_ACTIVE],
                    ['<=', 'level', $userLevel]
                ])
                ->orderBy([
                    'level' => SORT_DESC,
                    'is_recommend' => SORT_DESC,
                    'complete_play_num' => SORT_DESC,
                    'collection_num' => SORT_DESC,
                    'real_play_num' => SORT_DESC,
                    'create_time' => SORT_DESC,
                ])
                ->with('category')
                ->asArray()
                ->limit(4)
                ->all();
            foreach ($models as $key => $model){
                $model['level_name'] = CommonHelper::t('app', 'level-'.$model['level']);
                $model['category']['name'] = $model['category']['name_'.$formatLang] ?: $model['category']['name'];
                $model['poster_url'] = $this->formatLink($model['poster_url']);
                if($model['source_type'] != 1){
                    $model['play_link'] = $this->formatLink($model['play_link']);
                    $model['download_link'] = $this->formatLink($model['download_link']);
                }
                $models[$key] = $model;
            }
            $data[$item] = $models;
        }

        return $data;
    }

    // 台词列表
    public function mediaLines($id, $lang, $page, $count){
        $count = $count ? $count * 2 : static::DEFAULT_PRE_PAGE * 2;
        list($offset,$limit) = $this->parsePageParam($page, $count);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = MediaLinesModel::find()
            ->select([
                'id','line_number','lang_type','source_id',
                'start_time','end_time','content',
            ])
            ->where([
                'source_id' => $id,
                'lang_type' => ['zh_CN', $lang],
                'status' => MediaLinesModel::STATUS_ACTIVE,
            ]);
        $data['dataCount'] = (int)$models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        $models = $models->orderBy(['line_number' => SORT_ASC, 'lang_type' => SORT_ASC])
            ->offset($offset)
            ->limit($limit)
            ->asArray()
            ->all();
        $lines = [];
        foreach($models as $line){
            $lineNumber = $line['line_number'];
            if(! empty($lines[$lineNumber])){
                $lines[$lineNumber]['content_'.$line['lang_type']] = $line['content'];
            }else{
                $lines[$lineNumber] = [
                    'start_time' => $line['start_time'],
                    'end_time' => $line['end_time'],
                    'line' => $line['line_number'],
                    'content_'.$line['lang_type'] => $line['content']
                ];
            }
        }
        $data['dataList'] = array_values($lines);
        return $data;
    }

    //视频音频资源详情
    public function mediaDetails($id, $lang, $uid){
        $column = [
            'id','cate_id','source_type',
            'total_time','level','poster_url',
            'play_link','download_link','play_num','collection_num',
            'download_num','create_time','media_desc'
        ];
        $lang = $this->changeLang($lang);
        $column['title'] = $lang == 'zh_CN' ? 'title' : 'title_'.$lang;
        $data = MediaModel::find()
            ->select($column)
            ->where(['id' => $id])
            ->andWhere(['status'=>MediaModel::STATUS_ACTIVE])
            ->asArray()
            ->one();
        //判断用户是否已下载和已收藏
        $data['has_collected'] = false;
        $data['has_download'] = false;
        $data['level_name'] = CommonHelper::t('app', 'level-'.$data['level']);
        if($uid){
            $data['has_collected'] = (bool)$this->isCollected($data['id'], $uid);
            $data['has_download'] = (bool)$this->isDownload($data['id'], $uid);
            $this->onAfterScannedMedia($id, $uid);
        }
        $data['poster_url'] = $this->formatLink($data['poster_url']);
        if($data['source_type'] != 1){
            $data['play_link'] = $this->formatLink($data['play_link']);
            $data['download_link'] = $this->formatLink($data['download_link']);
        }
        return $data;
    }

    //评论列表
    public function commentList($media,$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = MediaCommentModel::find()
            ->select(['id','user_id','content','create_time'])
            ->where(['source_id' => $media,'status' => MediaCommentModel::STATUS_ACTIVE]);
        $data['dataCount'] = (int)$models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page < $data['pageCount'])
        {
            $models = $models->with('user')->orderBy(['create_time' => SORT_DESC])
                ->offset($offset)->asArray()->limit($limit)->all();
            $data['dataList'] = $models;
        }

        return $data;
    }

    //评论
    public function mediaComment($media,$content,$userId)
    {
        $check = $this->check($media, $userId);
        if($check !== true){
            return $check;
        }
        $model = new MediaCommentModel();
        $params = ['user_id' => $userId, 'source_id' => $media, 'content' => $content];
        if($model->load($params, '') and $model->save()){
            $model->id = (string)$model->id;
            return $model->toArray();
        }
        return CodeConstant::MEDIA_COMMENT_FAILED;
    }

    //收藏与取消收藏
    public function collectMedia($mediaId,$userId)
    {
        $return = $this->collect($mediaId,$userId);
        if(is_numeric($return)) return $return;
        //判断是收藏还是取消收藏
        if($return['operation'] == static::POSITIVE_OPERATION)
        {
            if($return['status'] == true){
                $this->onAfterCollect($return['operationId'],$mediaId,$userId);
                return true;
            }
            return CodeConstant::COLLECT_MEDIA_FAILED;
        }

        if($return['status'] == true){
            $this->onAfterCancelCollect($return['operationId'],$mediaId,$userId);
            return true;
        }
        return CodeConstant::CANCEL_COLLECT_MEDIA_FAILED;
    }

    //下载
    public function downloadMedia($mediaId,$userId)
    {
        $return = $this->download($mediaId,$userId);
        if($return != true) {
            $this->onAfterDownload($mediaId, $userId);
            return CodeConstant::DOWNLOAD_MEDIA_FAILED;
        }

        return true;
    }

    private function onAfterDownload($mediaId,$userId)
    {
        $event = new MediaEvent(['mediaId' => $mediaId,'userId' => $userId]);
        $this->trigger(static::AFTER_DOWNLOAD_MEDIA,$event);
    }

    private function onAfterCollect($operationId,$mediaId,$userId)
    {
        $event = new MediaEvent(['operationId' => $operationId,'mediaId' => $mediaId,'userId' => $userId]);
        $this->trigger(static::AFTER_COLLECT_MEDIA,$event);
    }

    private function onAfterCancelCollect($operationId,$mediaId,$userId)
    {
        $event = new MediaEvent(['operationId' => $operationId,'mediaId' => $mediaId,'userId' => $userId]);
        $this->trigger(static::AFTER_CANCEL_COLLECT_MEDIA,$event);
    }

    private function onAfterScannedMedia($mediaId,$userId)
    {
        $event = new MediaEvent(['mediaId' => $mediaId,'userId' => $userId]);
        $this->trigger(static::AFTER_SCANNED_MEDIA,$event);
    }
}
<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\VideoService;
use common\helpers\UrlHelper;

$videoService = VideoService::getService();
$params = \Yii::$app->request->getPost();
$page = ArrayHelper::getValue($params, 'pageNum', '1');
$orderFiled = ArrayHelper::getValue($params, 'orderFiled', '');
$orderDirection = ArrayHelper::getValue($params, 'orderDirection', 'desc');
$prePage = ArrayHelper::getValue($params, 'numPerPage', '20');

//查询条件
$other = ArrayHelper::getValue($params, 'other');
$category = ArrayHelper::getValue($other, 'category');
$album = ArrayHelper::getValue($other, 'album');
$categories = $videoService->categories();

?>
<div class="" id="video-list" rel="video-list">
    <form id="pagerForm" method="post" action="#rel#">
        <input type="hidden" name="pageNum" value="<?= $page ?>"/>
        <input type="hidden" name="numPerPage" value="<?= $prePage ?>"/>
        <input type="hidden" name="orderField" value="<?= $orderFiled ?>"/>
        <input type="hidden" name="orderDirection" value="<?= $orderDirection ?>"/>
    </form>
    <div class="pageHeader">
        <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?= Url::to(['video/video-list']) ?>"
              method="post">
            <div class="searchBar">
                <table class="searchContent">
                    <tbody>
                    <tr>
                        <td width="200">
                            搜索视频并添加到 App 下载：
                            <input type="hidden" name="other[album]" data-name="album.id" value="<?= $album ?>">
                            <input class="required textInput valid lookup" value="<?= $album ?>" data-name="album.name"
                                   name="album" postfield="keyword" suggestfields="name" lookupgroup="album"
                                   autocomplete="off" type="text">
                        </td>
                        <td><a class="btnLook" href="<?= Url::to(['video/album-list', 'search' => 1]) ?>"
                               lookupgroup="album">查找带回</a></td>

                    </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="pageContent">
        <table class="table" width="1200" layoutH="138">
            <thead>
            <tr>
                <?php if (YII_ENV === 'development'): ?><th width="70">专辑ID</th><?php endif; ?>
                <th width="200">视频名称</th>
                <th width="80">长海报图片</th>
                <th width="80">宽海报图片</th>
                <th width="60">导演</th>
                <th width="80">主演</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="update_time" width="100">建档日期
                </th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dataList as $key => $data): ?>
                <tr target="album-id" rel="<?= $data['id'] ?>">
                    <?php if (YII_ENV === 'development'): ?><td><?= $data['id'] ?></td><?php endif; ?>
                    <td><a href="<?=UrlHelper::make('channel/video-play.html', ['id'=>$data['id']])?>" target="_blank"><?=$data['name'] ?></a></td>
                    <td><img src="<?= \Yii::$app->params['imageUrlPrefix'].$data['poster_url'] ?>" width="100%"/></td>
                    <td><img src="<?= \Yii::$app->params['imageUrlPrefix'].$data['wide_poster'] ?>" width="100%"/></td>
                    <td><?= $data['director']?></td>
                    <td><?= $data['actor'] ?></td>
                    <td><?= date('Y-m-d', $data['create_time']) ?></td>
                    <td>
                        <?php if (\Yii::$app->user->can('video/delete-app-download')): ?>
                            <a title="删除" target="ajaxTodo"
                               href="<?= Url::to(['video/delete-app-download', 'id' => $data['id']]) ?>" class="btnDel">删除</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="panelBar">
            <div class="pages">
                <span>显示</span>
                <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                    <option value="20" <?= $prePage == 20 ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= $prePage == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= $prePage == 100 ? 'selected' : '' ?>>100</option>
                    <option value="200" <?= $prePage == 200 ? 'selected' : '' ?>>200</option>
                </select>
                <span>条，共<?= $dataCount ?>条</span>
            </div>
            <div class="pagination" rel='video-list' targetType="navTab" totalCount="<?= $dataCount ?>"
                 numPerPage="<?= $prePage ?>" pageNumShown="10" currentPage="<?= $page ?>"></div>
        </div>
    </div>
</div>

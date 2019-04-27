<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\VideoService;

$videoService = VideoService::getService();
$params = \Yii::$app->request->getPost();
$page = ArrayHelper::getValue($params, 'pageNum', '1');
$orderFiled = ArrayHelper::getValue($params, 'orderFiled', '');
$orderDirection = ArrayHelper::getValue($params, 'orderDirection', 'desc');
$prePage = ArrayHelper::getValue($params, 'numPerPage', '20');

//查询条件
$other = ArrayHelper::getValue($params, 'other');
$category = ArrayHelper::getValue($other, 'category');
$sourceType = ArrayHelper::getValue($other, 'source_type');


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
                        <td>分类筛选：
                            <select name="category">
                                <option value="" <?=$categorys?'':'selected'?>>-- 请选择分类 --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?=($category['id']==$categorys)? "selected":''?>><?= $category['name'] ?></option>
                                    <?php foreach ($category['child'] as $child): ?>
                                        <option value="<?= $child['id'] ?>" <?=($child['id']==$categorys)? "selected":''?>>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<?= $child['name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>

<!--                        <td>推荐筛选-->
<!--                            <select name="recommend">-->
<!--                                <option --?//= $recommends===false ?'':'selected'?>-- 全部 --</option>-->
<!--                                <option value="1" ?//= $recommends ? 'selected':''?>--推荐的</option>-->
<!--                                <option value="0" ?//= $recommends ? '':'selected'?>--不推荐的</option>-->
<!--                            </select>-->
<!--                        </td>-->
                        <td>关键词：<input name="keyword" class="textInput" type="text" alt=""
                                       value="<?= ArrayHelper::getValue($params, 'keyword') ?>"></td>
                        <td>
                            <a class="btnLook" href="<?= Url::to(['video/category-list', 'search' => 1, 'source_type' => $sourceType]) ?>"
                               lookupgroup="category">查找带回
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="subBar">
                    <ul>
                        <li>
                            <div class="buttonActive">
                                <div class="buttonContent">
                                    <button type="submit">检索</button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
    <div class="pageContent">
        <div class="panelBar">
            <ul class="toolBar">
                <?php if (\Yii::$app->user->can('video/edit-video')): ?>
                    <li><a class="add" href="<?= Url::to(['video/edit-video']) ?>" target="navTab"><span>添加</span></a>
                    </li>
                <?php endif; ?>

                <?php if (\Yii::$app->user->can('video/delete-video')): ?>
                    <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['video/delete-video']) ?>" class="delete"><span>批量删除</span></a></li>
                <?php endif; ?>

                <?php if (\Yii::$app->user->can('video/recommend-video')): ?>
                    <li><a title="确定要推荐记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['video/recommend-video', 'column' => 'is_recommend', 'value' => 1]) ?>"
                           class="delete"><span>首页推荐</span></a></li>
                    <li><a title="确定要取消推荐记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['video/recommend-video', 'column' => 'is_recommend', 'value' => 0]) ?>"
                           class="delete"><span>首页推荐</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
        <table class="table" width="1200" layoutH="138">
            <thead>
            <tr>
                <th width="30"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                <th width="80">ID</th>
                <th width="80">分类名称</th>
                <th width="80">名称</th>
                <th width="80">描述</th>
                <th width="80">资源连接</th>
<!--                <th width="80">海报图片</th>-->
                <th width="60">播放数</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="play_num" width="60">真实播放数</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="real_play_num" width="60">下载数</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="real_download_num" width="60">真实下载数</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="collection_num" width="60">收藏数</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="real_collection_num" width="60">真实收藏数</th>
                <th width="80">首页推荐</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="update_time" width="100">建档日期</th>
                <th width="250">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dataList as $key => $data): ?>
                <tr target="video-id" rel="<?= $data['id'] ?>">
                    <td><input name="ids[]" value="<?= $data['id'] ?>" type="checkbox"></td>
                    <td><?= $data['id']?></td>
                    <td><?= $data['cate_id']?></td>
                    <td><?= $data['title']?></td>
                    <td><?= $data['sub_title']?></td>
                    <td><?= $data['video_link']?></td>
                    <td align="center"><?= $data['play_num']?></td>
                    <td align="center"><?= $data['real_play_num']?></td>
                    <td align="center"><?= $data['download_num']?></td>
                    <td align="center"><?= $data['real_download_num']?></td>
                    <td align="center"><?= $data['collection_num']?></td>
                    <td align="center"><?= $data['real_collection_num']?></td>
                    <td align="center"><?= $data['is_recommd'] ? '推荐' : '不推荐' ?></td>
                    <td><?= date('Y-m-d', $data['create_time']) ?></td>
                    <td>
                        <?php if (\Yii::$app->user->can('video/delete-video')): ?>
                            <a title="删除" target="ajaxTodo"
                               href="<?= Url::to(['video/delete-video', 'ids' => $data['id']]) ?>" class="btnDel">删除</a>
                        <?php endif; ?>

                        <?php if (\Yii::$app->user->can('video/edit-video')): ?>
                            <a title="编辑" target="navTab" href="<?= Url::to(['video/edit-video', 'id' => $data['id']]) ?>"
                               class="btnEdit">编辑</a>
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

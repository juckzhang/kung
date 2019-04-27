<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\VideoService;

$cardService = VideoService::getService();
$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderFiled','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage','20');

$cate = ArrayHelper::getValue($params,'category','');
$search = ArrayHelper::getValue($params,'search');
?>
<div class="" id="album-list" rel="album-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['video/album-list'])?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tbody>
                <tr>
                    <td>关键词：<input name="keyword" class="textInput" type="text" alt="" value="<?=ArrayHelper::getValue($params,'keyword')?>"></td>
                    <td>
                        视频分类：
                        <input type="hidden" name="other[category]" data-name="category.id" value="<?=$cate?>">
                        <input class="required textInput valid" value="<?=$cate?>" name="category" data-name="category.name"  postfield="keyword" suggestfields="name" lookupgroup="category" autocomplete="off" type="text">
                    </td>
                    <td>
                        <input type="hidden" name="search" value="<?=$search?>">
                        <a class="btnLook" href="<?=Url::to(['video/category-list','search' => 1])?>" lookupgroup="category">查找带回</a>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="subBar">
                <ul>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
                    <?php if($search):?>
                        <li><div class="button"><div class="buttonContent"><button type="button" multLookup="ids[]" warn="请选择专辑">选择带回</button></div></div></li>
                    <?php endif;?>
<!--                    <li><a class="button" href="demo_page6.html" target="dialog" mask="true" title="查询框"><span>高级检索</span></a></li>-->
                </ul>
            </div>
        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <?php if(\Yii::$app->user->can('video/edit-album')):?>
                <li><a class="add" href="<?=Url::to(['video/edit-album'])?>" target="navTab"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('video/delete-album')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['video/delete-album'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>
<!--            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="demo/common/ajaxDone.html" class="delete"><span>批量删除逗号分隔</span></a></li>-->
<!--            <li><a class="edit" href="demo_page4.html?uid={sid_user}" target="navTab" warn="请选择一个用户"><span>修改</span></a></li>-->
<!--            <li class="line">line</li>-->
<!--            <li><a class="icon" href="demo/common/dwz-team.xls" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>-->
            <!--<li><a target="selectedLoad" rel="ids" postType="string" href="demo_page1.html" class="icon"><span>批量Dialog Load逗号分隔</span></a></li>-->
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <th width="40">专辑ID</th>
            <th width="100">专辑名称</th>
            <th width="60">创建用户</th>
            <th width="80">视频分类</th>
            <th width="80">长海报图</th>
            <th width="80">宽海报图</th>
            <th width="40">导演</th>
            <th width="40">演员</th>
            <th width="60">地区</th>
            <th width="120">简介</th>
            <th width="60">上映年份</th>
            <th class="<?=$orderDirection?>" tyle="cursor: pointer;" orderfield="is_recommend" width="80">首页推荐</th>
            <th class="<?=$orderDirection?>" tyle="cursor: pointer;" orderfield="update_time" width="140">建档日期</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="album-id" rel="<?=$data->id?>">
                <td><input name="ids[]" value="<?=$search? "{id:$data->id,name:'{$data->name}'}" : $data->id?>" type="checkbox"></td>
                <td width="60"><?=$data->id?></td>
                <td><?=$data->name?></td>
                <td><?=$data->user_id?></td>
                <td><?=$data->cate_id?></td>
                <td><img src="<?=$data->poster_url?>" width="100%"/></td>
                <td><img src="<?=$data->wide_poster?>" width="100%"/></td>
                <td><?=$data->director?></td>
                <td><?=$data->actor?></td>
                <td><?=$data->area?></td>
                <td><?=$data->introduction?></td>
                <td><?=$data->release_time?></td>
                <td><?=$data->is_recommend ? '已推荐' : '未推荐'?></td>
                <td><?=date('Y-m-d H:i:s',$data->create_time)?></td>
                <td>
                    <?php if(\Yii::$app->user->can('video/delete-album')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['video/delete-album','ids' => $data->id])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('video/edit-album')):?>
                        <a title="编辑" target="navTab" href="<?=Url::to(['video/edit-album','id' => $data->id])?>" class="btnEdit">编辑</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('video/addvideo-list')):?>
                        <a title="可添加视频信息列表" target="dialog" href="<?=Url::to(['video/addvideo-list','album' => $data->id])?>" class="btnAdd" rel="album-list">视频信息列表</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('video/delvideo-list')):?>
                        <a title="可移除视频信息列表" target="dialog" href="<?=Url::to(['video/delvideo-list','album' => $data->id])?>" class="btnInfo" rel="album-list">视频信息列表</a>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="<?=$search ? 'dialogPageBreak' : 'navTabPageBreak'?>({numPerPage:this.value})">
                <option value="20" <?=$prePage == 20 ?   'selected' : ''?>>20</option>
                <option value="50" <?=$prePage == 50 ?   'selected' : ''?>>50</option>
                <option value="100" <?=$prePage == 100 ? 'selected' : ''?>>100</option>
                <option value="200" <?=$prePage == 200 ? 'selected' : ''?>>200</option>
            </select>
            <span>条，共<?=$dataCount?>条</span>
        </div>
        <div class="pagination" rel='album-list' targetType="<?=$search ? 'dialogTab' : 'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>

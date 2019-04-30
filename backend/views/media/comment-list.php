<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\MediaService;

$videoService = MediaService::getService();
$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderFiled','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage','20');

$search = ArrayHelper::getValue($params,'search');
?>
<div class="" id="comment-list" rel="comment-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="search", value="<?=$search?>">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['video/comment-list','search' => $search])?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tbody>
                <tr>
                    <td>关键词：<input name="keyword" class="textInput" type="text" alt="" value="<?=ArrayHelper::getValue($params,'keyword')?>"></td>
                </tr>
                </tbody>
            </table>
            <div class="subBar">
                <ul>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
                    <?php if($search):?>
                    <li><div class="button"><div class="buttonContent"><button type="button" multLookup="ids[]" warn="请选择部门">选择带回</button></div></div></li>
                    <?php endif;?>
                </ul>
            </div>
        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <?php if(\Yii::$app->user->can('video/edit-comment')):?>
            <li><a class="add" href="<?=Url::to(['video/edit-comment'])?>" target="dialog"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('video/delete-comment')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['video/delete-comment'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <th width="40">评论ID</th>
            <th width="80">资源ID</th>
            <th width="80">用户ID</th>
            <th width="80">评论内容</th>
            <th class="<?=$orderDirection?>" tyle="cursor: pointer;" orderfield="update_time" width="80">建档日期</th>
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data->id?>">
                <td><input name="ids[]" value="<?=$search? "{id:$data->id,name:'{$data->name}'}" : $data->id?>" type="checkbox"></td>
                <td><?=$data->id?></td>
                <td><?=$data->source_id?></td>
                <td><?=$data->user_id?></td>
                <td><?=$data->content?></td>
                <td><?=date('Y-m-d H:i:s',$data->create_time)?></td>
                <td>
                    <?php if(\Yii::$app->user->can('video/delete-comment')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['video/delete-comment','ids' => $data->id])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('video/edit-comment')):?>
                    <a title="编辑" target="dialog" href="<?=Url::to(['video/edit-comment','id' => $data->id])?>" class="btnEdit">编辑</a>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="<?=$search ? 'dialogPageBreak':'navTabPageBreak'?>({numPerPage:this.value})">
                <option value="20" <?=$prePage == 20 ?   'selected' : ''?>>20</option>
                <option value="50" <?=$prePage == 50 ?   'selected' : ''?>>50</option>
                <option value="100" <?=$prePage == 100 ? 'selected' : ''?>>100</option>
                <option value="200" <?=$prePage == 200 ? 'selected' : ''?>>200</option>
            </select>
            <span>条，共<?=$dataCount?>条</span>
        </div>
        <div class="pagination" rel='product-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>

<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\MediaService;

$mediaService = MediaService::getService();
$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderField','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage','20');
$other = ArrayHelper::getValue($params, 'other', []);
$search = ArrayHelper::getValue($params,'search');
?>
<div class="" id="category-list" rel="category-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="search", value="<?=$search?>">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
    <?php foreach ($other as $key => $value):?>
        <input type="hidden" name="other[<?=$key;?>]" value="<?=$value;?>"/>
    <?php endforeach;?>
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['media/category-list','search' => $search])?>" method="post">
        <?php foreach ($other as $key => $value):?>
            <input type="hidden" name="other[<?=$key;?>]" value="<?=$value;?>"/>
        <?php endforeach;?>
<!--        <div class="searchBar">-->
<!--            <table class="searchContent">-->
<!--                <tbody>-->
<!--                <tr>-->
<!--                    <td>关键词：<input name="keyword" class="textInput" type="text" alt="" value="<=ArrayHelper::getValue($params,'keyword')?>"></td>-->
<!--                </tr>-->
<!--                </tbody>-->
<!--            </table>-->
<!--            <div class="subBar">-->
<!--                <ul>-->
<!--                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>-->
<!--                    <php if($search):?>
<!--                    <li><div class="button"><div class="buttonContent"><button type="button" multLookup="ids[]" warn="请选择部门">选择带回</button></div></div></li>-->
<!--                    <php endif;?>
<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <?php if(\Yii::$app->user->can('media/edit-category')):?>
            <li><a class="add" href="<?=Url::to(['media/edit-category'])?>" target="dialog"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('media/delete-category')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['media/delete-category'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <th width="40">分类ID</th>
            <th width="80">中文名称</th>
            <th width="80">英文名称</th>
            <th class="<?=$orderDirection?>" style="cursor: pointer;" orderfield="update_time" width="80">修改时间</th>
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data->id?>">
                <td><input name="ids[]" value="<?=$search? "{id:$data->id,name:'{$data->name}'}" : $data->id?>" type="checkbox"></td>
                <td><?=$data->id?></td>
                <td><?=$data->name?></td>
                <td><?=$data->name_en?></td>
                <td><?=date('Y-m-d H:i:s',$data->update_time)?></td>
                <td>
                    <?php if(\Yii::$app->user->can('media/delete-category')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['media/delete-category','ids' => $data->id])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('media/edit-category')):?>
                    <a title="编辑" target="dialog" href="<?=Url::to(['media/edit-category','id' => $data->id])?>" class="btnEdit">编辑</a>
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
        <div class="pagination" rel='category-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>

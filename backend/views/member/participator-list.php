<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderFiled','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage','20');

?>

<div class="" id="card-list" rel="card-list">
    <form id="pagerForm" method="post" action="#rel#">
        <input type="hidden" name="pageNum" value="<?=$page?>" />
        <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
        <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
        <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
    </form>
    <div class="pageHeader">
        <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=Url::to(['member/participator-list'])?>" method="post">
            <div class="searchBar">
                <ul class="searchContent">
                    <li>
                        <label>搜索关键词：</label>
                        <input type="text" name="keyword" value="<?=ArrayHelper::getValue($params,'keyword','')?>" alt="关键词"/>
                    </li>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
                </ul>
            </div>
        </form>
    </div>
    <div class="pageContent">
        <div class="panelBar">
            <ul class="toolBar">
                <?php if (\Yii::$app->user->can('member/update-participator')): ?>
                    <li><a class="add" href="<?= Url::to(['member/member-list', 'participator'=>1]) ?>" lookupgroup="user"><span>添加合作会员</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
        <table class="table" width="1200" layoutH="138">
            <thead>
            <tr>
                <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                <th width="30">用户ID</th>
                <th width="80">用户昵称</th>
                <th width="80">手机号</th>
                <th width="70">操作</th>
                <th width="50">用户头像</th>
                <th class="<?=$orderDirection?>" tyle="cursor: pointer;" orderfield="create_time" width="80">建档日期</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($dataList as $key => $data):?>
                <tr target="card-id" rel="<?=$data->id?>">
                    <td><input name="ids[]" value="<?=$data->id?>" type="checkbox"></td>
                    <td><?=$data->id?></td>
                    <td><?=$data->nick_name?></td>
                    <td><?=$data->mobile?></td>
                    <td>
                        <?php if(\Yii::$app->user->can('member/assign-profit')):?>
                            <a class="btnEdit" title="给分成" href="<?=Url::to(['member/assign-profit', 'user_id'=>$data->id, 'nick_name'=>$data->nick_name, 'mobile'=>$data->mobile])?>" target="dialog">给分成</a>
                        <?php endif;?>
                        <?php if (\Yii::$app->user->can('member/update-participator')): ?>
                            <a title="移除合作会员" target="ajaxTodo"
                               href="<?= Url::to(['member/update-participator', 'user_id' => $data['id'], 'state' => 0]) ?>" class="btnDel">删除</a>
                        <?php endif; ?>
                        <?php if(\Yii::$app->user->can('member/edit-user')):?>
                            <a class="btnView" title="编辑" href="<?=Url::to(['member/edit-user','user_id'=>$data->id])?>" target="dialog">编辑</a>
                        <?php endif;?>
                    </td>
                    <td><img src="<?=$data->icon_url?>" width="100%"></td>
                    <td><?=date('Y-m-d H:i:s',$data->create_time)?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <div class="panelBar">
            <div class="pages">
                <span>显示</span>
                <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                    <option value="20" <?=$prePage == 20 ?   'selected' : ''?>>20</option>
                    <option value="50" <?=$prePage == 50 ?   'selected' : ''?>>50</option>
                    <option value="100" <?=$prePage == 100 ? 'selected' : ''?>>100</option>
                    <option value="200" <?=$prePage == 200 ? 'selected' : ''?>>200</option>
                </select>
                <span>条，共<?=$dataCount?>条</span>
            </div>
            <div class="pagination" rel='card-list' targetType="navTab" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
        </div>
    </div>
</div>

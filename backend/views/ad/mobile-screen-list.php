<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderFiled','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage','20');
?>
<div class="" id="mobile-screen-list" rel="mobile-screen-list">
    <form id="pagerForm" method="post" action="#rel#">
        <input type="hidden" name="pageNum" value="<?=$page?>" />
        <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
        <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
        <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
    </form>
    <div class="pageHeader">
        <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=Url::to(['ad/mobile-screen-list'])?>" method="post">
        </form>
    </div>
    <div class="pageContent">
        <div class="panelBar">
            <ul class="toolBar">
            </ul>
        </div>
        <table class="table" width="800" layoutH="138">
            <thead>
            <tr>
                <th width="180">移动端开屏图片</th>
                <th width="260">跳转页面链接</th>
                <th class="<?=$orderDirection?>" tyle="cursor: pointer;" orderfield="create_time" width="80">建档日期</th>
                <th class="<?=$orderDirection?>" tyle="cursor: pointer;" orderfield="update_time" width="80">更新日期</th>
                <th width="70">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($dataList as $key => $data):?>
                <tr target="card-id" rel="<?=$data->id?>">
                    <td><img src="<?=$data->screen_img?>" width="100%"></td>
                    <td><?=$data->screen_url?></td>
                    <td><?=date('Y-m-d H:i:s',$data->create_time)?></td>
                    <td><?=date('Y-m-d H:i:s',$data->update_time)?></td>
                    <td>
                        <?php if(\Yii::$app->user->can('ad/edit-mobile-screen')):?>
                        <a title="编辑" target="navTab" href="<?=Url::to(['ad/edit-mobile-screen','id' => $data->id])?>" class="btnEdit">编辑</a>
                        <?php endif;?>
                    </td>
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

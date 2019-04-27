<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\ArticleService;

$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderFiled','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage','20');

//查询条件
$cateId = ArrayHelper::getValue($params,'other.cateId');
$categories = ArticleService::getService()->categories();
?>
<div class="" id="card-list" rel="card-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=Url::to(['article/article-list'])?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tbody>
                <tr>
                    <td>文章标题：<input name="keyword" class="textInput" type="text" alt="文章标题"></td>
                    <td>
                        <select class="" name="province">
                            <option value="" selected>所有文章</option>
                            <?php foreach($categories as $value):?>
                                <option value="<?=$value['id']?>" <?=$cateId == $value['id'] ? 'selected' :''?>><?=$value['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="subBar">
                <ul>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
<!--                    <li><a class="button" href="demo_page6.html" target="dialog" mask="true" title="查询框"><span>高级检索</span></a></li>-->
                </ul>
            </div>
        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <?php if(\Yii::$app->user->can('article/edit-article')):?>
            <li><a class="add" href="<?=Url::to(['article/edit-article'])?>" target="navTab"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('article/delete-article')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['article/delete-article'])?>" class="delete"><span>批量删除</span></a></li>
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
            <th width="150">文章标题</th>
            <th width="80">副标题</th>
            <th width="80">作者</th>
            <th width="80">来源</th>
            <th width="80">文章分类</th>
            <th width="80" class="<?=$orderDirection?>" tyle="cursor: pointer;" orderfield="is_recommend">是否推荐</th>
            <th width="60">在专题里隐藏</th>
            <th class="<?=$orderDirection?>" tyle="cursor: pointer;" orderfield="update_time" width="80">建档日期</th>
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data->id?>">
                <td><input name="ids[]" value="<?=$data->id?>" type="checkbox"></td>
                <td><?=$data->title?></td>
                <td><?=$data->sub_title?></td>
                <td><?=$data->author?></td>
                <td><?=$data->source?></td>
                <td><?=$data->cate_id?></td>
                <td><?=$data->is_recommend == 1 ? '是' : '否'?></td>
                <td><?=$data->hide_in_list == 1 ? '是' : '否'?></td>
                <td><?=date('Y-m-d H:i:s',$data->create_time)?></td>
                <td>
                    <?php if(\Yii::$app->user->can('article/delete-article')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['article/delete-article','ids' => $data->id])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('article/edit-article')):?>
                    <a title="编辑" target="navTab" href="<?=Url::to(['article/edit-article','id' => $data->id])?>" class="btnEdit">编辑</a>
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

<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$params = \Yii::$app->request->getPost();
$page = ArrayHelper::getValue($params, 'pageNum', '1');
$orderFiled = ArrayHelper::getValue($params, 'orderFiled', '');
$orderDirection = ArrayHelper::getValue($params, 'orderDirection', 'asc');
$prePage = ArrayHelper::getValue($params, 'numPerPage', '20');

function typeInfo($type) {
    if ($type == 1) {
        return '广告分成';
    } else if ($type == 2) {
        return '分成提现';
    } else {
        return '未知';
    }
}

?>
<div class="" id="card-list" rel="card-list">
    <form id="pagerForm" method="post" action="#rel#">
        <input type="hidden" name="pageNum" value="<?= $page ?>"/>
        <input type="hidden" name="numPerPage" value="<?= $prePage ?>"/>
        <input type="hidden" name="orderField" value="<?= $orderFiled ?>"/>
        <input type="hidden" name="orderDirection" value="<?= $orderDirection ?>"/>
    </form>
    <div class="pageHeader">
        <form rel="pagerForm" onsubmit="return navTabSearch(this);"
              action="<?= Url::to(['member/qualification-list']) ?>" method="post">
            <div class="searchBar">
                <tr class="searchContent">
                    <td width="200">
                        搜索用户：
                        <input type="hidden" name="other[album]" data-name="album.id" value="<?= '' ?>">
                        <input class="required textInput valid lookup" value="<?= '' ?>" data-name="album.name"
                               name="album" postfield="keyword" suggestfields="name" lookupgroup="album"
                               autocomplete="off" type="text">
                    </td>
                    <td><a class="btnLook" href="<?= Url::to(['video/album-list', 'search' => 1]) ?>"
                           lookupgroup="album">查找带回</a></td>
                </tr>
            </div>
        </form>
    </div>
    <div class="pageContent">
        <div class="panelBar">
            <ul class="toolBar">
                <?php if (\Yii::$app->user->can('member/delete-qualification')): ?>
                    <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['member/delete-qualification']) ?>" class="delete"><span>批量删除</span></a>
                    </li>
                <?php endif; ?>
                <!--            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="demo/common/ajaxDone.html" class="delete"><span>批量删除逗号分隔</span></a></li>-->
                <!--            <li><a class="edit" href="demo_page4.html?uid={sid_member}" target="navTab" warn="请选择一个用户"><span>修改</span></a></li>-->
                <!--            <li class="line">line</li>-->
                <!--            <li><a class="icon" href="demo/common/dwz-team.xls" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>-->
                <!--<li><a target="selectedLoad" rel="ids" postType="string" href="demo_page1.html" class="icon"><span>批量Dialog Load逗号分隔</span></a></li>-->
            </ul>
        </div>
        <table class="table" width="1200" layoutH="138">
            <thead>
            <tr>
                <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                <th width="30">用户ID</th>
                <th width="80">昵称</th>
                <th width="80">手机号</th>
                <th width="30">类型</th>
                <th width="40">金额</th>
                <th width="80">描述</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="update_time" width="80">申请日期</th>
                <th width="70">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dataList as $key => $data): ?>
                <tr target="card-id" rel="<?= $data->user_id ?>">
                    <td><input name="ids[]" value="<?= $data->user_id ?>" type="checkbox"></td>
                    <td><?= $data->user_id ?></td>
                    <td><?= $data->user->nick_name ?></td>
                    <td><?= $data->user->mobile ?></td>
                    <td><?= typeInfo($data->type) ?></td>
                    <td><?= $data->sum ?></td>
                    <td><?= $data->desc ?></td>
                    <td><?= date('Y-m-d H:i:s', $data->update_time) ?></td>
                    <td>
                        <?php if (\Yii::$app->user->can('member/delete-profit')): ?>
                            <a title="删除" target="ajaxTodo"
                               href="<?= Url::to(['member/delete-profit', 'ids' => $data->id]) ?>"
                               class="btnDel">删除</a>
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
            <div class="pagination" rel='card-list' targetType="navTab" totalCount="<?= $dataCount ?>"
                 numPerPage="<?= $prePage ?>" pageNumShown="10" currentPage="<?= $page ?>"></div>
        </div>
    </div>
</div>

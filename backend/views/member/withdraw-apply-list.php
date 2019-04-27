<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$params = \Yii::$app->request->getPost();
$page = ArrayHelper::getValue($params, 'pageNum', '1');
$orderFiled = ArrayHelper::getValue($params, 'orderFiled', '');
$orderDirection = ArrayHelper::getValue($params, 'orderDirection', 'asc');
$prePage = ArrayHelper::getValue($params, 'numPerPage', '20');

function processMesage($process) {
    if ($process == 1) {
        return '1 等待审核';
    }

    if ($process == 2) {
        return '2 已通过审核，等待收取发票';
    }

    if ($process == 3) {
        return '3 已经收到发票，已经通知财务打款';
    }

    if ($process == 4) {
        return '4 财务已经打款，提现完成';
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
        <table class="table" width="1200" layoutH="138">
            <thead>
            <tr>
                <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                <th width="30">用户ID</th>
                <th width="80">昵称</th>
                <th width="80">手机号</th>
                <th width="40">金额</th>
                <th width="80">进度</th>
                <th width="70">操作</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="update_time" width="80">申请日期</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dataList as $key => $data): ?>
                <tr target="card-id" rel="<?= $data->user_id ?>">
                    <td><input name="ids[]" value="<?= $data->user_id ?>" type="checkbox"></td>
                    <td><?= $data->user_id ?></td>
                    <td><?= $data->user->nick_name ?></td>
                    <td><?= $data->user->mobile ?></td>
                    <td><?= $data->sum ?></td>
                    <td><?= processMesage($data->process) ?></td>
                    <td>
                        <?php if (\Yii::$app->user->can('member/update-withdraw-apply')): ?>
                            <a title="通过申请" target="ajaxTodo" href="<?= Url::to(['member/update-withdraw-apply', 'id' => $data->id, 'process' => 2]) ?>" class="btnAdd">通过申请</a>
                            <a title="收到发票-财务打钱" target="ajaxTodo" href="<?= Url::to(['member/update-withdraw-apply', 'id' => $data->id, 'process' => 3]) ?>" class="btnAttach">收到发票-财务打钱</a>
                            <a title="完成" target="ajaxTodo" href="<?= Url::to(['member/update-withdraw-apply', 'id' => $data->id, 'process' => 4]) ?>" class="btnSelect">完成</a>
                        <?php endif; ?>
                    </td>
                    <td><?= date('Y-m-d H:i:s', $data->update_time) ?></td>
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

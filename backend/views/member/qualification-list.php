<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$params = \Yii::$app->request->getPost();
$page = ArrayHelper::getValue($params, 'pageNum', '1');
$orderFiled = ArrayHelper::getValue($params, 'orderFiled', '');
$orderDirection = ArrayHelper::getValue($params, 'orderDirection', 'asc');
$prePage = ArrayHelper::getValue($params, 'numPerPage', '20');

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
                <ul class="searchContent">
                    <li>
                        <label>搜索关键词：</label>
                        <input type="text" name="keyword" value="<?= ArrayHelper::getValue($params, 'keyword', '') ?>"
                               alt="关键词"/>
                    </li>
                </ul>
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
                <th width="80">真实姓名</th>
                <th width="80">手机号</th>
                <th width="80">审核状态</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="update_time" width="80">申请日期</th>
                <th width="70">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dataList as $key => $data): ?>
                <tr target="card-id" rel="<?= $data->user_id ?>">
                    <td><input name="ids[]" value="<?= $data->user_id ?>" type="checkbox"></td>
                    <td><?= $data->user_id ?></td>
                    <td><?= $data->name ?></td>
                    <td><?= $data->mobile ?></td>
                    <td><?= ($data->status == 1 ? '等待审核' : ($data->status == 2 ? '已通过' : ($data->status == 3 ? '被拒绝' : '未申请'))) ?></td>
                    <td><?= date('Y-m-d H:i:s', $data->update_time) ?></td>
                    <td>
                        <?php if (\Yii::$app->user->can('member/delete-member')): ?>
                            <a title="删除" target="ajaxTodo"
                               href="<?= Url::to(['member/delete-member', 'user_id' => $data->user_id]) ?>"
                               class="btnDel">删除</a>
                        <?php endif; ?>
                        <?php if (\Yii::$app->user->can('member/edit-qualification')): ?>
                            <a title="审核" target="navTab"
                               href="<?= Url::to(['member/edit-qualification', 'user_id' => $data->user_id]) ?>"
                               class="btnEdit">审核</a>
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

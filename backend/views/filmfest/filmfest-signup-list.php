<?php
/**
 * Created by PhpStorm.
 * User: dongbin
 * Date: 13/07/2017
 * Time: 4:46 PM
 */

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\FilmfestService;

?>

<div class="" id="filmfest-signup-list" rel="filmfest-signup-list">
    <div class="pageContent">
        <div class="panelBar">
            <ul class="toolBar">
                <?php if(\Yii::$app->user->can('filmfest/delete-signup')):?>
                    <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['filmfest/delete-signup'])?>" class="delete"><span>批量删除</span></a></li>
                <?php endif;?>
            </ul>
        </div>
        <table class="table" width="1200" layoutH="138">
            <thead>
            <tr>
                <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                <th width="200">所报电影节</th>
                <th width="160">用户</th>
                <th width="160">电影名称</th>
                <th width="120">报名时间</th>
                <th width="40">操作</th>
                <th width="60">支付情况</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($dataList as $key => $data):?>
                <tr target="card-id" rel="<?= $data->id ?>">
                    <td><input name="ids[]" value="<?= $data->id ?>" type="checkbox"></td>
                    <td><?= $data->filmfest ? $data->filmfest->title : "没有" ?></td>
                    <td><?= $data->filmfest ? $data->user->nick_name : "也没有" ?></td>
                    <td><?= $data->movie_name ?></td>
                    <td><?= date('Y-m-d H:i:s', $data->create_time) ?></td>
                    <td>
                        <a title="显示" target="_blank" href="<?=Url::to(['filmfest/show-signup-info','id' => $data->id])?>" class="btnInfo">显示</a>
                    </td>
                    <td><?= $data->pay_status == 1 ? "已支付" : "未支付" ?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>


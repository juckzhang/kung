<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\mysql\MenuModel;
$parentId = ArrayHelper::getValue($model,'parent_id');
$id       = ArrayHelper::getValue($model,'id');
//$type     = ArrayHelper::getValue($model,'type','');
$positionId = ArrayHelper::getValue($model,'position_id');
//$menuTypes = [
//    MenuModel::MENU_TYPE_ARTICLE => '文章详情',
//    MenuModel::MENU_TYPE_CATEGORY => '文章列表',
//    MenuModel::MENU_TYPE_OTHER   => '其他',
//];
$positions = [
//    MenuModel::POSITION_ALL => '所有位置',
    MenuModel::POSITION_TOP => '顶部导航栏',
    MenuModel::POSITION_BOTTOM => '底部导航栏',
    MenuModel::POSITION_CENTER => 'footer顶部导航栏',
];
?>
<h2 class="contentTitle">文章编辑</h2>
<div class="pageContent">

    <form method="post" action="<?=Url::to(['menu/edit-menu','id' => $id])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>菜单名称：</dt>
                <dd>
                    <input type="text" name="MenuModel[name]"  class="" value="<?=ArrayHelper::getValue($model,'name','')?>"/>
                </dd>
            </dl>
            <dl>
                <dt>显示位置：</dt>
                <dd>
                    <select name="MenuModel[position_id]" value="<?=$positionId?>">
                        <option>-- 请选择显示位置 --</option>
                        <?php foreach($positions as $key => $value):?>
                            <option value="<?=$key?>" <?=$positionId == $key ? 'selected="selected"' : ''?>><?=$value?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <!--dl>
                <dt>链接类型：</dt>
                <dd>
                    <select name="MenuModel[type]" value="<=$type?>">
                        <option>-- 请选择链接类型 --</option>
                        <php foreach($menuTypes as $key => $value):?>
                            <option value="<=$key?>" <=$type == $key ? 'selected="selected"' : ''?>><=$value?></option>
                        <php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl -->
                <dt>链接地址：</dt>
                <dd>
                    <input type="text" name="MenuModel[link_url]"  class="" value="<?=ArrayHelper::getValue($model,'link_url','')?>" style="width:60%;"/>
                </dd>
            </dl>
            <dl>
                <dt>父级菜单：</dt>
                <dd>
                    <select class="" name="MenuModel[parent_id]" value="<?=ArrayHelper::getValue($model,'parent_id')?>">
                        <option value="0">-- 一级菜单 --</option>
                        <?php foreach($menus as $menu):?>
                            <?php if($id != $menu['id']):?>
                                <option value="<?=$menu['id']?>" <?=$parentId == $menu['id'] ? 'selected="selected"' : ''?>><?=$menu['name']?></option>
                            <?php endif;?>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>


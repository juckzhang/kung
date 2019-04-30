<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$parentId = ArrayHelper::getValue($model,'parent_id');
$id       = ArrayHelper::getValue($model,'id');
?>
<h2 class="contentTitle">编辑视频分类信息</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['video/edit-category','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>分类名称：</dt>
                <dd>
                    <input type="text" name="VideoCategoryModel[name]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'name','')?>"/>
                    <span class="info">分类名称不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>父级菜单：</dt>
                <dd>
                    <select class="" name="VideoCategoryModel[parent_id]" value="<?=ArrayHelper::getValue($model,'parent_id')?>">
                        <option value="0">-- 一级菜单 --</option>
                        <?php foreach($categories as $category):?>
                            <?php if($id != $category['id']):?>
                                <option value="<?=$category['id']?>" <?=$parentId == $category['id'] ? 'selected="selected"' : ''?>><?=$category['name']?></option>
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
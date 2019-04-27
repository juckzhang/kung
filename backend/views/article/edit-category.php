<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>
<h2 class="contentTitle">编辑文章分类</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['article/edit-category','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>分类名称：</dt>
                <dd>
                    <input type="text" name="CategoryModel[name]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'name','')?>"/>
                    <span class="info">分类名称不能为空</span>
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


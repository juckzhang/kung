<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
$categories = [
    ['id' => 1, 'name' => '视频'],
    ['id' => 2, 'name' => '音频'],
    ['id' => 3, 'name' => 'PDF'],
];
?>
<h2 class="contentTitle">编辑视频分类信息</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['media/edit-category','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>中文名称：</dt>
                <dd>
                    <input type="text" name="MediaCategoryModel[name]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'name','')?>"/>
                    <span class="info">分类名称不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>英文名称：</dt>
                <dd>
                    <input type="text" name="MediaCategoryModel[name_en]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'name_en','')?>"/>
                    <span class="info">分类名称不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>媒体类型：</dt>
                <dd>
                    <select class="" name="MediaCategoryModel[source_type]" value="<?=ArrayHelper::getValue($model,'source_type')?>">
                        <option value="0">-- 媒体类型 --</option>
                        <?php foreach($categories as $category):?>
                            <option value="<?=$category['id']?>" <?=$model['source_type'] == $category['id'] ? 'selected="selected"' : ''?>><?=$category['name']?></option>
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
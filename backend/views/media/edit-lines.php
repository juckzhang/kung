<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
$defaultSourceId = ArrayHelper::getValue(\Yii::$app->request->getPost(), 'source_id');
$sourceId = ArrayHelper::getValue($model, 'source_id', $defaultSourceId);
?>
<h2 class="contentTitle">编辑台词</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['media/edit-lines','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>资源id：</dt>
                <dd>
                    <input type="text" name="MediaLinesModel[source_id]" maxlength="20" class="required" value="<?=$sourceId?>"/>
                    <span class="info">资源id不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>序号：</dt>
                <dd>
                    <input type="text" name="MediaLinesModel[line_number]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'line_number',1)?>"/>
                    <span class="info">序号不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>开始时间：</dt>
                <dd>
                    <input type="text" placeholder="格式:00:00:00" name="MediaLinesModel[start_time]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'start_time','')?>"/>
                    <span class="info">开始时间不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>结束时间：</dt>
                <dd>
                    <input type="text" placeholder="格式:00:00:00" name="MediaLinesModel[end_time]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'end_time','')?>"/>
                    <span class="info">结束时间不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>语种：</dt>
                <dd>
                    <select class="" name="MediaLinesModel[lang_type]" value="<?=ArrayHelper::getValue($model,'lang_type')?>">
                        <?php foreach(['zh_CN' => '中文', 'en_US' => '英语'] as $key => $value):?>
                            <option value="<?=$key?>" <?=$model['lang_type'] == $key ? 'selected="selected"' : ''?>><?=$value?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>台词内容：</dt>
                <dd>
                    <textarea type="text" name="MediaLinesModel[content]" class="required" value="<?=ArrayHelper::getValue($model,'content','')?>"><?=ArrayHelper::getValue($model, 'content', '')?></textarea>
                    <span class="info">台词内容不能为空</span>
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
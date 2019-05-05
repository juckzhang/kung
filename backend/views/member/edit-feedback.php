<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$params = \Yii::$app->request->getPost();
$parent_id = ArrayHelper::getValue($params, 'parent_id', 0);
?>
<h2 class="contentTitle">用户反馈回复</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['member/edit-feedback','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <input type="hidden" name="FeedbackModel[user_id]" value="1"/>
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>回复ID：</dt>
                <dd>
                    <input type="text" name="FeedbackModel[parent_id]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'parent_id',$parent_id)?>"/>
                    <span class="info">分类名称不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>回复内容：</dt>
                <dd>
                    <textarea style="width: 200px;height: 100px;" name="FeedbackModel[content]" value="<?=ArrayHelper::getValue($model,'content','')?>"><?=ArrayHelper::getValue($model,'content','')?></textarea>
                    <span class="info">回复内容不能为空</span>
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
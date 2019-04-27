<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>
<h2 class="contentTitle">编辑账户信息</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['member/edit-user'])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <input type="hidden" name="user_id" value="<?= $user['id']?>"/>
                <dt><?= '用户ID：'.$user['id'] ?></dt>
                <dt><?= '用户昵称：'.$user['nick_name'] ?></dt>
                <dt><?= '电话号码：'.$user['mobile'] ?></dt>
                <dd>
                    <label>账户类型：</label>
                    <input type="text" name="UserModel[account_type]" class="required" value="<?= ArrayHelper::getValue($user, 'account_type', '') ?>"/>
                    <span class="info">&nbsp&nbsp&nbsp账户类型不能为空</span>
                </dd>
                <dd>
                    <label>收款方名称：</label>
                    <input type="text" name="UserModel[payee_name]"  class="required" value="<?= ArrayHelper::getValue($user, 'payee_name', '') ?>"/>
                    <span class="info">&nbsp&nbsp&nbsp收款方名称不能为空</span>
                </dd>
                <dd>
                    <label>收款账户：</label>
                    <input type="text" name="UserModel[gathering]" class="required" value="<?= ArrayHelper::getValue($user, 'gathering', '') ?>"/>
                    <span class="info">&nbsp&nbsp&nbsp收款账户不能为空</span>
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
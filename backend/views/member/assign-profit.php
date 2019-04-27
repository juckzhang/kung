<?php
use yii\helpers\Url;

?>
<h2 class="contentTitle">给分成</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['member/assign-profit'])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <input type="hidden" name="user_id" value="<?= $user_id?>"/>
                <dt><?= '用户ID：'.$user_id ?></dt>
                <dt><?= '用户昵称：'.$nick_name ?></dt>
                <dt><?= '电话号码：'.$mobile ?></dt>
                <dd>
                    <label>类型：</label>
                    <select name="type">
                        <option value="1" selected="selected">广告分成</option>
                    </select>
                </dd>
                <dd>
                    <label>金额：</label>
                    <input type="text" name="sum" maxlength="20" class="required" value=""/>
                    <span class="info">&nbsp&nbsp&nbsp金额不能为空</span>
                </dd>
                <dd>
                    <label>说明：</label>
                    <textarea name="desc" width="240" height="160" class="required" value="" />
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
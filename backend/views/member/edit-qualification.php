<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;
use common\services\UserService;

$userService = UserService::getService();
$career_name = '';
$career = ArrayHelper::getValue($model, 'career');
switch ($career) {
    case 1:
        $career_name = '导演';
        break;
    case 2:
        $career_name = '编剧';
        break;
    case 3:
        $career_name = '演员';
        break;
    case 4:
        $career_name = '摄像';
        break;
    case 5:
        $career_name = '灯光师';
        break;
    case 6:
        $career_name = '制片人';
        break;
    case 7:
        $career_name = '后期';
        break;
    case 8:
        $career_name = '剪辑师';
        break;
    case 9:
        $career_name = '特效师';
        break;
    case 10:
        $career_name = '化妆师';
        break;
    case 11:
        $career_name = '配音师';
        break;
    case 12:
        $career_name = '配乐师';
        break;
    default:
        $career_name = '';
        break;
}
?>

<h2 class="contentTitle">视频编辑</h2>
<div class="pageContent">
    <form method="post"
          action="<?= Url::to(['member/edit-qualification', 'user_id' => ArrayHelper::getValue($model, 'user_id', '')]) ?>"
          class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label>用户 ID：</label>
                <input id="user-id" type="text" name="QualificationModel[user_id]"
                       value="<?= ArrayHelper::getValue($model, 'user_id') ?>" readonly="true">
            </p>
            <p>
                <label>真实姓名：</label>
                <input id="name" type="text" name="QualificationModel[name]"
                       value="<?= ArrayHelper::getValue($model, 'name') ?>" readonly="true">
            </p>
            <p>
                <label>性别：</label>
                <input id="sex" type="text" name="QualificationModel[sex]"
                       value="<?= ArrayHelper::getValue($model, 'sex', 0) == 1 ? '女' : '男' ?>" readonly="true">
            </p>
            <p>
                <label>职业：</label>
                <input type="text" name="QualificationModel[career]" value="<?= $career_name ?>" readonly="true">
            </p>
            <p>
                <label>联系方式：</label>
                <input type="text" name="QualificationModel[mobile]"
                       value="<?= ArrayHelper::getValue($model, 'mobile', 0) ?>" readonly="true">
            </p>
            <p>
                <label>邮箱：</label>
                <input type="text" name="QualificationModel[email]"
                       value="<?= ArrayHelper::getValue($model, 'email', '') ?>" readonly="true">
            </p>
            <p>
                <label>代表作品：</label>
                <input type="text" name="QualificationModel[opus]"
                       value="<?= ArrayHelper::getValue($model, 'opus', '') ?>" readonly="true">
            </p>
            <dl>
                <dt>自我介绍：</dt>
                <dd>
                    <textarea id="note" style="width: 300px; height: 140px;" class="textInput valid"
                              readonly="true"><?= ArrayHelper::getValue($model, 'note', '') ?></textarea>
                </dd>
            </dl>
            <p>
                <label>横板海报图片：</label>
                <img id="idcard" style="width: 300px; height: auto;"
                     src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1490360720939&di=1fcb28cf1a3553fd9b46842edde6e705&imgtype=0&src=http%3A%2F%2Fwww.sznews.com%2Fent%2Fimages%2Fattachement%2Fjpg%2Fsite3%2F20141011%2F4437e629783815a2bce257.jpg"/>
            </p>
        </div>
        <div class="formBar">
            <ul>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button id="qual-pass" type="button">审核通过</button>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="button">
                        <div class="buttonContent">
                            <button type="button" class="close">取消</button>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button id="qual-eject" type="button">审核不通过</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
<script src="/js/ajaxfileupload.js"></script>
<script type="text/javascript">
    $(function () {
        $('#qual-pass').on('click', function () {
            var user_id = $('#user-id').val();
            var param = {
                user_id: user_id,
                status: 2
            };
            $.ajax({
                url: "member/edit-qualification",
                type: "POST",
                data: param,
                dataType:"json",
                success: function ($res) {
                    if ($res.code == 200) {
                    }
                }
            });
        });
        $('#qual-eject').on('click', function () {
            var user_id = $('#user-id').val();
            var param = {
                user_id: user_id,
                status: 3
            };
            $.ajax({
                url: "member/edit-qualification",
                type: "POST",
                data: param,
                dataType:"json",
                success: function ($res) {
                    if ($res.code == 200) {
                    }
                }
            });
        });
    });
</script>

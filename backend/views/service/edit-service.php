<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;
use common\models\mysql\AdminModel;

$admin = AdminModel::find()->select('id,username')->asArray()->all();
?>
<?php $this->beginBody() ?>
<style>
    .pageFormContent .textInput {
        width: 100%;
    }
</style>
<h2 class="contentTitle">场景编辑</h2>
<div class="pageContent">
    <form method="post"
          action="<?= Url::to(['service/edit-service', 'id' => ArrayHelper::getValue($model, 'id', '')]) ?>"
          class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label style="width:60px;">场景标题：</label>
                <input type="text" name="ServiceModel[title]" class=""
                       value="<?= ArrayHelper::getValue($model, 'title', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">作者：</label>
                <input type="text" name="ServiceModel[author]" class=""
                       value="<?= ArrayHelper::getValue($model, 'author', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">来源：</label>
                <input type="text" name="ServiceModel[source]" class=""
                       value="<?= ArrayHelper::getValue($model, 'source', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">租赁费用:</label>
                <input type="text" name="ServiceModel[price]" class=""
                       value="<?= ArrayHelper::getValue($model, 'price', '') ?>" style="width:60%;"/>元/天
            </p>
            <p>
                <label style="width:60px;">海报图片：</label>
                <input type="text" name="ServiceModel[poster_url]" id='poster-url' class=""
                       value="<?= ArrayHelper::getValue($model, 'poster_url', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width: 60px">场地要求:</label>
                <textarea style="width: 495px;" rows="10" name="ServiceModel[requirement]"><?= ArrayHelper::getValue($model, 'requirement', '') ?></textarea>
            </p>
            <dl>
                <dt></dt>
                <dd style="margin-left: 70px;">
                    <input id="poster-upload" class="upload-input" data-name="poster-url" style="display: none" type="file"
                           name="UploadForm[file]">
                    <div style="width:600px; height:200px;border: dashed 2px #e5e5e5;text-align: center; line-height: 200px;">
                        <img id="upload" class="upload-btn" style="max-width: 600px; max-height: 200px;"
                             src="<?= !empty($model['poster_url']) ? \Yii::$app->params['imageUrlPrefix'] . $model['poster_url'] : '/images/upload.png' ?>"/>
                    </div>
                </dd>
            </dl>

            <dl>
                <p>
                    <label style="width:60px;">Banner：</label>
                    <input type="text" name="ServiceModel[banner_url]" id='banner-url' class=""
                           value="<?= ArrayHelper::getValue($model, 'banner_url', '') ?>" style="width:60%;"/>
                </p>
            </dl>
            <dl>
                <dt></dt>
                <dd style="margin-left: 70px;">
                    <input id="banner-upload" class="upload-input" data-name="banner-url" style="display: none" type="file"
                           name="UploadForm[file]">
                    <div style="width:600px; height:200px;border: dashed 2px #e5e5e5;text-align: center; line-height: 200px;">
                        <img id="upload" class="upload-btn" style="max-width: 600px; max-height: 200px;"
                             src="<?= !empty($model['banner_url']) ? \Yii::$app->params['imageUrlPrefix'] . $model['banner_url'] : '/images/upload.png' ?>"/>
                    </div>
                </dd>
            </dl>

            <dl>
                <p>
                    <label style="width:60px;">VR海报：</label>
                    <input type="text" name="ServiceModel[vr_poster]" id='vr_poster' class=""
                           value="<?= ArrayHelper::getValue($model, 'vr_poster', '') ?>" style="width:60%;"/>
                </p>
            </dl>
            <dl>
                <dt></dt>
                <dd style="margin-left: 70px;">
                    <input id="vr-upload" class="upload-input" data-name="vr_poster" style="display: none" type="file"
                           name="UploadForm[file]">
                    <div style="width:600px; height:200px;border: dashed 2px #e5e5e5;text-align: center; line-height: 200px;">
                        <img id="upload" class="upload-btn" style="max-width: 600px; max-height: 200px;"
                             src="<?= !empty($model['vr_poster']) ? \Yii::$app->params['imageUrlPrefix'] . $model['vr_poster'] : '/images/upload.png' ?>"/>
                    </div>
                </dd>
            </dl>

            <p>
                <label style="width: 60px;">所属场主:</label>
                <select name="ServiceModel[admin_id]">
                    <option value="" <?=ArrayHelper::getValue($model, 'admin_id', '')?'':'selected'?>>-- 请选择场主 --</option>
                    <?php foreach ($admin as $val):?>
                        <option value="<?=$val['id']?>" <?= ArrayHelper::getValue($model, 'admin_id') == $val['id']?'selected':''?>><?=$val['username']?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <p>
                <label style="width:60px;">场景地址：</label>
                <input type="text" name="ServiceModel[address]" class=""
                       value="<?= ArrayHelper::getValue($model, 'address', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">VR地址：</label>
                <input type="text" name="ServiceModel[vrurl]" class=""
                       value="<?= ArrayHelper::getValue($model, 'vrurl', '') ?>" style="width:60%;"/>
            </p>
            <p>场景内容：</p>
            <dl>
                <dd style="width: 100%;">
                    <?= \crazydb\ueditor\UEditor::widget([
                        'name' => 'ServiceModel[content]',
                        'value' => ArrayHelper::getValue($model, 'content', ''),
                        'options' => ['style' => 'float:none;',],
                    ])?>
                </dd>
            </dl>
        </div>
        <div class="formBar">
            <ul>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">提交</button>
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
            </ul>
        </div>
    </form>
</div>

<script src="/js/ajaxfileupload.js"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $(".upload-btn").on('click', function () {
            $(this).parent().parent().find('input[type=file]').click();
        });

        //上传图片，选择文件之后执行上传
        $('.upload-input').on('change', function () {
            var name = $(this).data('name'),
                id = $(this).attr('id'),
                imgObj = $(this).parent().find('img[class=upload-btn]'),
                inputText = $('#' + name);

            $.ajaxFileUpload({
                url: '<?=Url::to(['upload/upload-file'])?>',
                secureuri: false,
                fileElementId: id,      //file标签的id
                dataType: 'json',       //返回数据的类型
                data: {type: 'poster'}, //一同上传的数据
                success: function (result, status) {
                    console.log(result);
                    //把图片替换
                    if (result.code == 200) {
                        var posterUrl = $.trim(result.data.url),
                            fullName = result.data.fullFileName;
                        imgObj.attr("src", posterUrl);
                        inputText.val(fullName);
                    }
                    else {
                        alert(result.resultDesc);
                    }
                },
                error: function (data, status, e) {
                    alert(e);
                }
            });
        });

    });
</script>
<?php $this->endBody() ?>
<?php $this->endPage() ?>


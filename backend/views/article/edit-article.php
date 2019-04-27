<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;

$cateId = ArrayHelper::getValue($model, 'cate_id');

?>
<?php $this->beginBody() ?>
<style>
    .pageFormContent .textInput {
        width: 100%;
    }
</style>
<h2 class="contentTitle">文章编辑</h2>
<div class="pageContent">
    <form method="post"
          action="<?= Url::to(['article/edit-article', 'id' => ArrayHelper::getValue($model, 'id', '')]) ?>"
          class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label style="width:60px;">文章标题：</label>
                <input type="text" name="ArticleModel[title]" class=""
                       value="<?= ArrayHelper::getValue($model, 'title', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">副标题：</label>
                <input type="text" name="ArticleModel[sub_title]" class=""
                       value="<?= ArrayHelper::getValue($model, 'sub_title', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">文章作者：</label>
                <input type="text" name="ArticleModel[author]" class=""
                       value="<?= ArrayHelper::getValue($model, 'author', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">文章来源：</label>
                <input type="text" name="ArticleModel[source]" class=""
                       value="<?= ArrayHelper::getValue($model, 'source', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">海报图片：</label>
                <input type="text" name="ArticleModel[poster_url]" id='poster-url' class=""
                       value="<?= ArrayHelper::getValue($model, 'poster_url', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">文章分类：</label>
                <select class="" name="ArticleModel[cate_id]" value="<?= ArrayHelper::getValue($model, 'cate_id') ?>">
                    <option>-- 选择文章分类 --</option>
                    <?php foreach ($cateModels as $cate): ?>
                        <option value="<?= $cate['id'] ?>" <?= $cateId == $cate['id'] ? 'selected' : '' ?>><?= $cate['name'] ?></option>
                    <?php endforeach; ?>
                </select>
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
                    <input type="text" name="ArticleModel[banner_url]" id='banner-url' class=""
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
                             src="<?= !empty($model['banner_url']) ? \Yii::$app->params['imageUrlPrefix'] . $model['poster_url'] : '/images/upload.png' ?>"/>
                    </div>
                </dd>
            </dl>

            <dl>
                <p>
                    <label style="width:60px;">文章地址：</label>
                    <input type="text" class="" readonly="readonly"
                           value="<?='https://www.moviest.com/article/article-details.html?articleId=' . ArrayHelper::getValue($model, 'id', '') ?>" style="width:400px;"/>
                </p>
            </dl>

            <dl>
                <p>
                    <label style="width:100px;">在专题里隐藏：</label>
                    <input type="checkbox" name="ArticleModel[hide_in_list]" id="hide-in-list" class=""
                           <?= (ArrayHelper::getValue($model, 'hide_in_list')==1) ? ' checked="checked" ' : ' '; ?>
                           value="<?= (ArrayHelper::getValue($model, 'hide_in_list')==1) ? 1 : 0; ?>"/>
                </p>
            </dl>

            <p>文章内容：</p>
            <dl>
                <dd style="width: 100%;">
                    <?= \crazydb\ueditor\UEditor::widget([
                        'name' => 'ArticleModel[content]',
                        'value' => ArrayHelper::getValue($model, 'content', ''),
                        'options' => ['style' => 'float:none;',],
                    ]) ?>
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
    $(function () {
        $(".upload-btn").on('click', function () {
            $(this).parent().parent().find('input[type=file]').click();
        });

        $("#hide-in-list").on('click', function () {
            $(this).val(this.checked ? 1 : 0);
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


<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;
?>
<?php $this->beginBody() ?>
<h2 class="contentTitle">文章编辑</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['topic/edit-topic','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label style="width:60px;">专题名称：</label>
                <input type="text" name="TopicModel[title]"  class="" value="<?=ArrayHelper::getValue($model,'title','')?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">专题标题：</label>
                <input type="text" name="TopicModel[sub_title]"  class="" value="<?=ArrayHelper::getValue($model,'sub_title','')?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">海报图片：</label>
                <input type="text" name="TopicModel[source]" id='poster-url'  class="" value="<?=ArrayHelper::getValue($model,'poster_url','')?>" style="width:60%;" />
            </p>
            <p>
            <dl>
                <dt></dt>
                <dd style="margin-left: 70px;">
                    <input id="fileToUpload" style="display: none" type="file" name="UploadForm[file]">
                    <div style="width:600px; height:200px;border: dashed 2px #e5e5e5;text-align: center; line-height: 200px;">
                        <img id="upload" src="<?= ! empty($model['poster_url']) ? \Yii::$app->params['imageUrlPrefix'] . $model['poster_url'] : '/images/upload.png'?>"/>
                    </div>
                </dd>
            </dl>
            <p>文章内容：</p>
            <dl>
                <dd style="width: 100%;">
                    <?= \crazydb\ueditor\UEditor::widget([
                        'name' => 'TopicModel[content]',
                        'value' => ArrayHelper::getValue($model,'content',''),
                        'options' => ['style' => 'float:none;',],
                    ]) ?>
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
<script src="/js/ajaxfileupload.js"></script>
<script type="text/javascript">
    $(function(){
        //上传图片
        $("#upload").on('click', function() {
            $('#fileToUpload').click();
        });

        //选择文件之后执行上传
        $('#fileToUpload').on('change', function() {
            $.ajaxFileUpload({
                url:'<?=Url::to(['upload/upload-file'])?>',
                secureuri:false,
                fileElementId:'fileToUpload',//file标签的id
                dataType: 'json',//返回数据的类型
                data:{type:'poster'},//一同上传的数据
                success: function (result, status) {
                    console.log(result);
                    //把图片替换
                    if(result.state == 'SUCCESS')
                    {
                        var posterUrl = $.trim(result.url);
                        $("#upload").attr("src", '<?=\Yii::$app->params['imageUrlPrefix']?>'+posterUrl);
                        $("#poster-url").val(posterUrl);
                    }
                    else
                    {
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


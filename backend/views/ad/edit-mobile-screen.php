<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;
use common\models\mysql\AdMobileScreenModel;

?>
<h2 class="contentTitle">文章编辑</h2>
<div class="pageContent">

    <form method="post" action="<?=Url::to(['ad/edit-mobile-screen','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label>跳转链接：</label>
                <input type="text" name="AdMobileScreenModel[screen_url]"  class="" value="<?=ArrayHelper::getValue($model,'screen_url','')?>" style="width:60%;"/>
            </p>
            <p>
                <label>图片 URL：</label>
                <input type="text" name="AdMobileScreenModel[screen_img]"  class="" value="<?=ArrayHelper::getValue($model,'screen_img','')?>" style="width:60%;"/>
            </p>
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
        $(".upload-btn").on('click', function() {
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


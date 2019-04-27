<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>
<h2 class="contentTitle">编辑账户信息</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['member/edit-member','id' => ArrayHelper::getValue($model, 'id', '')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label>昵称：</label>
                <input type="text" name="UserModel[nick_name]"  class="" value="<?=ArrayHelper::getValue($model,'nick_name','')?>" style="width:60%;"/>
            </p>
            <p>
                <label>电话号码：</label>
                <input type="text" name="UserModel[mobile]"  class="" value="<?=ArrayHelper::getValue($model,'mobile','')?>" style="width:60%;"/>
            </p>
            <p>
                <label>头像：</label>
                <input type="text" name="UserModel[icon_url]" id='poster-url' class="" value="<?=ArrayHelper::getValue($model,'icon_url','')?>"/>
            </p>
            <dl>
                <dt></dt>
                <dd>
                    <input id="fileToUpload"  class="upload-input" data-name="poster-url" style="display: none" type="file" name="UploadForm[file]">
                    <div style="width:600px;border: solid 1px #F4FF77;text-align: center;">
                        <img  class="upload-btn" src="<?php if(strpos($model['icon_url'],'http') !== false )  { echo $model['icon_url'];} else{ echo \Yii::$app->params['imageUrlPrefix'].$model['icon_url'];}?>" width="200px"/>
                    </div>
                </dd>
            </dl>
            <p>
                <label>密码：</label>
                <input type="password" name="UserModel[password]"  class="" value="<?=ArrayHelper::getValue($model,'password','')?>" style="width:60%;"/>
            </p>
            <p>
                <label>是推荐：</label>
                <select name="UserModel[is_push]" value="<?=ArrayHelper::getValue($model,'is_push',0)?>">
                    <option value="0">不推荐</option>
                    <option value="1" <?php if(ArrayHelper::getValue($model,'is_push') == 1):?>selected="selected"<?php endif;?>>推荐</option>
                </select>
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
<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;
use common\models\mysql\AdModel;

$positionId = ArrayHelper::getValue($model,'position_id');
$type       = ArrayHelper::getValue($model,'type');
$types      = [
    AdModel::AD_TYPE_PICTURE => '图片广告',
    AdModel::AD_TYPE_TEXT    => '文字广告',
];
$positions  = [
    AdModel::AD_POSITION_INDEX_TOP => '首页轮播',
    AdModel::AD_POSITION_FILM_TOP => '电影节部轮播图',
];
?>
<h2 class="contentTitle">文章编辑</h2>
<div class="pageContent">

    <form method="post" action="<?=Url::to(['ad/edit-ad','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <input type="hidden" name="AdModel[position_id]" value="<?=AdModel::AD_POSITION_INDEX_TOP?>">
        <input type="hidden" name="AdModel[type]" value="<?=AdModel::AD_TYPE_PICTURE?>">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label>广告标题：</label>
                <input type="text" name="AdModel[title]"  class="" value="<?=ArrayHelper::getValue($model,'title','')?>" style="width:60%;"/>
            </p>
            <p>
                <label>链接地址：</label>
                <input type="text" name="AdModel[link_url]"  class="" value="<?=ArrayHelper::getValue($model,'link_url','')?>" style="width:60%;"/>
            </p>
            <p>
                <label>按钮名：</label>
                <input type="text" name="AdModel[btn_name]"  class="" value="<?=ArrayHelper::getValue($model,'btn_name','')?>" style="width:60%;"/>
            </p>
            <p>
                <label>位置：</label>
                <select name="AdModel[position_id]">
                    <option value="">请选择</option>
                    <option value="1" <?= ArrayHelper::getValue($model, 'position_id') == '1'?'selected':''?>>首页</option>
                    <option value="2" <?= ArrayHelper::getValue($model, 'position_id') == '2'?'selected':''?>>电影节</option>
                    <option value="3" <?= ArrayHelper::getValue($model, 'position_id') == '3'?'selected':''?>>随便看看</option>
                    <option value="3" <?= ArrayHelper::getValue($model, 'position_id') == '4'?'selected':''?>>宣传发布</option>
                </select>
<!--                <input type="text" name="AdModel[position_id]"  class="" value="--><?//=ArrayHelper::getValue($model,'position_id','')?><!--" style="width:60%;"/>-->
            </p>
            <dl>
                <dt>简介：</dt>
                <dd>
                    <textarea style="width: 100%" name="AdModel[introduction]" value="<?=ArrayHelper::getValue($model,'introduction','')?>"><?=ArrayHelper::getValue($model,'introduction','')?></textarea>
                </dd>
            </dl>
            <p>
                <label>开始时间：</label>
                <input type="text" name="AdModel[start_time]" class="date"  datefmt="yyyy-MM-dd HH:mm:ss" value="<?=ArrayHelper::getValue($model,'start_time') ? date('Y-m-d H:i:s',$model['start_time']) : ''?>"/>
                <a class="inputDateButton" href="javascript:;">选择</a>
            </p>
            <p>
                <label>结束时间：</label>
                <input type="text" name="AdModel[end_time]" class="date" datefmt="yyyy-MM-dd HH:mm:ss" value="<?=ArrayHelper::getValue($model,'end_time') ? date('Y-m-d H:i:s',$model['end_time']) : ''?>"/>
                <a class="inputDateButton" href="javascript:;">选择</a>
            </p>
            <p>
                <label>广告图片：</label>
                <input type="text" name="AdModel[content]" id='poster-url' class="" value="<?=ArrayHelper::getValue($model,'content','')?>"/>
            </p>
            <dl>
                <dt></dt>
                <dd>
                    <input id="fileToUpload"  class="upload-input" data-name="poster-url" style="display: none" type="file" name="UploadForm[file]">
                    <div style="width:600px;border: solid 1px #F4FF77;text-align: center;">
                        <img  class="upload-btn" src="<?= ! empty($model['content']) ? \Yii::$app->params['imageUrlPrefix'] . $model['content'] : '/imges/upload.jpg'?>" width="200px"/>
                    </div>
                </dd>
            </dl>

            <p>
                <label>gif图片：</label>
                <input type="text" name="AdModel[gif]" id='git-url' class="" value="<?=ArrayHelper::getValue($model,'gif','')?>"/>
            </p>
            <dl>
                <dt></dt>
                <dd>
                    <input id="fileToUploads"  class="upload-input" data-name="git-url" style="display: none" type="file" name="UploadForm[file]">
                    <div style="width:600px;border: solid 1px #F4FF77;text-align: center;">
                        <img  class="upload-btn" src="<?= ! empty($model['gif']) ? \Yii::$app->params['imageUrlPrefix'] . $model['gif'] : '/imges/upload.jpg'?>" width="200px"/>
                    </div>
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


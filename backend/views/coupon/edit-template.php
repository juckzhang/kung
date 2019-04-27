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
<h2 class="contentTitle">模板编辑</h2>
<div class="pageContent">
    <form method="post"
          action="<?= Url::to(['coupon/edit-template', 'id' => ArrayHelper::getValue($model, 'id', '')]) ?>"
          class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label style="width:60px;">模板名称：</label>
                <input type="text" name="CouponTemplateModel[name]" class=""
                       value="<?= ArrayHelper::getValue($model, 'name', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label>有效时间:</label>
                <input type="text" class="form-control" style="width: 100px;"  name="CouponTemplateModel[start_time]" id="qBeginTime" value="<?=isset($model['start_time'])? date('Y-m-d',ArrayHelper::getValue($model, 'start_time', '')):'' ?>"/>
                <label style="width: 20px;">至</label>
                <input type="text" class="form-control" style="width: 100px;" name="CouponTemplateModel[end_time]" id="qEndTime" value="<?=isset($model['start_time'])? date('Y-m-d',ArrayHelper::getValue($model, 'end_time', '')):'' ?>"/>
            </p>
            <p>
                <label style="width:60px;">模板样式1(未领取)：</label>
                <input type="text" name="CouponTemplateModel[style_1]" id='style_1' class=""
                       value="<?= ArrayHelper::getValue($model, 'style_1', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">金额：</label>
                <input type="text" name="CouponTemplateModel[sum]" class=""
                       value="<?= ArrayHelper::getValue($model, 'sum', '') ?>" style="width:60%;"/>元
            </p>
            <dl>
                <dt></dt>
                <dd style="margin-left: 70px;">
                    <input id="poster-upload" class="upload-input" data-name="style_1" style="display: none" type="file"
                           name="UploadForm[file]">
                    <div style="width:600px; height:200px;border: dashed 2px #e5e5e5;text-align: center; line-height: 200px;">
                        <img id="upload" class="upload-btn" style="max-width: 600px; max-height: 200px;"
                             src="<?= !empty($model['style_1']) ? \Yii::$app->params['imageUrlPrefix'] . $model['style_1'] : '/images/upload.png' ?>"/>
                    </div>
                </dd>
            </dl>
            <p>
                <label style="width:60px;">模板样式2(已领取)：</label>
                <input type="text" name="CouponTemplateModel[style_2]" id='style_2' class=""
                       value="<?= ArrayHelper::getValue($model, 'style_2', '') ?>" style="width:60%;"/>
            </p>
            <dl>
                <dt></dt>
                <dd style="margin-left: 70px;">
                    <input id="poster-uploads" class="upload-input" data-name="style_2" style="display: none" type="file"
                           name="UploadForm[file]">
                    <div style="width:600px; height:200px;border: dashed 2px #e5e5e5;text-align: center; line-height: 200px;">
                        <img id="uploads" class="upload-btn" style="max-width: 600px; max-height: 200px;"
                             src="<?= !empty($model['style_2']) ? \Yii::$app->params['imageUrlPrefix'] . $model['style_2'] : '/images/upload.png' ?>"/>
                    </div>
                </dd>
            </dl>
            <?php if(ArrayHelper::getValue($model, 'create_time', false)){?>
                <input type="hidden" name="CouponTemplateModel[update_time]" value="<?=time()?>"/>
            <?php }else{?>
                <input type="hidden" name="CouponTemplateModel[create_time]" value="<?=time()?>"/>
            <?php }?>

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
    $('#qBeginTime').datepicker({
        format: "yyyy-mm-dd",
        //language: "zh-CN",
        todayBtn : "linked",
        autoclose : true,
        todayHighlight : true,
        //beforeShowDay: disableSpecificDays,
        //endDate : new Date()
    }).on('changeDate',function(e){
        var startTime = e.date;
        $('#qEndTime').datepicker('setStartDate',startTime);
    });

    //结束时间：
    $('#qEndTime').datepicker({
        format: "yyyy-mm-dd",
        //language: "zh-CN",
        todayBtn : "linked",
        autoclose : true,
        todayHighlight : true,
        //beforeShowDay: disableSpecificDays,
        //endDate : new Date()
    }).on('changeDate',function(e){
        var endTime = e.date;
        $('#qBeginTime').datepicker('setEndDate',endTime);
    });
</script>
<?php $this->endBody() ?>
<?php $this->endPage() ?>


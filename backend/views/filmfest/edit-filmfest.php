<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;

?>
<?php $this->beginBody() ?>
<style>
    .pageFormContent .textInput {
        width: 100%;
    }
</style>
<h2 class="contentTitle">电影节编辑</h2>
<div class="pageContent">
    <form method="post"
          action="<?= Url::to(['filmfest/edit-filmfest/', 'id' => ArrayHelper::getValue($model, 'id', '')]) ?>"
          class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label style="width:60px; font-size: x-small">电影节标题：</label>
                <input type="text" name="FilmfestModel[title]" class=""
                       value="<?= ArrayHelper::getValue($model, 'title', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">副标题：</label>
                <input type="text" name="FilmfestModel[sub_title]" class=""
                       value="<?= ArrayHelper::getValue($model, 'sub_title', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">发布者：</label>
                <input type="text" name="FilmfestModel[author]" class=""
                       value="<?= ArrayHelper::getValue($model, 'author', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label style="width:60px;">来源：</label>
                <input type="text" name="FilmfestModel[source]" class=""
                       value="<?= ArrayHelper::getValue($model, 'source', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label>有效时间:</label>
                <input type="text" class="form-control" style="width: 100px;"  name="FilmfestModel[start_time]" id="qBeginTime" value="<?=isset($model['start_time'])? date('Y-m-d',ArrayHelper::getValue($model, 'start_time', '')):'' ?>"/>
                <label style="width: 20px;">至</label>
                <input type="text" class="form-control" style="width: 100px;" name="FilmfestModel[end_time]" id="qEndTime" value="<?=isset($model['start_time'])? date('Y-m-d',ArrayHelper::getValue($model, 'end_time', '')):'' ?>"/>
            </p>
            <p>
                <label style="width:60px;">国家：</label>
                <select name="FilmfestModel[country]">
                    <option value="">请选择</option>
                    <option value="中国" <?= ArrayHelper::getValue($model, 'country') == '中国'?'selected':''?>>中国</option>
                    <option value="法国" <?= ArrayHelper::getValue($model, 'country') == '法国'?'selected':''?>>法国</option>
                    <option value="美国" <?= ArrayHelper::getValue($model, 'country') == '美国'?'selected':''?>>美国</option>
                    <option value="英国" <?= ArrayHelper::getValue($model, 'country') == '英国'?'selected':''?>>英国</option>
                    <option value="奥地利" <?= ArrayHelper::getValue($model, 'country') == '奥地利'?'selected':''?>>奥地利</option>
                    <option value="意大利" <?= ArrayHelper::getValue($model, 'country') == '意大利'?'selected':''?>>意大利</option>
                    <option value="埃及" <?= ArrayHelper::getValue($model, 'country') == '埃及'?'selected':''?>>埃及</option>
                    <option value="德国" <?= ArrayHelper::getValue($model, 'country') == '德国'?'selected':''?>>德国</option>
                    <option value="俄罗斯" <?= ArrayHelper::getValue($model, 'country') == '俄罗斯'?'selected':''?>>俄罗斯</option>
                    <option value="加拿大" <?= ArrayHelper::getValue($model, 'country') == '加拿大'?'selected':''?>>加拿大</option>
                    <option value="其他" <?= ArrayHelper::getValue($model, 'country') == '其他'?'selected':''?>>其他</option>
                </select>
            </p>
            <p>
                <label style="width:60px;">月份：</label>
                <select name="FilmfestModel[month]">
                    <option value="">请选择</option>
                    <option value="一月份" <?= ArrayHelper::getValue($model, 'month') == '一月份'?'selected':''?>>一月份</option>
                    <option value="二月份" <?= ArrayHelper::getValue($model, 'month') == '二月份'?'selected':''?>>二月份</option>
                    <option value="三月份" <?= ArrayHelper::getValue($model, 'month') == '三月份'?'selected':''?>>三月份</option>
                    <option value="四月份" <?= ArrayHelper::getValue($model, 'month') == '四月份'?'selected':''?>>四月份</option>
                    <option value="五月份" <?= ArrayHelper::getValue($model, 'month') == '五月份'?'selected':''?>>五月份</option>
                    <option value="六月份" <?= ArrayHelper::getValue($model, 'month') == '六月份'?'selected':''?>>六月份</option>
                    <option value="七月份" <?= ArrayHelper::getValue($model, 'month') == '七月份'?'selected':''?>>七月份</option>
                    <option value="八月份" <?= ArrayHelper::getValue($model, 'month') == '八月份'?'selected':''?>>八月份</option>
                    <option value="九月份" <?= ArrayHelper::getValue($model, 'month') == '九月份'?'selected':''?>>九月份</option>
                    <option value="十月份" <?= ArrayHelper::getValue($model, 'month') == '十月份'?'selected':''?>>十月份</option>
                    <option value="十一月份" <?= ArrayHelper::getValue($model, 'month') == '十一月份'?'selected':''?>>十一月份</option>
                    <option value="十二月份" <?= ArrayHelper::getValue($model, 'month') == '十二月份'?'selected':''?>>十二月份</option>
                </select>
            </p>
            <dl>
            <p>
                <label style="width:60px;">地址：</label>
                <input type="text" name="FilmfestModel[address]" id='address' class=""
                       value="<?= ArrayHelper::getValue($model, 'address', '') ?>" style="width:60%;"/>
            </p>
                <p>
                    <label style="width:60px;">报名费：</label>
                    <input type="text" name="FilmfestModel[price]" id='price' class=""
                           value="<?= ArrayHelper::getValue($model, 'price', '') ?>" style="width:60%;"/>
                </p>
            </dl>
            <p>
                <label style="width:60px;">海报图片：</label>
                <input type="text" name="FilmfestModel[poster_url]" id='poster-url' class=""
                       value="<?= ArrayHelper::getValue($model, 'poster_url', '') ?>" style="width:60%;"/>
            </p>
            <p>
                <label>是推荐：</label>
                <select name="FilmfestModel[is_push]" value="<?=ArrayHelper::getValue($model,'is_push',0)?>">
                    <option value="0">不推荐</option>
                    <option value="1" <?php if(ArrayHelper::getValue($model,'is_push') == 1):?>selected="selected"<?php endif;?>>推荐</option>
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
                    <input type="text" name="FilmfestModel[banner_url]" id='banner-url' class=""
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
                    <label style="width:60px;">网页地址：</label>
                    <input type="text" class="" readonly="readonly"
                           value="<?='https://www.moviest.com/filmfest/filmfest-details.html?filmfestId=' . ArrayHelper::getValue($model, 'id', '') ?>" style="width:400px;"/>
                </p>
            </dl>

            <p>文章内容：</p>
            <dl>
                <dd style="width: 100%;">
                    <?= \crazydb\ueditor\UEditor::widget([
                        'name' => 'FilmfestModel[content]',
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
    });
</script>
<?php $this->endBody() ?>
<?php $this->endPage() ?>


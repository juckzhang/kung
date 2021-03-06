<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;
use backend\services\MediaService;

$param = \Yii::$app->request->getPost();
$mediaService = MediaService::getService();
$defaultSourceType = ArrayHelper::getValue($param,'source_type', 1);
$sourceType = ArrayHelper::getValue($model, 'source_type', $defaultSourceType);
$categories   = $mediaService->categories($sourceType);
?>
<h2 class="contentTitle">资源编辑</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['media/edit-media','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <input type="hidden" name="MediaModel[source_type]" value="<?=$sourceType?>">
            <p>
                <label>中文标题：</label>
                <input type="text" size="60" name="MediaModel[title]" value="<?=ArrayHelper::getValue($model,'title')?>">
            </p>
            <p>
                <label>英文标题：</label>
                <input type="text" size="60" name="MediaModel[title_en]" value="<?=ArrayHelper::getValue($model,'title_en')?>">
            </p>
            <p>
                <label>播放链接：</label>
                <input class="link" type="text"  size="60" name="MediaModel[play_link]" value="<?=ArrayHelper::getValue($model,'play_link')?>">
            </p>
            <p>
                <label>下载链接：</label>
                <input type="text" class="link" size="60" name="MediaModel[download_link]" value="<?=ArrayHelper::getValue($model,'download_link')?>">
            </p>
            <p>
                <label>播放量：</label>
                <input type="text" name="MediaModel[play_num]" value="<?=ArrayHelper::getValue($model,'play_num',50)?>">
            </p>
            <p>
                <label>下载量：</label>
                <input type="text" name="MediaModel[download_num]" value="<?=ArrayHelper::getValue($model,'download_num',10)?>">
            </p>
            <p>
                <label>收藏量：</label>
                <input type="text" name="MediaModel[collection_num]" value="<?=ArrayHelper::getValue($model,'collection_num',10)?>">
            </p>
            <p>
                <label>播放总时长：</label>
                <input type="text" placeholder="格式: 00:00:00" name="MediaModel[total_time]" value="<?=ArrayHelper::getValue($model,'total_time')?>">
            </p>
            <p>
                <label>资源分类：</label>
                <select name="MediaModel[cate_id]" value="<?=ArrayHelper::getValue($model, 'cate_id')?>">
                    <?php foreach($categories as $category):?>
                        <option value="<?=$category['id']?>" <?=ArrayHelper::getValue($model, 'cate_id') == $category['id'] ? 'selected' : ''?>><?=$category['name']?></option>
                        <?php foreach($category['child'] as $child):?>
                            <option value="<?=$child['id']?>" <?=ArrayHelper::getValue($model, 'cate_id') == $child['id'] ? 'selected' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;<?=$child['name']?></option>
                        <?php endforeach;?>
                    <?php endforeach;?>
                </select>
            </p>
            <p>
                <label>语言类型：</label>
                <select name="MediaModel[lang_type]" value="<?=ArrayHelper::getValue($model, 'lang_type')?>">
                    <option value="zh_CN" selected="selected">中文</option>
                    <option value="en_US" <?=ArrayHelper::getValue($model, 'lang_type') == 'en_US' ? 'selected' : ''?>>英语</option>
                </select>
            </p>
            <p>
                <label>是否首页推荐：</label>
                <select name="MediaModel[is_recommend]" value="<?=ArrayHelper::getValue($model,'is_recommend',0)?>">
                    <option value="0">不推荐</option>
                    <option value="1" <?php if(ArrayHelper::getValue($model,'is_recommend') == 1):?>selected="selected"<?php endif;?>>推荐</option>
                </select>
            </p>
            <p>
                <label>等级：</label>
                <select name="MediaModel[level]" value="<?=ArrayHelper::getValue($model,'level',1)?>">
                    <option value="1" <?php if(ArrayHelper::getValue($model,'level') == 1): ?> selected="selected"<?php endif;?>>初级</option>
                    <option value="2" <?php if(ArrayHelper::getValue($model,'level') == 2): ?> selected="selected"<?php endif;?>>中级</option>
                    <option value="3" <?php if(ArrayHelper::getValue($model,'level') == 3): ?> selected="selected"<?php endif;?>>高级</option>
                </select>
            </p>

            <label>描述(参考文本):</label>
            <textarea style="width: 634px;height: 94px;" type="text" name="MediaModel[media_desc]" value="<?=ArrayHelper::getValue($model,'media_desc','')?>"><?=ArrayHelper::getValue($model, 'media_desc', '')?></textarea>

            <p>
                <label>海报图片：</label>
                <input type="text" name="MediaModel[poster_url]" class='poster-url' value="<?=ArrayHelper::getValue($model,'poster_url','')?>"/>
            </p>
            <p>
                <?php if($sourceType != 3):?>
                    <label>字幕：</label>
                    <input type="text" name="lines" class='lines' value=""/>
                <?php endif;?>
            </p>
            <p>
                <label>&nbsp;</label>
                <input id="poster-url" size="60" class="upload-input" data-name="poster-url" style="display: none" type="file" data-type="picture" name="UploadForm[file]">
                <img id="upload" class="upload-btn" src="<?= ! empty($model['poster_url']) ? \Yii::$app->params['imageUrlPrefix'] .$model['poster_url'] : '/images/upload.png'?>" width="100px"/>
            </p>
            <p>
                <label><?= $sourceType == 3 ? 'PDF文档上传:' : '&nbsp;';?></label>
                <input id="pdf" size="60" class="upload-input" data-type="pdf" data-name="<?=$sourceType == 3 ? 'link' : 'lines';?>" style="display: none" type="file" name="UploadForm[file]">
                <img id="upload" class="upload-btn" src="<?= ! empty($model['play_link']) ? \Yii::$app->params['imageUrlPrefix'] .$model['play_link'] : '/images/upload.png'?>" width="100px"/>
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
        $(".upload-btn").on('click', function() {
            $(this).parent().find('input[type=file]').click();
        });

        //上传图片
        //选择文件之后执行上传
        $('.upload-input').on('change',function(){
            var name      = $(this).data('name'),
                type      = $(this).data('type'),
                id        = $(this).attr('id'),
                imgObj    = $(this).parent().find('img[class=upload-btn]'),
                inputText = $('.'+name);

            $.ajaxFileUpload({
                url:'<?=Url::to(['upload/upload-file'])?>',
                secureuri:false,
                fileElementId:id,//file标签的id
                dataType: 'json',//返回数据的类型
                data:{type: type},//一同上传的数据
                success: function (result, status) {
                    //把图片替换
                    if(result.code == 200){
                        var posterUrl = $.trim(result.data.url),
                            fullName  = result.data.fullFileName;
                        imgObj.attr("src", posterUrl);
                        inputText.val(fullName);
                    }else {
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
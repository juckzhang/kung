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
<h2 class="contentTitle">媒体编辑</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['media/edit-media','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <input type="hidden" name="mediaModel[source_type]" value="<?=$sourceType?>">
            <p>
                <label>名称：</label>
                <input type="text" name="mediaModel[title]" value="<?=ArrayHelper::getValue($model,'title')?>">
            </p>
            <p>
                <label>副标题：</label>
                <input id="vu" type="text" name="mediaModel[sub_title]" value="<?=ArrayHelper::getValue($model,'sub_title')?>">
            </p>
            <p>
                <label>播放连接：</label>
                <input id="media_id" type="text" name="mediaModel[play_link]" value="<?=ArrayHelper::getValue($model,'play_link')?>">
            </p>
            <p>
                <label>下载链接：</label>
                <input type="text" name="mediaModel[download_link]" value="<?=ArrayHelper::getValue($model,'download_link')?>">
            </p>
            <p>
                <label>播放量：</label>
                <input type="text" name="mediaModel[play_num]" value="<?=ArrayHelper::getValue($model,'play_num',0)?>">
            </p>
            <p>
                <label>下载量：</label>
                <input type="text" name="mediaModel[download_num]" value="<?=ArrayHelper::getValue($model,'download_num',0)?>">
            </p>
            <p>
                <label>收藏量：</label>
                <input type="text" name="mediaModel[collection_num]" value="<?=ArrayHelper::getValue($model,'collection_num',0)?>">
            </p>
            <p>
                <label>播放时长：</label>
                <input type="text" name="mediaModel[total_time]" value="<?=ArrayHelper::getValue($model,'total_time')?>">
            </p>
            <div class="album">
                <p>
                    <label>媒体分类：</label>
                    <select name="mediaModel[cate_id]" value="<?=ArrayHelper::getValue($model, 'cate_id')?>">
                        <option selected>-- 请选择分类 --</option>
                        <?php foreach($categories as $category):?>
                            <option value="<?=$category['id']?>" <?=ArrayHelper::getValue($model, 'cate_id') == $category['id'] ? 'selected' : ''?>><?=$category['name']?></option>
                            <?php foreach($category['child'] as $child):?>
                                <option value="<?=$child['id']?>" <?=ArrayHelper::getValue($model, 'cate_id') == $child['id'] ? 'selected' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;<?=$child['name']?></option>
                            <?php endforeach;?>
                        <?php endforeach;?>
                    </select>
                </p>
                <p>
                    <label>是否首页推荐：</label>
                    <select name="mediaModel[is_recommend]" value="<?=ArrayHelper::getValue($model,'is_recommend',0)?>">
                        <option value="0">不推荐</option>
                        <option value="1" <?php if(ArrayHelper::getValue($album,'is_recommend') == 1):?>selected="selected"<?php endif;?>>推荐</option>
                    </select>
                </p>
                <p>
                <dl>
                    <dt>简介：</dt>
                    <dd>
                        <textarea style="width: 640px;height: 200px;" name="mediaModel[desc]" value="<?=ArrayHelper::getValue($model,'desc','')?>"><?=ArrayHelper::getValue($album,'introduction','')?></textarea>
                    </dd>
                </dl>
                <p>
                    <label>海报图片：</label>
                    <input type="text" name="mediaModel[poster_url]" class='wide-poster' value="<?=ArrayHelper::getValue($album,'poster_url','')?>"/>
                </p>
                <p></p>
                <p>
                    <label>&nbsp;</label>
                    <input id="poster-url" class="upload-input" data-name="poster-url" style="display: none" type="file" name="UploadForm[file]">
                    <img id="upload" class="upload-btn" src="<?= ! empty($album['poster_url']) ? \Yii::$app->params['imageUrlPrefix'] . $album['poster_url'] : '/images/upload.png'?>" width="100px"/>
                </p>
            </div>
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
                id        = $(this).attr('id'),
                imgObj    = $(this).parent().find('img[class=upload-btn]'),
                inputText = $('.'+name);

            console.log(id);
            $.ajaxFileUpload({
                url:'<?=Url::to(['upload/upload-file'])?>',
                secureuri:false,
                fileElementId:id,//file标签的id
                dataType: 'json',//返回数据的类型
                data:{type:'poster'},//一同上传的数据
                success: function (result, status) {
                    console.log(result);
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
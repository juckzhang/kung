<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;
use backend\services\VideoService;

$videoService = VideoService::getService();
$params       = \Yii::$app->request->getPost();

$cateId       = ArrayHelper::getValue($model,'cate_id');
$categories   = $videoService->categories();
?>
<h2 class="contentTitle">视频专辑编辑</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['video/edit-album','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label>视频分类：</label>
                <select name="VideoAlbumModel[cate_id]" value="<?=$cateId?>">
                    <option selected>-- 请选择分类 --</option>
                    <?php foreach($categories as $category):?>
                        <option value="<?=$category['id']?>" <?=$cateId == $category['id'] ? 'selected' : ''?>><?=$category['name']?></option>
                        <?php foreach($category['child'] as $child):?>
                            <option value="<?=$child['id']?>" <?=$cateId == $child['id'] ? 'selected' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;<?=$child['name']?></option>
                        <?php endforeach;?>
                    <?php endforeach;?>
                </select>
            </p>
            <p>
                <label>专辑名称：</label>
                <input type="text" name="VideoAlbumModel[name]" value="<?=ArrayHelper::getValue($model,'name')?>">
                <!-- 是否是专辑 -->
                <input type="hidden" name="VideoAlbumModel[is_album]" value="1">
                <input type="hidden" name="VideoAlbumModel[has_video]" value="1">

            </p>
            <p>
                <label>导演：</label>
                <input type="text" name="VideoAlbumModel[director]" value="<?=ArrayHelper::getValue($model,'director')?>">
            </p>
            <p>
                <label>演员：</label>
                <input type="text" name="VideoAlbumModel[actor]" value="<?=ArrayHelper::getValue($model,'actor')?>">
            </p>
            <p>
                <label>地区：</label>
                <input type="text" name="VideoAlbumModel[area]" value="<?=ArrayHelper::getValue($model,'area')?>">
            </p>
            <p>
                <label>发行年份：</label>
                <input type="text" name="VideoAlbumModel[release_time]" value="<?=ArrayHelper::getValue($model,'release_time')?>">
            </p>
            <p>
                <label>是否首页推荐：</label>
                <select name="VideoAlbumModel[is_recommend]" value="0">
                    <option value="0" selected="selected">不推荐</option>
                    <option value="1">推荐</option>
                </select>
            </p>
            <dl>
                <dt>简介：</dt>
                <dd>
                    <textarea style="width: 510px;" name="VideoAlbumModel[introduction]" value="<?=ArrayHelper::getValue($model,'introduction','')?>"><?=ArrayHelper::getValue($model,'introduction','')?></textarea>
                </dd>
            </dl>
            <p>
                <label>跟新进度：</label>
                <input type="text" name="VideoAlbumModel[progress]" value="<?=ArrayHelper::getValue($model,'progress')?>">
            </p>
            <p>
                <label>竖版海报图片：</label>
                <input type="text" name="VideoAlbumModel[poster_url]" class='poster-url' value="<?=ArrayHelper::getValue($model,'poster_url','')?>"/>
            </p>
            <p>
                <label>横板海报图片：</label>
                <input type="text" name="VideoAlbumModel[wide_poster]" class='wide-poster' value="<?=ArrayHelper::getValue($model,'wide_poster','')?>"/>
            </p>
            <p>
                <label>&nbsp;</label>
                <input id="poster-url" class="upload-input" data-name="poster-url" style="display: none" type="file" name="UploadForm[file]">
                <img id="upload" class="upload-btn" src="<?= ! empty($model['poster_url']) ? \Yii::$app->params['imageUrlPrefix'] . $model['poster_url'] : '/images/upload.png'?>" width="100px"/>
            </p>
            <p>
                <label>&nbsp;</label>
                <input id="wide-poster" class="upload-input" data-name="wide-poster" style="display: none" type="file" name="UploadForm[file]">
                <img id="upload" class="upload-btn" src="<?= ! empty($model['wide_poster']) ? \Yii::$app->params['imageUrlPrefix'] . $model['wide_poster'] : '/images/upload.png'?>" width="100px"/>
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
            $(this).parent().find('input[type=file]').click();
//            $('#fileToUpload').click();
        });

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
                    if(result.code == 200)
                    {
                        var posterUrl = $.trim(result.data.url),
                            fullName  = result.data.fullFileName;
                        imgObj.attr("src", posterUrl);
                        inputText.val(fullName);
//                        $("#upload").attr("src", '<//=\Yii::$app->params['imageUrlPrefix']?>//'+posterUrl);
//                        $("#poster-url").val(posterUrl);
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


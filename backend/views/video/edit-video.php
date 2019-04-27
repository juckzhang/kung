<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;
use backend\services\VideoService;

$videoService = VideoService::getService();
$cateId       = ArrayHelper::getValue($album,'cate_id');
$categories   = $videoService->categories();
$user = $videoService->getUser();
$isAlbum      = ArrayHelper::getValue($album,'is_album');
$user_id      = ArrayHelper::getValue($album,'user_id');
?>
<h2 class="contentTitle">视频编辑</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['video/edit-video','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label>专辑：</label>
                <input type="hidden" id='album-id' name="VideoModel[album_id]" data-name="album.id" value="<?=ArrayHelper::getValue($model,'album_id')?>">
                <input class="textInput valid" name="album-name" data-name="album.name" value="<?=ArrayHelper::getValue($model,'album_id')?>" readonly="true" postfield="keyword" suggestfields="name" lookupgroup="album" autocomplete="off" type="text">
                <a class="btnLook" href="<?=Url::to(['video/album-list','search' => 1])?>" lookupgroup="album">查找带回</a>
            </p>
            <p>
                <label>视频名称：</label>
                <input type="text" name="VideoModel[title]" value="<?=ArrayHelper::getValue($model,'title')?>">
            </p>
            <p>
                <label>唯一标识符号：</label>
                <input id="vu" type="text" name="VideoModel[video_unique]" value="<?=ArrayHelper::getValue($model,'video_unique')?>">
            </p>
            <p>
                <label>乐视用户凭证：</label>
                <input id="uu" type="text" name="VideoModel[leshi_user]" value="<?=ArrayHelper::getValue($model,'leshi_user')?>">
            </p>
            <p>
                <label>视频id：</label>
                <input id="video_id" type="text" name="VideoModel[video_id]" value="<?=ArrayHelper::getValue($model,'video_id')?>">
            </p>
            <p>
                <label>视频链接：</label>
                <input type="text" name="VideoModel[video_link]" value="<?=ArrayHelper::getValue($model,'video_link')?>">
            </p>
            <p>
                <label>点击量：</label>
                <input type="text" name="VideoModel[click_num]" value="<?=ArrayHelper::getValue($model,'click_num',0)?>">
            </p>
            <p>
                <label>所属用户：</label>
                <select name="VideoAlbumModel[user_id]">
                    <option selected>-- 请选择用户 --</option>
                    <?php foreach($user as $u):?>
                        <option value="<?=$u['id']?>" <?=$user_id == $u['id'] ? 'selected' : ''?>><?=$u['nick_name']?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <!-- 专辑信息 -->
            <div class="album">
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
                    <input type="text" name="VideoAlbumModel[name]" value="<?=ArrayHelper::getValue($album,'name')?>">
                    <!-- 专辑id -->
                    <input type="hidden" name="albumId" value="<?=ArrayHelper::getValue($album,'id')?>">
                    <input type="hidden" name="VideoAlbumModel[has_video]" value=1>
                </p>
                <p>
                    <label>导演：</label>
                    <input type="text" name="VideoAlbumModel[director]" value="<?=ArrayHelper::getValue($album,'director')?>">
                </p>
                <p>
                    <label>演员：</label>
                    <input type="text" name="VideoAlbumModel[actor]" value="<?=ArrayHelper::getValue($album,'actor')?>">
                </p>
                <p>
                    <label>地区：</label>
                    <select name="VideoAlbumModel[area]">
                        <?php $areas = ["CN", "AL", "DZ", "AF", "AR", "AE", "AW", "OM", "AZ", "EG", "ET", "IE", "EE", "AD", "AO", "AI", "AG", "AT", "AX", "AU", "MO", "BB", "PG", "BS", "PK", "PY", "PS", "BH", "PA", "BR", "BY", "BM", "BG", "MP", "BJ", "BE", "IS", "PR", "PL", "BA", "BO", "BZ", "BW", "BQ", "BT", "BF", "BI", "BV", "KP", "GQ", "DK", "DE", "TL", "TG", "DO", "DM", "RU", "EC", "ER", "FR", "FO", "PF", "GF", "TF", "VA", "PH", "FJ", "FI", "CV", "FK", "GM", "CD", "CG", "CO", "CR", "GG", "GD", "GL", "GE", "CU", "GP", "GU", "GY", "KZ", "HT", "KR", "NL", "HM", "ME", "HN", "KI", "DJ", "KG", "GN", "GW", "CA", "GH", "GA", "KH", "CZ", "ZW", "CM", "QA", "KY", "CC", "KM", "CI", "KW", "HR", "KE", "CK", "CW", "LV", "LS", "LA", "LB", "LT", "LR", "LY", "LI", "RE", "LU", "RW", "RO", "MG", "IM", "MV", "MT", "MW", "MY", "ML", "MK", "MH", "MQ", "YT", "MU", "MR", "US", "AS", "UM", "VI", "MN", "MS", "BD", "PE", "FM", "MM", "MD", "MA", "MC", "MZ", "MX", "NA", "ZA", "AQ", "GS", "NR", "NP", "NI", "NE", "NG", "NU", "NO", "NF", "PW", "PN", "PT", "JP", "SE", "CH", "SV", "WS", "RS", "SL", "SN", "CY", "SC", "XS", "SA", "BL", "CX", "ST", "SH", "KN", "LC", "MF", "SX", "SM", "PM", "VC", "XE", "LK", "SK", "SI", "SZ", "SD", "SR", "SB", "SO", "TJ", "TW", "TH", "TZ", "TO", "TC", "TT", "TN", "TV", "TR", "TM", "TK", "WF", "VU", "GT", "VG", "VE", "BN", "UG", "UA", "UY", "UZ", "ES", "GR", "HK", "SG", "NC", "NZ", "HU", "SY", "JM", "AM", "SJ", "YE", "IQ", "IR", "IL", "IT", "IN", "ID", "UK", "IO", "JO", "VN", "ZM", "JE", "TD", "GI", "CL", "CF",];?>
                        <?php $area_names = ["中国", "阿尔巴尼亚", "阿尔及利亚", "阿富汗", "阿根廷", "阿拉伯联合酋长国", "阿鲁巴", "阿曼", "阿塞拜疆", "埃及", "埃塞俄比亚", "爱尔兰", "爱沙尼亚", "安道尔", "安哥拉", "安圭拉岛", "安提瓜和巴布达", "奥地利", "奥兰岛", "澳大利亚", "澳门特别行政区", "巴巴多斯", "巴布亚新几内亚", "巴哈马", "巴基斯坦", "巴拉圭", "巴勒斯坦民族权力机构", "巴林", "巴拿马", "巴西", "白俄罗斯", "百慕大群岛", "保加利亚", "北马里亚纳群岛", "贝宁", "比利时", "冰岛", "波多黎各", "波兰", "波斯尼亚和黑塞哥维那", "玻利维亚", "伯利兹", "博茨瓦纳", "博内尔", "不丹", "布基纳法索", "布隆迪", "布韦岛", "朝鲜", "赤道几内亚", "丹麦", "德国", "东帝汶", "多哥", "多米尼加共和国", "多米尼克", "俄罗斯", "厄瓜多尔", "厄立特里亚", "法国", "法罗群岛", "法属波利尼西亚", "法属圭亚那", "法属南极地区", "梵蒂冈城", "菲律宾", "斐济群岛", "芬兰", "佛得角", "福克兰群岛(马尔维纳斯群岛)", "冈比亚", "刚果(DRC)", "刚果共和国", "哥伦比亚", "哥斯达黎加", "格恩西岛", "格林纳达", "格陵兰", "格鲁吉亚", "古巴", "瓜德罗普岛", "关岛", "圭亚那", "哈萨克斯坦", "海地", "韩国", "荷兰", "赫德和麦克唐纳群岛", "黑山共和国", "洪都拉斯", "基里巴斯", "吉布提", "吉尔吉斯斯坦", "几内亚", "几内亚比绍", "加拿大", "加纳", "加蓬", "柬埔寨", "捷克共和国", "津巴布韦", "喀麦隆", "卡塔尔", "开曼群岛", "科科斯群岛(基灵群岛)", "科摩罗联盟", "科特迪瓦共和国", "科威特", "克罗地亚", "肯尼亚", "库可群岛", "库拉索", "拉脱维亚", "莱索托", "老挝", "黎巴嫩", "立陶宛", "利比里亚", "利比亚", "列支敦士登", "留尼汪岛", "卢森堡", "卢旺达", "罗马尼亚", "马达加斯加", "马恩岛", "马尔代夫", "马耳他", "马拉维", "马来西亚", "马里", "马其顿, 前南斯拉夫共和国", "马绍尔群岛", "马提尼克岛", "马约特岛", "毛里求斯", "毛利塔尼亚", "美国", "美属萨摩亚", "美属外岛", "美属维尔京群岛", "蒙古", "蒙特塞拉特", "孟加拉国", "秘鲁", "密克罗尼西亚", "缅甸", "摩尔多瓦", "摩洛哥", "摩纳哥", "莫桑比克", "墨西哥", "纳米比亚", "南非", "南极洲", "南乔治亚和南德桑威奇群岛", "瑙鲁", "尼泊尔", "尼加拉瓜", "尼日尔", "尼日利亚", "纽埃", "挪威", "诺福克岛", "帕劳群岛", "皮特凯恩群岛", "葡萄牙", "日本", "瑞典", "瑞士", "萨尔瓦多", "萨摩亚", "塞尔维亚共和国", "塞拉利昂", "塞内加尔", "塞浦路斯", "塞舌尔", "沙巴岛", "沙特阿拉伯", "圣巴泰勒米岛", "圣诞岛", "圣多美和普林西比", "圣赫勒拿岛", "圣基茨和尼维斯", "圣卢西亚", "法属圣马丁岛", "荷属圣马丁岛", "圣马力诺", "圣皮埃尔岛和密克隆岛", "圣文森特和格林纳丁斯", "圣尤斯特歇斯岛", "斯里兰卡", "斯洛伐克", "斯洛文尼亚", "斯威士兰", "苏丹", "苏里南", "所罗门群岛", "索马里", "塔吉克斯坦", "台湾", "泰国", "坦桑尼亚", "汤加", "特克斯和凯科斯群岛", "特立尼达和多巴哥", "突尼斯", "图瓦卢", "土耳其", "土库曼斯坦", "托克劳", "瓦利斯和富图纳", "瓦努阿图", "危地马拉", "维尔京群岛(英属)", "委内瑞拉", "文莱", "乌干达", "乌克兰", "乌拉圭", "乌兹别克斯坦", "西班牙", "希腊", "香港特别行政区", "新加坡", "新喀里多尼亚", "新西兰", "匈牙利", "叙利亚", "牙买加", "亚美尼亚", "扬马延岛", "也门", "伊拉克", "伊朗", "以色列", "意大利", "印度", "印度尼西亚", "英国", "英属印度洋领地", "约旦", "越南", "赞比亚", "泽西", "乍得", "直布罗陀", "智利", "中非共和国",];?>
                        <?php for($i = 0; $i < count($areas); $i++):?>
                            <option value="<?=$areas[$i]?>" <?=ArrayHelper::getValue($album, 'area') === $areas[$i] ? 'selected="selected"' : ''?>><?=$area_names[$i]?></option>
                        <?php endfor;?>
                    </select>
                </p>
                <p>
                    <label>发行年份：</label>
                    <select name="VideoAlbumModel[release_time]">
                        <?php for($i = date('Y') ; $i >= 1970; --$i):?>
                            <option value="<?=$i?>" <?=ArrayHelper::getValue($album,'release_time',1970) == $i ? 'selected="selected"' : ''?>><?=$i?></option>
                        <?php endfor;?>
                    </select>
<!--                    <input type="text" name="VideoAlbumModel[release_time]" value="<=ArrayHelper::getValue($album,'release_time')?>">-->
                </p>
<!--                <p>-->
<!--                    <label>是否轮播推荐：</label>-->
<!--                    <select name="VideoAlbumModel[is_carousel]" value="0">-->
<!--                        <option value="0" selected="selected">不推荐</option>-->
<!--                        <option value="1" <php if(ArrayHelper::getValue($album,'is_carousel') == 1):?> selected="selected"<php endif;?>>推荐</option>-->
<!--                    </select>-->
<!--                </p>-->
                <p>
                    <label>是否楼层推荐：</label>
                    <select name="VideoAlbumModel[is_recommend]" value="<?=ArrayHelper::getValue($album,'is_recommend',0)?>">
                        <option value="0">不推荐</option>
                        <option value="1" <?php if(ArrayHelper::getValue($album,'is_recommend') == 1):?>selected="selected"<?php endif;?>>推荐</option>
                    </select>
                </p>
                <p>
                    <label>是否最热推荐：</label>
                    <select name="VideoAlbumModel[is_hot]" value="<?=ArrayHelper::getValue($album,'is_hot',0)?>"">
                        <option value="0">不推荐</option>
                        <option value="1" <?php if(ArrayHelper::getValue($album,'is_hot') == 1):?>selected="selected"<?php endif;?>>推荐</option>
                    </select>
                </p>
                <p>
                    <label>是否最新推荐：</label>
                    <select name="VideoAlbumModel[is_new]" value="<?=ArrayHelper::getValue($album,'is_new',0)?>"">
                        <option value="0">不推荐</option>
                        <option value="1" <?php if(ArrayHelper::getValue($album,'is_new') == 1):?>selected="selected"<?php endif;?>>推荐</option>
                    </select>
                </p>
                <p>
                    <label>一句话简介：</label>
                    <input type="text" name="VideoAlbumModel[aword]" value="<?=ArrayHelper::getValue($album,'aword')?>">
                </p>
                <p>
                    <label>视频标签：</label>
                    <input type="text" name="VideoModel[tags]" value="<?=ArrayHelper::getValue($model,'tags')?>">
                </p>
                <p>
                    <label>播放时长：</label>
                    <input type="text" name="VideoModel[play_time]" value="<?=ArrayHelper::getValue($model,'play_time')?>">
                </p>
                <dl>
                    <dt>在 App 内隐藏：</dt>
                    <dd>
                        <select name="VideoAlbumModel[api_hidden]" value="<?=ArrayHelper::getValue($album,'api_hidden',0)?>"">
                            <option value="0">不隐藏</option>
                            <option value="1" <?php if(ArrayHelper::getValue($album,'api_hidden') == 1):?>selected="selected"<?php endif;?>>隐藏</option>
                        </select>
                    </dd>
                </dl>
                <p>
                <dl>
                    <dt>简介：</dt>
                    <dd>
                        <textarea style="width: 510px;" name="VideoAlbumModel[introduction]" value="<?=ArrayHelper::getValue($album,'introduction','')?>"><?=ArrayHelper::getValue($album,'introduction','')?></textarea>
                    </dd>
                </dl>
                <p>
                    <label>横板海报图片：</label>
                    <input type="text" name="VideoAlbumModel[wide_poster]" class='wide-poster' value="<?=ArrayHelper::getValue($album,'wide_poster','')?>"/>
                </p>
                <p>
                    <label>竖版海报图片：</label>
                    <input type="text" name="VideoAlbumModel[poster_url]" class='poster-url' value="<?=ArrayHelper::getValue($album,'poster_url','')?>"/>
                </p>
                <p>
                    <label>&nbsp;</label>
                    <input id="wide-poster" class="upload-input" data-name="wide-poster" style="display: none" type="file" name="UploadForm[file]">
                    <img id="upload" class="upload-btn" src="<?= ! empty($album['wide_poster']) ? \Yii::$app->params['imageUrlPrefix'] . $album['wide_poster'] : '/images/upload.png'?>" width="100px"/>
                </p>
                <p>
                    <label>&nbsp;</label>
                    <input id="poster-url" class="upload-input" data-name="poster-url" style="display: none" type="file" name="UploadForm[file]">
                    <img id="upload" class="upload-btn" src="<?= ! empty($album['poster_url']) ? \Yii::$app->params['imageUrlPrefix'] . $album['poster_url'] : '/images/upload.png'?>" width="100px"/>
                </p>

                <dl style="margin-top:100px;">
                    <dt>JS：</dt>
                    <dd>
                        <textarea id="link-url" style="width: 510px; height: 140px;" class="textInput valid"></textarea>
                    </dd>
                    <label>平台账号类型：</label>
                    <select id="service-select" name="VideoModel[service_num]" value="<?=ArrayHelper::getValue($model,'service_num',0)?>"">
                        <option value="0" <?php if(ArrayHelper::getValue($model, 'service_num') == 0): ?>selected="selected"<?php endif;?>>账号 lige@moviest.com</option>
                        <option value="1" <?php if(ArrayHelper::getValue($model, 'service_num') == 1): ?>selected="selected"<?php endif;?>>属于发型平台</option>
                        <option value="2" <?php if(ArrayHelper::getValue($model, 'service_num') == 2): ?>selected="selected"<?php endif;?>>账号 lige@mmbang.net</option>
                    </select>
                </dl>
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
    var album = $('#album-id').val();
    $(function(){
        $('#service-select').change(function () {
            var selectVal = $(this).children('option:selected').val();
            var leishiUser = '';
            if (selectVal == 0) {
                leishiUser = 'fvaz7udkhj';
            } else if (selectVal == 2) {
                leishiUser = '624310fa63';
            } else {
                leishiUser = '';
            }
            $('#uu').val(leishiUser);
        });
        //监听album-id
//        var interval = setInterval(function(){
//            //判断值是否变化
//            var albumId = $('#album-id').val();
//            if(album != albumId)
//            {
//                album = albumId;
//                //隐藏专辑编辑框
//                $('.album').hide();
//                //删除定时器
//                clearInterval(interval);
//            }
//        },1000);

        /*
         * 关闭发型平台加片的功能
        $('#is_vrp').change(function() {
            var is_vrp = $('#is_vrp');
            var checked = is_vrp.is(':checked');
            if (checked) {
                is_vrp.val(1);
                var vu = $('#vu').val();
                $('#video_id').val(vu?vu:100);
            } else {
                is_vrp.val(0);
                $('#video_id').val(null);
            }
        });

        $('#link-url').on('change input porpertychange', function() {
            var linkUrl = $(this).val();

            var uuReg = /uu:(\d+)/;
            var uu = linkUrl.match(uuReg);
            $('#uu').val(uu[1]);

            var vuReg = /vu:(\d+)/;
            var vu = linkUrl.match(vuReg);
            $('#vu').val(vu[1]);
        });
        */

        $(".upload-btn").on('click', function() {
            $(this).parent().find('input[type=file]').click();
//            $('#fileToUpload').click();
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
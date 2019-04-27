<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\VideoService;
use common\helpers\UrlHelper;

$videoService = VideoService::getService();
$params = \Yii::$app->request->getPost();
$page = ArrayHelper::getValue($params, 'pageNum', '1');
$orderFiled = ArrayHelper::getValue($params, 'orderFiled', '');
$orderDirection = ArrayHelper::getValue($params, 'orderDirection', 'desc');
$prePage = ArrayHelper::getValue($params, 'numPerPage', '20');

//查询条件
$other = ArrayHelper::getValue($params, 'other');
$category = ArrayHelper::getValue($other, 'category');

$album = ArrayHelper::getValue($params, 'album');


$categories = $videoService->categories();

?>
<div class="" id="video-list" rel="video-list">
    <form id="pagerForm" method="post" action="#rel#">
        <input type="hidden" name="pageNum" value="<?= $page ?>"/>
        <input type="hidden" name="numPerPage" value="<?= $prePage ?>"/>
        <input type="hidden" name="orderField" value="<?= $orderFiled ?>"/>
        <input type="hidden" name="orderDirection" value="<?= $orderDirection ?>"/>
    </form>
    <div class="pageHeader">
        <form id = "adrelform" rel="pagerForm" onsubmit="return dialogSearch(this);" action="<?= Url::to(['video/addvideo-list']) ?>"
              method="post">
            <input type="hidden" name="album" value="<?= $album ?>"/>
            <div class="searchBar">
                <table class="searchContent">
                    <tbody>
                    <tr>
                        <td>分类筛选：
                            <select name="category">
                                <option value="" <?=$categorys?'':'selected'?>>-- 请选择分类 --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?=($category['id']==$categorys)? "selected":''?>><?= $category['name'] ?></option>
                                    <?php foreach ($category['child'] as $child): ?>
                                        <option value="<?= $child['id'] ?>" <?=($child['id']==$categorys)? "selected":''?>>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<?= $child['name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>

                        <td>推荐筛选
                            <select name="recommend">
                                <option value=""  <?= $recommends===false ?'':'selected'?>>-- 全部 --</option>
                                <option value="1" <?= $recommends?'selected':''?>>推荐的</option>
                                <option value="0" <?= $recommends?'':'selected'?>>不推荐的</option>
                            </select>
                        </td>
                        <td>关键词：<input name="keyword" class="textInput" type="text" alt=""
                                       value="<?= ArrayHelper::getValue($params, 'keyword') ?>"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="subBar">
                    <ul>
                        <li>
                            <div class="buttonActive">
                                <div class="buttonContent">
                                    <button type="submit">检索</button>
                                </div>
                            </div>
                        </li>
                        <li><div class="button"><div class="buttonContent"><button type="button" onclick="addToalbum(<?=$album?>);" warn="选择视频">加入到专辑</button></div></div></li>
                        <!--                    <li><a class="button" href="demo_page6.html" target="dialog" mask="true" title="查询框"><span>高级检索</span></a></li>-->
                    </ul>
                </div>
            </div>
        </form>
    </div>
    <div class="pageContent">
        <table class="table" width="512" layoutH="138">
            <thead>
            <tr>
                <th width="30"><input type="checkbox" group="videosid" class="checkboxCtrl"></th>
                <th width="50">视频id</th>
                <th width="70">用户名称</th>
                <th width="70">分类名称</th>
                <th width="200">视频名称</th>
                <th width="80">视频唯一编码</th>
                <th width="60">导演</th>
                <th width="80">主演</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="update_time" width="100">建档日期
                </th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id = "videoid">
            <?php foreach ($dataList as $key => $data): ?>
                <tr target="video-id" rel="<?= $data['id'] ?>">
                    <td><input name="videosid" value="<?= $data['id'] ?>" type="checkbox"></td>
                    <td><?= $data['id']?></td>
                    <td><?= $data['user_id'] ?></td>
                    <td><?= $data['album']['category']['name'] ?></td>
                    <td><a href="<?=UrlHelper::make('channel/video-play.html', ['id'=>$data['album_id']])?>" target="_blank"><?=$data['title'] ?></a></td>
                    <td><?= $data['video_unique'] ?></td>
                    <td><?= $data['album']['director']?></td>
                    <td><?= $data['album']['actor'] ?></td>
                    <td><?= date('Y-m-d', $data['create_time']) ?></td>
                    <td>
                        <?php if (\Yii::$app->user->can('video/toalbum')): ?>
                            <a title="加入专辑"  onclick="addToalbums(<?=$album?>,<?=$data['id']?>);"  class="btnAdd">加入专辑</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="panelBar">
            <div class="pages">
                <span>显示</span>
                <select class="combox" name="numPerPage" onchange="dialogPageBreak({numPerPage:this.value})">
                    <option value="20" <?= $prePage == 20 ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= $prePage == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= $prePage == 100 ? 'selected' : '' ?>>100</option>
                    <option value="200" <?= $prePage == 200 ? 'selected' : '' ?>>200</option>
                </select>
                <span>条，共<?= $dataCount ?>条</span>
            </div>
            <div class="pagination" rel='video-list' targetType="dialogTab" totalCount="<?= $dataCount ?>"
                 numPerPage="<?= $prePage ?>" pageNumShown="10" currentPage="<?= $page ?>"></div>
        </div>
    </div>
</div>
<script type="text/javascript">

    /**
     * 加入到专辑
     */
    function addToalbum(albumid) {
        var videoid = [];
        $('#videoid input[name="videosid"]:checked').each(function () {
            videoid.push($(this).val());
        });
        if(videoid.length == 0){
            alert('请勾选视频！');return false;
        }
        console.log(videoid);

        $.ajax({
            url:'<?=Url::to(['video/toalbum'])?>',
            dataType: 'json',//返回数据的类型
            data:{videoid:videoid,albumid:albumid},
            success: function (result, status) {
                console.log(result);
                if(result.statusCode == 200)
                {
                    alert('添加成功!');
                    dialogSearch($("#adrelform"));
                }
                else
                {
                    alert('添加失败!');
                }
            },
            error: function (data, status, e) {
                alert(e);
            }
        });
    }

    /**
     * 加入到专辑
     */
    function addToalbums(albumid,videoids) {
        var videoid = [];
            videoid.push(videoids);
        console.log(videoid);

        $.ajax({
            url:'<?=Url::to(['video/toalbum'])?>',
            dataType: 'json',//返回数据的类型
            data:{videoid:videoid,albumid:albumid},
            success: function (result, status) {
                console.log(result);
                if(result.statusCode == 200)
                {

                    alert('添加成功!');
                    dialogSearch($("#adrelform"));

                }
                else
                {
                    alert('添加失败!');
                }
            },
            error: function (data, status, e) {
                alert(e);
            }
        });
    }
</script>

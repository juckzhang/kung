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

$album = ArrayHelper::getValue($other, 'album');

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
        <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?= Url::to(['video/video-list']) ?>"
              method="post">
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
                        <!--                    <td>-->
                        <!--                        视频分类：-->
                        <!--                        <input type="hidden" name="other[category]" data-name="category.id" value="<=$category?>">-->
                        <!--                        <input class="required textInput valid" value="<=$category?>" name="category" data-name="category.name"  postfield="keyword" suggestfields="name" lookupgroup="category" autocomplete="off" type="text">-->
                        <!--                    </td>-->
                        <td><a class="btnLook" href="<?= Url::to(['video/category-list', 'search' => 1]) ?>"
                               lookupgroup="category">查找带回</a></td>
                        <td width="200">
                            视频专辑：
                            <input type="hidden" name="other[album]" data-name="album.id" value="<?= $album ?>">
                            <input class="required textInput valid lookup" value="<?= $album ?>" data-name="album.name"
                                   name="album" postfield="keyword" suggestfields="name" lookupgroup="album"
                                   autocomplete="off" type="text">
                        </td>
                        <td><a class="btnLook" href="<?= Url::to(['video/album-list', 'search' => 1]) ?>"
                               lookupgroup="album">查找带回</a></td>

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
                        <!--                    <li><div class="button"><div class="buttonContent"><button type="button" multLookup="ids[]" warn="选择卡片">选择带回</button></div></div></li>-->
                        <!--                    <li><a class="button" href="demo_page6.html" target="dialog" mask="true" title="查询框"><span>高级检索</span></a></li>-->
                    </ul>
                </div>
            </div>
        </form>
    </div>
    <div class="pageContent">
        <div class="panelBar">
            <ul class="toolBar">
                <?php if (\Yii::$app->user->can('video/edit-video')): ?>
                    <li><a class="add" href="<?= Url::to(['video/edit-video']) ?>" target="navTab"><span>添加</span></a>
                    </li>
                <?php endif; ?>

                <?php if (\Yii::$app->user->can('video/delete-video')): ?>
                    <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['video/delete-video']) ?>" class="delete"><span>批量删除</span></a></li>
                <?php endif; ?>

                <?php if (\Yii::$app->user->can('video/recommend-video')): ?>
                    <li><a title="确定要推荐记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['video/recommend-video', 'column' => 'is_recommend', 'value' => 1]) ?>"
                           class="delete"><span>楼层推荐</span></a></li>
                    <li><a title="确定要推荐记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['video/recommend-video', 'column' => 'is_hot', 'value' => 1]) ?>"
                           class="delete"><span>最热推荐</span></a></li>
                    <li><a title="确定要推荐记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['video/recommend-video', 'column' => 'is_new', 'value' => 1]) ?>"
                           class="delete"><span>最新推荐</span></a></li>
                    <li><a title="确定要取消推荐记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['video/recommend-video', 'column' => 'is_recommend', 'value' => 0]) ?>"
                           class="delete"><span>取消楼层推荐</span></a></li>
                    <li><a title="确定要取消推荐记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['video/recommend-video', 'column' => 'is_hot', 'value' => 0]) ?>"
                           class="delete"><span>取消最热推荐</span></a></li>
                    <li><a title="确定要取消推荐记录吗?" target="selectedTodo" rel="ids[]"
                           href="<?= Url::to(['video/recommend-video', 'column' => 'is_new', 'value' => 0]) ?>"
                           class="delete"><span>取消最新推荐</span></a></li>
                <?php endif; ?>
                <!--            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="demo/common/ajaxDone.html" class="delete"><span>批量删除逗号分隔</span></a></li>-->
                <!--            <li><a class="edit" href="demo_page4.html?uid={sid_user}" target="navTab" warn="请选择一个用户"><span>修改</span></a></li>-->
                <!--            <li class="line">line</li>-->
                <!--            <li><a class="icon" href="demo/common/dwz-team.xls" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>-->
                <!--<li><a target="selectedLoad" rel="ids" postType="string" href="demo_page1.html" class="icon"><span>批量Dialog Load逗号分隔</span></a></li>-->
            </ul>
        </div>
        <table class="table" width="1200" layoutH="138">
            <thead>
            <tr>
                <th width="30"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                <th width="80">用户名称</th>
                <th width="80">分类名称</th>
                <?php if (YII_ENV === 'development'): ?><th width="70">专辑ID</th><?php endif; ?>
                <th width="200">视频名称</th>
                <th width="80">推广方式</th>
                <th width="80">长海报图片</th>
                <th width="80">宽海报图片</th>
                <th width="80">视频唯一编码</th>
                <th width="60">导演</th>
                <th width="80">主演</th>
                <th width="60">点击数</th>
                <th width="80">楼层推荐</th>
                <th width="80">最热推荐</th>
                <th width="80">最新推荐</th>
                <th width="80">审核状态</th>
                <th class="<?= $orderDirection ?>" tyle="cursor: pointer;" orderfield="update_time" width="100">建档日期</th>
                <th width="250">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dataList as $key => $data): ?>
                <tr target="video-id" rel="<?= $data['id'] ?>">
                    <td><input name="ids[]" value="<?= $data['id'] ?>" type="checkbox"></td>
                    <td><?= $data['username'] ?></td>
                    <td><?= $data['album']['category']['name'] ?></td>
                    <?php if (YII_ENV === 'development'): ?>
                        <td><?= $data['album_id'] ?></td><?php endif; ?>
                    <td><a href="<?=UrlHelper::make('channel/video-play.html', ['id'=>$data['album_id']])?>" target="_blank"><?=$data['title'] ?></a></td>
                    <td><?=$data['user_id']==0 ? '后台上传' : ($data['extend']==0 ? '不推广' : ($data['extend']==1 ? '全站推广' : ($data['extend']==2 ? '本站推广' : '未知方式')))?></td>
                    <td><img src="<?= \Yii::$app->params['imageUrlPrefix'].$data['album']['poster_url'] ?>" width="100%"/></td>
                    <td><img src="<?= \Yii::$app->params['imageUrlPrefix'].$data['album']['wide_poster'] ?>" width="100%"/></td>
                    <td><?= $data['video_unique'] ?></td>
                    <td><?= $data['album']['director']?></td>
                    <td><?= $data['album']['actor'] ?></td>
                    <td align="center"><?= $data['click_num']?></td>
                    <td align="center"><?= $data['album']['is_recommend'] ? '推荐' : '不推荐' ?></td>
                    <td align="center"><?= $data['album']['is_hot'] ? '推荐' : '不推荐' ?></td>
                    <td align="center"><?= $data['album']['is_new'] ? '推荐' : '不推荐' ?></td>
                    <td align="center"><?= $data['status'] == 2 ? '未通过' : '已通过' ?></td>
                    <td><?= date('Y-m-d', $data['create_time']) ?></td>
                    <td>
                        <?php if (\Yii::$app->user->can('video/delete-video')): ?>
                            <a title="删除" target="ajaxTodo"
                               href="<?= Url::to(['video/delete-video', 'ids' => $data['id']]) ?>" class="btnDel">删除</a>
                        <?php endif; ?>

                        <?php if (\Yii::$app->user->can('video/edit-video')): ?>
                            <a title="编辑" target="navTab" href="<?= Url::to(['video/edit-video', 'id' => $data['id']]) ?>"
                               class="btnEdit">编辑</a>
                        <?php endif; ?>

                        <?php if (\Yii::$app->user->can('video/auditing-video')): ?>
                            <a title="<?= $data['status'] ? '审核中':'已审核通过'?>" target="ajaxTodo"
                               href="<?= Url::to(['video/auditing-video', 'ids' => $data['id']]) ?>" class="btnView">审核</a>
                        <?php endif; ?>
                        <?php if (\Yii::$app->user->can('video/push-video')): ?>
                            <a title="推送" target="ajaxTodo"
                               href="<?= Url::to(['video/push-video', 'id' => $data['album_id'], 'name' => urlencode($data['title'])]) ?>" class="btn">推送</a>
                        <?php endif; ?>
                        <?php if (\Yii::$app->user->can('video/add-app-download')): ?>
                            <a title="App 下载" target="ajaxTodo"
                               href="<?= Url::to(['video/add-app-download', 'id' => $data['album_id']]) ?>" class="">App 下载</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="panelBar">
            <div class="pages">
                <span>显示</span>
                <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                    <option value="20" <?= $prePage == 20 ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= $prePage == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= $prePage == 100 ? 'selected' : '' ?>>100</option>
                    <option value="200" <?= $prePage == 200 ? 'selected' : '' ?>>200</option>
                </select>
                <span>条，共<?= $dataCount ?>条</span>
            </div>
            <div class="pagination" rel='video-list' targetType="navTab" totalCount="<?= $dataCount ?>"
                 numPerPage="<?= $prePage ?>" pageNumShown="10" currentPage="<?= $page ?>"></div>
        </div>
    </div>
</div>

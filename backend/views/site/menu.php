<?php
use yii\helpers\Url;
use common\models\mysql\AdModel;
?>
<div id="sidebar">
    <div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>

    <div class="accordion" fillSpace="sidebar">
        <div class="accordionHeader">
            <h2><span>Folder</span>网站内容管理</h2>
        </div>
        <div class="accordionContent">
            <ul class="tree treeFolder">
                <!-- 视频信息 -->
                <?php if(\Yii::$app->user->can('video')):?>
                <li><a>视频信息管理</a>
                    <ul>
                        <?php if(\Yii::$app->user->can('video/video-list')):?>
                        <li><a href="<?=Url::to(['video/video-list','other' => ['source_type' => 1]])?>" target="navTab" rel="video-list">视频信息列表</a></li>
                        <?php endif;?>

                        <?php if(\Yii::$app->user->can('video/category-list')):?>
                        <li><a href="<?=Url::to(['video/category-list', 'other' => ['source_type' => 1]])?>" target="navTab" rel="category-list">视频分类信息列表</a></li>
                        <?php endif;?>

                        <?php if(\Yii::$app->user->can('video/comment-list')):?>
                            <li><a href="<?=Url::to(['video/comment-list'])?>" target="navTab" rel="comment-list">视频评论列表</a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <!-- 音频信息 -->
                <?php if(\Yii::$app->user->can('video')):?>
                    <li><a>音频信息管理</a>
                        <ul>
                            <?php if(\Yii::$app->user->can('video/video-list')):?>
                                <li><a href="<?=Url::to(['video/video-list','other' => ['source_type' => 2]])?>" target="navTab" rel="video-list">音频信息列表</a></li>
                            <?php endif;?>

                            <?php if(\Yii::$app->user->can('video/category-list')):?>
                                <li><a href="<?=Url::to(['video/category-list', 'other' => ['source_type' => 2]])?>" target="navTab" rel="category-list">音频分类信息列表</a></li>
                            <?php endif;?>

                            <?php if(\Yii::$app->user->can('video/comment-list')):?>
                                <li><a href="<?=Url::to(['video/comment-list'])?>" target="navTab" rel="comment-list">视频评论列表</a></li>
                            <?php endif;?>
                        </ul>
                    </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('member')):?>
                <li><a>用户信息管理</a>
                    <ul>
                        <?php if(\Yii::$app->user->can('member/member-list')):?>
                            <li><a href="<?=Url::to(['member/member-list'])?>" target="navTab" rel="member-list">全部用户列表</a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <li><a href="<?=Url::to(['site/logout'])?>" target="navTab" rel="logout">退出</a></li>
            </ul>
        </div>

        <?php if(\Yii::$app->user->can('system')):?>
        <div class="accordionHeader">
            <h2><span>Folder</span>系统与日志管理</h2>
        </div>
        <div class="accordionContent">
            <ul class="tree treeFolder">
            <?php if(\Yii::$app->user->can('system/source-list')):?>
                <li><a>资源管理</a>
                    <ul>
                        <li><a href="<?=Url::to(['system/source-list'])?>" target="navTab" rel="source-list">资源列表</a></li>
                        <?php if(\Yii::$app->user->can('system/edit-source')):?>
                        <li><a href="<?=Url::to(['system/edit-source'])?>" target="dialog" rel="edit-source">添加资源</a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('system/role-list')):?>
                <li><a>角色管理</a>
                    <ul>
                        <li><a href="<?=Url::to(['system/role-list'])?>" target="navTab" rel="role-list">角色列表</a></li>
                        <?php if(\Yii::$app->user->can('system/edit-role')):?>
                        <li><a href="<?=Url::to(['system/edit-role'])?>" target="dialog" rel="edit-role">添加角色 </a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('system/user-list')):?>
                <li><a>管理员信息管理</a>
                    <ul>
                        <li><a href="<?=Url::to(['system/user-list'])?>" target="navTab" rel="user-list">管理员列表</a></li>
                        <?php if(\Yii::$app->user->can('system/edit-user')):?>
                        <li><a href="<?=Url::to(['system/edit-user'])?>" target="dialog" rel="edit-bord">添加管理员</a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>
            </ul>
        </div>
        <?php endif;?>
    </div>
</div>
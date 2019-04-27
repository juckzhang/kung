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
                <?php if(\Yii::$app->user->can('video')):?>
                <li><a>视频信息管理</a>
                    <ul>
                        <?php if(\Yii::$app->user->can('video/video-list')):?>
                        <li><a href="<?=Url::to(['video/video-list'])?>" target="navTab" rel="video-list">视频信息列表</a></li>
                        <?php endif;?>

                        <?php if(\Yii::$app->user->can('video/category-list')):?>
                        <li><a href="<?=Url::to(['video/category-list'])?>" target="navTab" rel="category-list">视频分类信息列表</a></li>
                        <?php endif;?>

                        <?php if(\Yii::$app->user->can('video/album-list')):?>
                        <li><a href="<?=Url::to(['video/album-list'])?>" target="navTab" rel="album-list">视频专辑信息列表</a></li>
                        <?php endif;?>

                        <?php if (\Yii::$app->user->can('video/app-download')): ?>
                        <li><a href="<?=Url::to(['video/app-download'])?>" target="navTab" rel="app-download">App 视频下载列表</a> </li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('member')):?>
                <li><a>会员信息管理</a>
                    <ul>
                        <?php if(\Yii::$app->user->can('member/member-list')):?>
                            <li><a href="<?=Url::to(['member/member-list'])?>" target="navTab" rel="member-list">全部会员列表</a></li>
                        <?php endif;?>
                        <?php if(\Yii::$app->user->can('member/qualification-list')):?>
                            <li><a href="<?=Url::to(['member/qualification-list'])?>" target="navTab" rel="qualification-list">个人认证</a></li>
                        <?php endif;?>
                        <?php if(\Yii::$app->user->can('member/participator--list')):?>
                            <li><a href="<?=Url::to(['member/participator-list'])?>" target="navTab" rel="participator-list">合作会员管理</a></li>
                        <?php endif;?>
                        <?php if(\Yii::$app->user->can('member/profit-list')):?>
                            <li><a href="<?=Url::to(['member/profit-list'])?>" target="navTab" rel="profit-list">分成管理</a></li>
                        <?php endif;?>
                        <?php if (\Yii::$app->user->can('member/withdraw-apply-list')): ?>
                            <li><a href="<?=Url::to(['member/withdraw-apply-list'])?>" target="navTab" rel="withdraw-apply-list">提现管理</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('article')):?>
                <li><a>文章管理</a>
                    <ul>
                        <?php if(\Yii::$app->user->can('article/article-list')):?>
                        <li><a href="<?=Url::to(['article/article-list'])?>" target="navTab" rel="article-list">文章列表</a></li>
                        <?php endif;?>

                        <?php if(\Yii::$app->user->can('article/category-list')):?>
                        <li><a href="<?=Url::to(['article/category-list'])?>" target="navTab" rel="article-category-list">文章分类列表</a></li>
                        <?php endif;?>

                        <?php if(\Yii::$app->user->can('article/feedback-list')):?>
                            <li><a href="<?=Url::to(['article/feedback-list'])?>" target="navTab" rel="feedback-list">意见反馈列表</a></li>
                        <?php endif;?>

                    </ul>
                </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('ad')):?>
                <li><a>广告信息管理</a>
                    <ul>
                        <?php if(\Yii::$app->user->can('ad/ad-list')):?>
                        <li><a href="<?=Url::to(['ad/ad-list'])?>" target="navTab" rel="ad-list">广告信息列表</a></li>
                        <?php endif;?>
                    </ul>
                    <ul>
                        <?php if(\Yii::$app->user->can('ad/mobile-screen-list')):?>
                            <li><a href="<?=Url::to(['ad/mobile-screen-list'])?>" target="navTab" rel="ad-list">移动端开屏广告</a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('menu')):?>
                <li><a>网站菜单信息管理</a>
                    <ul>
                        <?php if(\Yii::$app->user->can('menu/menu-list')):?>
                        <li><a href="<?=Url::to(['menu/menu-list'])?>" target="navTab" rel="menu-list">菜单信息列表</a></li>
                        <?php endif;?>

                        <?php if(\Yii::$app->user->can('menu/menu-list')):?>
                        <li><a href="<?=Url::to(['menu/edit-menu'])?>" target="dialog" rel="edit-menu">添加菜单</a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('service')):?>
                    <li><a>场景服务管理</a>
                        <ul>
                            <?php if(\Yii::$app->user->can('service/service-list')):?>
                                <li><a href="<?=Url::to(['service/service-list'])?>" target="navTab" rel="service-list">场景列表</a></li>
                            <?php endif;?>

                            <?php if(\Yii::$app->user->can('service/service-order')):?>
                                <li><a href="<?=Url::to(['service/service-order'])?>" target="navTab" rel="service-order">场景订单</a></li>
                            <?php endif;?>
                        </ul>
                    </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('coupon')):?>
                    <li><a>代金券管理</a>
                        <ul>
                            <?php if(\Yii::$app->user->can('coupon/template-list')):?>
                                <li><a href="<?=Url::to(['coupon/template-list'])?>" target="navTab" rel="coupon-template">代金券模板列表</a></li>
                            <?php endif;?>

                            <?php if(\Yii::$app->user->can('coupon/list')):?>
                                <li><a href="<?=Url::to(['coupon/list'])?>" target="navTab" rel="list">代金券列表</a></li>
                            <?php endif;?>
                        </ul>
                    </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('filmfest')): ?>
                    <li><a>电影节管理</a>
                        <ul>
                            <?php if(\Yii::$app->user->can('filmfest/signup-list')): ?>
                                <li><a href="<?=Url::to(['filmfest/signup-list'])?>" target="navTab" rel="list">电影节报名列表</a></li>
                            <?php endif; ?>
                            <?php if(\Yii::$app->user->can('filmfest/filmfest-list')): ?>
                                <li><a href="<?=Url::to(['filmfest/filmfest-list'])?>" target="navTab" rel="list">电影节列表</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
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
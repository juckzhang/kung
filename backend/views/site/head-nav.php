<?php
use yii\helpers\Url;
?>
<div id="header">
    <div class="headerNav">
<!--        <a class="logo" href="<=Url::home()?><!--">标志</a>-->
        <ul class="nav">
            <li><a href=" <?=Url::to(['site/change-password'])?>" target="dialog" width="600">设置</a></li>
            <!--                <li><a href="http://www.cnblogs.com/dwzjs" target="_blank">博客</a></li>-->
            <!--                <li><a href="http://weibo.com/dwzui" target="_blank">微博</a></li>-->
            <li><a href="<?=Url::to(['site/logout'])?>">退出</a></li>
        </ul>
        <ul class="themeList" id="themeList">
            <li theme="default"><div class="selected">蓝色</div></li>
            <li theme="green"><div>绿色</div></li>
            <!--<li theme="red"><div>红色</div></li>-->
            <li theme="purple"><div>紫色</div></li>
            <li theme="silver"><div>银色</div></li>
            <li theme="azure"><div>天蓝</div></li>
        </ul>
    </div>
</div>
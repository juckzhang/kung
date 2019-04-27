<?php
/**
 * Created by PhpStorm.
 * User: dongbin
 * Date: 14/07/2017
 * Time: 12:00 PM
 */

?>

<h3>影片</h3>

<ul>
    <li><?= "电影名称：$data->movie_name" ?></li>
    <li><?= "影片语言：$data->movie_language" ?></li>
    <li><?= "影片分类：$data->movie_category" ?></li>
    <li><?= "影片类型：$data->movie_type" ?></li>
    <li><?= "影片题材：$data->movie_theme" ?></li>
    <li><?= "对白字幕：$data->movie_subtitle" ?></li>
    <li><?= "片长：$data->movie_duration" ?></li>
    <li><?= "声音规格：$data->movie_sound_standard" ?></li>
    <li><?= "影片形式：$data->movie_format" ?></li>
    <li><?= "影片规格：$data->movie_standard" ?></li>
    <li><?= "画面颜色：$data->frame_color" ?></li>
    <li><?= "视频格式：$data->video_format" ?></li>
    <li><?= "拍摄国家：$data->take_country" ?></li>
    <li><?= "上线日期：$data->publish_date" ?></li>
    <li><?= "完成日期：$data->finish_date" ?></li>
</ul>

<h3>导演</h3>

<?php foreach (json_decode($data->director) as $key => $vv): ?>
    <ul>
        <li><?= "姓名：$vv->directorName" ?></li>
        <li><?= "性别：$vv->directorSex" ?></li>
        <li><?= "首部作品：$vv->directorCapital" ?></li>
        <li><?= "国籍：$vv->directorNationality" ?></li>
        <li><?= "出生日期：$vv->directorBirthday" ?></li>
        <li><?= "地址：$vv->directorAddress" ?></li>
    </ul>
<?php endforeach; ?>



<h3>主演</h3>

<?php foreach (json_decode($data->performer) as $key => $vv): ?>
    <ul>
        <li><?= "姓名：$vv->performerName" ?></li>
        <li><?= "性别：$vv->performerSex" ?></li>
        <li><?= "国籍：$vv->performerNationality" ?></li>
        <li><?= "出生日期：$vv->performerBirthday" ?></li>
        <li><?= "地址：$vv->performerAddress" ?></li>
    </ul>
<?php endforeach; ?>



<h3>出品公司</h3>

<ul>
    <li><?= "出品公司名称：$data->company_name" ?></li>
    <li><?= "出品公司地址：$data->company_address" ?></li>
    <li><?= "出品公司电话：$data->company_phone_number" ?></li>
    <li><?= "出品公司传真：$data->company_fax_number" ?></li>
    <li><?= "出品公司邮箱：$data->company_email" ?></li>
    <li><?= "联系人：$data->company_contact_name" ?></li>
    <li><?= "性别：$data->company_contact_sex" ?></li>
    <li><?= "部门：$data->company_contact_department" ?></li>
    <li><?= "职务：$data->company_contact_duty" ?></li>
</ul>

<h3>主创团队</h3>

<ul>
    <li><?= "制片人：$data->prod_team_producer" ?></li>
    <li><?= "摄影师：$data->prod_team_lensman" ?></li>
    <li><?= "编剧：$data->prod_team_screenwriter" ?></li>
    <li><?= "美术师：$data->prod_team_artist" ?></li>
    <li><?= "剪辑师：$data->prod_team_cutter" ?></li>
    <li><?= "艺术指导：$data->prod_team_art_direction" ?></li>
    <li><?= "动画制作：$data->prod_team_animation" ?></li>
    <li><?= "特效制作：$data->prod_team_effect" ?></li>
    <li><?= "原创音乐：$data->prod_team_original_muisc" ?></li>
</ul>

<h3>物料信息</h3>

<ul>
    <li>百度网盘地址：<a href="<?= $data->materiel_baidu_pan_url ?>" target="_blank"><?= $data->materiel_baidu_pan_url ?></a></li>
    <li><?= "网盘提取密码：$data->materiel_baidu_pan_password" ?></li>
</ul>

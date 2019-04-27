create database IF NOT EXISTS kung;
use kung;

-- 用户表
create table if NOT EXISTS kung_user(
  id int unsigned NOT null primary key auto_increment comment'用户id',
  account_type tinyint unsigned NOT NULL DEFAULT 1 comment'用户类型 1：facebook 2：google',
  nick_name char(15) NOT null default '' comment'用户昵称',
  third_account char(15) NOT NULL default '' comment'第三方appid',
  icon_url varchar(255) NOT NULL DEFAULT '' comment'用户头像',
  password char(125) NOT null default'' comment'用户密码',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：禁用 2：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1000000000;

-- 资源分类表
CREATE TABLE if NOT EXISTS kung_video_category(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  parent_id int unsigned NOT NULL default 0 comment'父id',
  source_type tinyint(1) unsigned NOT NULL default 0 comment'资源类型 1：视频， 2，音频',
  `name` VARCHAR(15) NOT NULL default '' comment '分类名称',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 视频/音频资源表
CREATE TABLE if NOT EXISTS kung_video(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  cate_id int unsigned NOT NULL DEFAULT 0 comment'分类id',
  source_type int unsigned NOT NULL DEFAULT 0 comment'资源类型 1：视频 2：音频',
  is_recommd tinyint(1) unsigned NOT NULL DEFAULT 0 comment'是否首页推荐 1:推荐 2：不推荐',
  title varchar(255) NOT NULL DEFAULT '' comment'标题',
  sub_title varchar(255) NOT NULL DEFAULT '' comment'副标题',
  video_link VARCHAR(255) NOT NULL DEFAULT '' comment'视屏地址',
  play_num int unsigned NOT NULL DEFAULT 0 comment'播放次数',
  real_play_num int unsigned NOT NULL DEFAULT 0 comment'真实播放次数',
  collection_num int unsigned NOT NULL DEFAULT 0 comment'收藏数',
  real_collection_num int unsigned NOT NULL DEFAULT 0 comment'真实收藏数',
  download_num int unsigned NOT NULL DEFAULT 0 comment'下载量',
  real_download_num int unsigned NOT NULL DEFAULT 0 comment'下载量',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 视频/音频台词表
CREATE TABLE if NOT EXISTS kung_video_lines(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  source_id int unsigned NOT NULL DEFAULT 0 comment'资源id',
  lang_type char(15) NOT NULL DEFAULT '' comment'台词语言类型',
  line_number int unsigned NOT NULL DEFAULT 0 comment'表示第几段台词',
  content varchar(255) NOT NULL DEFAULT '' comment'内容',
  start_time char(25) NOT NULL default '' comment'开始时间',
  end_time char(25) NOT NULL default '' comment'结束时间',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 资源收藏表
CREATE TABLE if NOT EXISTS kung_video_collection(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'用户id',
  source_id int unsigned NOT NULL DEFAULT 0 comment'资源id',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 资源浏览记录表
CREATE TABLE if NOT EXISTS kung_video_look(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'用户id',
  source_id int unsigned NOT NULL DEFAULT 0 comment'资源id',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 资源下载记录表
CREATE TABLE if NOT EXISTS kung_video_download(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'用户id',
  source_id int unsigned NOT NULL DEFAULT 0 comment'资源id',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 评论表
CREATE TABLE if NOT EXISTS kung_video_comment(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  parent_id int unsigned NOT NULL DEFAULT 0 comment'父id',
  user_id int unsigned NOT null DEFAULT 0 comment'用户id',
  source_id int unsigned NOT NULL DEFAULT 0 comment'资源id',
  content VARCHAR(255) NOT NULL comment'评论内容',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 管理员角色表
CREATE TABLE IF NOT EXISTS kung_role(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  `name` char(25) NOT NULL comment'角色名称',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 后台管理员列表
create TABLE IF NOT EXISTS kung_admin(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  role_id int unsigned NOT null default 0 comment'角色',
  `username` char(32) NOT NULL COMMENT '昵称，允许修改',
  `password` char(255) NOT NULL DEFAULT '',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

INSERT into`kung_admin` VALUES (null,0,'root','$2y$13$zVjSKOcaBEnuwFFtW7fNCuVX1dysOj5U6oxOiWNDGoxpCd1JKD8ii',500,123456789,123456789,0);

-- 后台控制访问的资源列表
CREATE TABLE IF NOT EXISTS kung_source(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  parent_id int unsigned NOT NULL DEFAULT 0 comment'父id',
  name char(25) NOT NULL comment'资源名称',
  request char(25) NOT NULL comment'对应的控制器方法',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned   NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned   NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 角色资源关系表
CREATE TABLE IF NOT EXISTS kung_role_source(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  role_id int  unsigned NOT NULL comment'角色id',
  source_id int unsigned NOT NULL comment'资源id',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 操作记录表
CREATE TABLE IF NOT EXISTS kung_admin_operation(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'用户id',
  content VARCHAR(255) NOT NULL DEFAULT '' comment'操作内容说明',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

-- 用户反馈
CREATE TABLE IF NOT EXISTS kung_feedback(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'用户id',
  content VARCHAR(255) NOT NULL DEFAULT '' comment'操作内容说明',
  parent_id int unsigned NOT NULL DEFAULT 0 comment'回复id',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;
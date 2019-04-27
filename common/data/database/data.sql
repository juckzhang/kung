create database IF not EXISTS kung;
use kung;

create table if not EXISTS kung_user(
  id int unsigned not null primary key auto_increment comment'用户id',
  type tinyint unsigned NOT NULL DEFAULT 1 comment'会员类型 1：普通会员 2：认证会员 4：合作会员',
  nick_name char(15) not null default '' comment'用户昵称',
  mobile char(12) not null default '' comment'用户手机号',
  icon_url varchar(255) NOT NULL DEFAULT '' comment'用户头像',
  password char(125) not null default'' comment'用户密码',
  email char(32) not null default '' comment'用户邮箱',
  city int unsigned NOT NULL DEFAULT 0 comment'所属于城市',
  source tinyint unsigned NOT NULL DEFAULT 0 comment'注册客户端类型',
  balance int unsigned NOT NULL DEFAULT 0 comment'账户余额',
  note VARCHAR(255) NOT NULL DEFAULT '' comment'备注信息',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：禁用 2：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_user_qualification(
  user_id int unsigned NOT NULL DEFAULT 0 comment'用户id',
  name VARCHAR(25) NOT NULL DEFAULT '' comment'真实姓名',
  barthday bigint unsigned NOT NULL DEFAULT 0 comment'生日',
  sex tinyint unsigned NOT NULL DEFAULT 0 comment'性别 0：男性 1：女性',
  mobile char(12) NOT NULL DEFAULT '' comment'手机号',
  email varchar(25) NOT NULL DEFAULT '' comment'邮箱',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：禁用 2：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_user_cooperate(
  user_id int unsigned NOT NULL DEFAULT 0 comment'用户id',
  company_name VARCHAR(30) NOT NULL DEFAULT '' comment'公司名称',
  card_num varchar(20) NOT NULL DEFAULT '' comment'交易账号',
  company_address VARCHAR(255) NOT NULL DEFAULT '' comment'公司地址',
  company_info text NOT NULL DEFAULT '' comment'公司简介',
  magnum_opus varchar(255) NOT NULL DEFAULT '' comment'代表作',
  company_link VARCHAR(255) NOT NULL DEFAULT '' comment'公司主页',
  email VARCHAR(255) NOT NULL DEFAULT '' comment'联系邮箱',
  concat VARCHAR(30) NOT NULL DEFAULT '' comment'联系人',
  mobile char(12) NOT NULL DEFAULT '' comment'联系人邮箱',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：禁用 2：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_user_team(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  name char(25) NOT NULL DEFAULT '' comment'团队名字',
  user_id int unsigned NOT NULL DEFAULT 0 comment'团队创建者',
  icon_url varchar(255) NOT NULL DEFAULT '' comment'头像',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_user_team_member(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  team_id char(25) NOT NULL DEFAULT '' comment'团队名字',
  user_id int unsigned NOT NULL DEFAULT 0 comment'团队创建者',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_video_category(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  parent_id int unsigned NOT NULL default 0 comment'父id',
  name VARCHAR(15) NOT NULL default '' comment '分类名称',
  poster_url VARCHAR(255) NOT NULL default '' comment'海报图片',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_video_album(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'上传用户id',
  cate_id int unsigned NOT NULL DEFAULT 0 comment'分类id',
  name VARCHAR(25) NOT NULL DEFAULT '' comment'专辑名称',
  poster_url VARCHAR(255) NOT NULL DEFAULT '' comment'专辑海报图片',
  wide_poster VARCHAR(255) NOT NULL DEFAULT '' comment'专辑海报图片-横图片',
  director VARCHAR(255) NOT NULL DEFAULT '' comment'导演',
  actor VARCHAR(255) NOT NULL DEFAULT '' comment'主演',
  area VARCHAR(255) NOT NULL DEFAULT '' comment '地区',
  introduction text NOT NULL DEFAULT '' comment'简介',
  release_time char(4) NOT NULL DEFAULT '1990' comment '上映年份',
  is_recommend tinyint unsigned NOT NULL DEFAULT 0 comment'是否楼层推荐 0 ：不是 1：是',
  is_carousel tinyint unsigned NOT NULL DEFAULT 0 comment'是否推荐到首页轮播 0 ：不是 1：是',
  is_album tinyint unsigned NOT NULL DEFAULT 0 comment'是否是专辑0：不是 1：是',
  has_video tinyint unsigned NOT NULL DEFAULT 0 comment'是否有视频存在 0：专辑下还没有视频，1：专辑下已经有视频了',
  is_new tinyint(1) unsigned not null DEFAULT 0 comment'最新',-- 首页最新推荐
  is_hot tinyint(1) unsigned not null DEFAULT 0 comment'最热',-- 首页最热推荐
  click_num bigint unsigned not null DEFAULT 0 comment'专辑的点击量',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除，2：未发布',
  KEY `is_album` (`is_album`),
  KEY `cate_id` (`cate_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_video(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  album_id int unsigned NOT NULL DEFAULT 0 comment'专辑id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'上传用户id',
  title varchar(255) NOT NULL DEFAULT '' comment'标题',
  video_unique char(25) NOT NULL comment'乐视视频唯一标识',
  leshi_user char(25) not null default '' comment'乐视用户id',
  video_id int unsigned NOT NULL comment'乐视视频id',
  video_link VARCHAR(255) NOT NULL DEFAULT '' comment'视屏地址',
  click_num int unsigned NOT NULL DEFAULT 0 comment'点击次数',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除，2：审核中 3：审核未通过'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_video_collection(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'上传用户id',
  source_id int unsigned NOT NULL DEFAULT 0 comment'专辑id',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_video_look(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'上传用户id',
  source_id int unsigned NOT NULL DEFAULT 0 comment'专辑id',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_album_recommend(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'上传用户id',
  source_id int unsigned NOT NULL DEFAULT 0 comment'专辑id',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_album_subscription(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'上传用户id',
  source_id int unsigned NOT NULL DEFAULT 0 comment'专辑id',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_tags(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  name VARCHAR(25) NOT NULL DEFAULT '' comment'标签名称',
  click_num int unsigned NOT NULL DEFAULT 0 comment'该标签被查询点击的次数',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_album_tags(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  album_id int unsigned NOT NULL DEFAULT 0 comment'专辑id',
  tags_id int unsigned NOT NULL DEFAULT 0 comment'标签id',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_album_comment(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  parent_id int unsigned NOT NULL DEFAULT 0 comment'父id',
  user_id int unsigned not null DEFAULT 0 comment'用户id',
  album_id int unsigned NOT NULL DEFAULT 0 comment'专辑id',
  content VARCHAR(255) NOT NULL comment'评论内容',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_user_incomme(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id not null DEFAULT 0 comment'用户id',
  amount int unsigned NOT NULL comment'收益额',
  clik_num int unsigned NOT NULL 0 comment'有效点击量',
  start_time int unsigned NOT NULL DEFAULT 0 comment'开始时间',
  end_time int unsigned NOT NULL DEFAULT 0 comment'结束时间',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除 2：审核中'
);

CREATE TABLE if not EXISTS kung_user_cash(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned not null DEFAULT 0 comment'用户id',
  amount int unsigned NOT NULL comment'收益额',
  clik_num int unsigned NOT NULL DEFAULT 0 comment'有效点击量',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：申请状态 1：删除 2：完成体现'
);

CREATE TABLE if not EXISTS kung_sms_message(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id text NOT NULL DEFAULT '' comment'用户id列表',
  content varchar(255) NOT NULL DEFAULT '' comment'发送内容',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：等待发送 1：删除 2：发送成功'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE if not EXISTS kung_push_message(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id text NOT NULL DEFAULT '' comment'用户id列表',
  content varchar(255) NOT NULL DEFAULT '' comment'发送内容',
  link_type tinyint unsigned(1) NOT NULL DEFAULT 0 comment'消息链接类型',
  link_url varchar(255) NOT NULL DEFAULT '' comment'链接地址',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：等待发送 1：删除 2：发送成功'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE IF not EXISTS kung_ad(
  id int unsigned  NOT NULL PRIMARY KEY auto_increment comment'id',
  position_id int unsigned not null DEFAULT 0 comment'广告位置 0：首页轮播',
  title char(255) NOT NULL DEFAULT '' comment'广告标题',
  type tinyint unsigned  NOT NULL DEFAULT 0 comment'广告类型 0：图片 1：文字',
  btn_name char(10) not null default '' comment'点击按钮名称',
  indocution text not null default'' comment'简介',
  content varchar(255) NOT NULL DEFAULT '' comment'广告内容',
  link_url VARCHAR (255) NOT NULL DEFAULT '' comment'广告链接',
  start_time bigint unsigned   NOT NULL default 0 comment'广告开始时间',
  end_time bigint unsigned not null DEFAULT 0 comment '广告结束时间 0：一直有效',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE IF not EXISTS kung_article_category(
  id int unsigned  NOT NULL PRIMARY Key auto_increment comment'id',
  parent_id int unsigned NOT NULL DEFAULT 0 comment'父id 0：等级分类',
  name VARCHAR(25) NOT  NULL comment'分类名称',
  poster_url VARCHAR(255) NOT  NULL  DEFAULT '' comment'海报图片',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE IF not EXISTS kung_article(
  id int unsigned  NOT NULL PRIMARY KEY auto_increment comment'id',
  cate_id int unsigned  NOT NULL comment'分类id',
  title VARCHAR(100) NOT  NULL DEFAULT '' comment'标题',
  sub_title VARCHAR(255) NOT NULL DEFAULT '' comment'副标题',
  author VARCHAR(25) NOT NULL DEFAULT '' comment'作者',
  source VARCHAR(255) NOT NULL DEFAULT '' comment '文章来源',
  poster_url VARCHAR(255) NOT  NULL  DEFAULT '' comment'海报图片',
  content text NOT NULL comment'文章内容',
  is_recommend tinyint unsigned NOT NULL DEFAULT 0 comment'是否是首页单独推荐的文章 0：不是 1：是',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE IF not EXISTS kung_menu(
  id int unsigned  NOT NULL PRIMARY KEY auto_increment comment'id',
  parent_id int unsigned NOT NULL DEFAULT 0 comment'父id',
  position_id int unsigned NOT NULL DEFAULT 0 comment'显示位置 0：所有位置 1：顶部导航栏 2：底部导航栏',
  type int unsigned  NOT NULL DEFAULT 0 comment'链接跳转类型 0：其他站外 1：文章 2：分类',
  name VARCHAR(50) NOT  NULL comment'标题',
  link_url VARCHAR(255) NOT NULL DEFAULT '' comment'导航栏链接地址',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE IF NOT EXISTS kung_role(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  name char(25) NOT NULL comment'角色名称',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;


create TABLE IF not EXISTS kung_admin(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  role_id int unsigned not null default 0 comment'角色',
  `username` char(32) NOT NULL COMMENT '昵称，允许修改',
  `password` char(255) NOT NULL DEFAULT '',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

INSERT into`kung_admin` VALUES (null,0,'root','$2y$13$zVjSKOcaBEnuwFFtW7fNCuVX1dysOj5U6oxOiWNDGoxpCd1JKD8ii',500,123456789,123456789,0);

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

CREATE TABLE IF NOT EXISTS kung_role_source(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  role_id int  unsigned NOT NULL comment'角色id',
  source_id int unsigned NOT NULL comment'资源id',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE kung_admin_operation(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'用户id',
  content VARCHAR(255) NOT NULL DEFAULT '' comment'操作内容说明',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE kung_sensitive_word(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  type tinyint unsigned NOT NULL DEFAULT 0 comment'应用场景 0：所有 1：注册 2:评论',
  word varchar(25) NOT NULL DEFAULT '' comment'关键词',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint  unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint  unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE kung_video_contrive(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'用户id列表',
  album_id int unsigned NOT NULL DEFAULT 0 comment'专辑id',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：等待推广 1：删除 2：已经推广'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;

CREATE TABLE kung_message(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  mobile char(14)  NOT NULL DEFAULT '' comment'用户id列表',
  content varchar(255)  NOT NULL DEFAULT '' comment'专辑id',
  type tinyint unsigned NOT NULL DEFAULT 0 comment'排序字段',
  expires_time int unsigned NOT NULL DEFAULT 0 comment'有效时长',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：等待推广 1：删除 2：已经推广'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1;
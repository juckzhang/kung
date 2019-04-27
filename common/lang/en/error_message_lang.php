<?php
use common\constants\CodeConstant;
return [
    /** 公用模块 **/

    /** 成功信息 */
    CodeConstant::FOLLOW_SUCCESS       => '关注成功',
    CodeConstant::CANCEL_FOLLOW_SUCCESS=> '取消关注成功',
    CodeConstant::COLLECT_SUCCESS      => '收藏成功',
    CodeConstant::CANCEL_COLLECT_SUCCESS=> '取消收藏成功',
    CodeConstant::SUBSCRIBE_SUCCESS     => '订阅成功',
    CodeConstant::CANCEL_SUBSCRIBE_SUCCESS=> '取消订阅成功',
    CodeConstant::POST_COMMENT_SUCCESS  => '评论成功',
    CodeConstant::DELETE_POST_COMMENT_SUCCESS  => '删除评论成功',
    CodeConstant::DELETE_POST_SUCCESS   => '删除帖子成功',
    CodeConstant::VOTE_UP_SUCCESS       => '点赞成功',
    CodeConstant::CANCEL_LIKE_SUCCESS   => '取消点赞成功',
    CodeConstant::REPORT_SUCCESS        => '举报成功',
    CodeConstant::SIGN_SUCCESS     => '签到成功',
    CodeConstant::FIND_PASSWORD_SUCCESS => '找回密码成功',
    CodeConstant::CHANGE_PASSWORD_SUCCESS => '修改密码成功',
    CodeConstant::CHANGE_AVATAR_SUCCESS => '修改头像成功',
    CodeConstant::CHANGE_NICK_SUCCESS   => '修改昵称成功',
    CodeConstant::CHANGE_GENDER_SUCCESS => '修改性别成功',
    CodeConstant::CHANGE_BIRTHDAY_SUCCESS => '修改生日成功',
    CodeConstant::CHANGE_SIGNATURE_SUCCESS => '修改签名成功',
    CodeConstant::FEEDBACK_SUCCESS      => '反馈成功',
    CodeConstant::DELETE_MESSAGE_SUCCESS => '删除消息成功',
    CodeConstant::EMPTY_MESSAGE_SUCCESS  => '清空消息成功',
    CodeConstant::CREATE_TOPIC_SUCCESS   => '发帖成功',
    CodeConstant::USER_LOGOUT_SUCCESS    => '退出登录成功',

    /** 出错信息 */
    CodeConstant::UNKNOWN_ERROR        => '位置错误',
    CodeConstant::PARAM_ERROR          => '参数错误',
    CodeConstant::REQUEST_METHOD_ERROR => '请求方式错误',
    CodeConstant::RPC_PARAM_ERROR      => 'rpc调用请求参数错误',
    CodeConstant::RPC_APP_FORBIDDEN_A  => 'openID错误',
    CodeConstant::RPC_APP_FORBIDDEN_S  => 'rpc签名错误',
    CodeConstant::RPC_CLASS_FORBIDDEN  => '禁止访问的服务',
    CodeConstant::RPC_CLASS_NOT_EXIST  => 'class 不存在',
    CodeConstant::RPC_METHOD_NOT_EXIST => 'method 不存在',
    CodeConstant::RPC_METHOD_FORBIDDEN => '禁止访问的方法',
    CodeConstant::RPC_FAILED           => 'rpc失败',
    CodeConstant::SOURCE_NOT_EXISTS    => '资源不存在',
    CodeConstant::IS_FOLLOWED          => '已经关注过了',
    CodeConstant::FOLLOW_FAILED        => '关注失败',
    CodeConstant::IS_NOT_FOLLOWED      => '还未关注过',
    CodeConstant::CANCEL_FOLLOW_FAILED => '取消关注失败',
    CodeConstant::IS_COLLECTED         => '已经收藏过了',
    CodeConstant::COLLECT_FAILED       => '收藏失败',
    CodeConstant::NOT_COLLECTED        => '还未收藏',
    CodeConstant::CANCEL_COLLECT_FAILED => '取消收藏失败',
    CodeConstant::POINT_OF_LIKE         => '已经点赞了',
    CodeConstant::VOTE_UP_FAILED       => '点赞失败',
    CodeConstant::NOT_POINT_LIKE       => '还未点赞',
    CodeConstant::CANCEL_VOTE_UP_FAILED=> '取消点赞失败',
    CodeConstant::USER_TOKEN_NOT_EXISTS     => 'TOKEN 无效!',
    CodeConstant::USER_TOKEN_OVERDUE        => 'TOKEN 失效!',

    /** 登陆  **/
    CodeConstant::USER_NOT_EXISTS      => '该用户不存在!',
    CodeConstant::USER_PASSWORD_ERROR  => '密码错误!',
    CodeConstant::USER_IS_LOCKED       => '用户被锁',
    CodeConstant::USER_THIRD_LOGIN_FAILED => '第三方登陆失败',
    CodeConstant::USER_THIRD_REGISTER_FAILED => '第三方用户注册失败',
    CodeConstant::UNKNOWN_THIRD_TYPE   => '未知第三方类型',
    CodeConstant::USER_THIRD_INVALID   => '非法第三方用户',
    CodeConstant::USER_TOKEN_CREATE_FAILED  => 'TOKEN 生成失败!',
    CodeConstant::USER_TOKEN_REFRESH_FAILED => 'TOKEN 刷新失败!',
    CodeConstant::USER_CLEAR_TOKEN_FAILED          => 'token清除失败',

    /** 注册 **/
    CodeConstant::USER_IS_EXISTS          => '该用户名以注册',
    CodeConstant::REGISTER_NAME_SENSITIVE => '用户名中有敏感词',
    CodeConstant::REGISTER_NICK_SENSITIVE => '昵称中有敏感词',
    CodeConstant::NICK_NAME_IS_EXISTS     => '该昵称已被使用',
    CodeConstant::USER_MOBILE_IS_EXISTS   => '手机号已被注册',
    CodeConstant::REGISTER_FAIL           => '注册失败!',
    CodeConstant::MOBILE_FORMAT_ERROR     => '手机号格式错误',
    CodeConstant::USER_NAME_FORMAT_INVALID => '用户名格式不正确',
    CodeConstant::CREATE_INVITATION_CODE_FAILED => '验证码生成失败',

    /** 找回密码 **/
    CodeConstant::MOBILE_NOT_REGISTER     => '手机号还未注册!',
    CodeConstant::FORGET_PASSWORD_FAILED   => '找回密码失败',

    /** 修改用户资料 **/
    CodeConstant::CHANGE_PASSWORD_FAILED  => '密码修改失败!',
    CodeConstant::CHANGE_USER_AVATAR_FAILED => '头像更新失败',
    CodeConstant::CHANGE_USER_INFO_FAILED => '用户信息更新失败',
    CodeConstant::CHANGE_USER_GENDER_FAILED => '性别修改失败',
    CodeConstant::CHANGE_USER_BIRTHDAY_FAILED => '生日修改失败',
    CodeConstant::CHANGE_USER_NICK_NAME_FAILED => '修改昵称失败',
    CodeConstant::USER_OLD_PASSWORD_INVALID => '密码错误',

    /** sign module **/
    CodeConstant::USER_IS_SIGNED          => '真积极，不过您今天已经签到了，希望明天也是这么积极！',
    CodeConstant::USER_SIGN_FAILED        => '签到失败了!',

    /** verify code module **/
    CodeConstant::MOBILE_NOT_VALIDITY     => '手机号格式不合法',
    CodeConstant::CODE_NOT_VALIDITY       => '验证码格式不正确',
    CodeConstant::CODE_CHECKED_FAILED     => '验证码校验失败',

    /** credits **/
    CodeConstant::ADD_CREDIT_FAILED       => '添加积分失败',
    CodeConstant::UPDATE_CREDIT_FAILED    => '跟新积分失败',

    /** 用户反馈 */
    CodeConstant::FEEDBACK_CONTENT_INVALID         => '反馈内容不能为空',
    CodeConstant::USER_FEEDBACK_FAILED             => '反馈失败',

    /** 用户消息 */
    CodeConstant::MESSAGE_NOT_EXISTS               => '消息不存在',
    CodeConstant::MESSAGE_READ_FORBIDDEN           => '无权限读取此消息',
    CodeConstant::MESSAGE_IS_READ                  => '您已经读过此消息了',
    CodeConstant::MESSAGE_READ_FAILED              => '消息读取失败',
    CodeConstant::MESSAGE_DELETE_FAILED            => '消息删除失败',
    CodeConstant::EMPTY_MESSAGE_FAILED             => '消息清空失败',
    CodeConstant::SEND_USER_MESSAGE_FAILED         => '消息发送失败',
    CodeConstant::MESSAGE_IS_EXISTS                => '消息已经存在',

    /** 用户举报 */
    CodeConstant::REPORT_SOURCE_NOT_EXISTS         => '举报资源不存在',
    CodeConstant::REPORT_CONTENT_EMPTY             => '举报内容不能为空',
    CodeConstant::REPORT_IS_REPORTED               => '今天已经举报过了',
    CodeConstant::REPORT_FAILED                    => '举报失败',
    CodeConstant::REPORT_TYPE_NOT_EXISTS           => '举报的内容类型不存在',

    /** upload file **/
    CodeConstant::UPLOAD_FILE_MIME_ERROR      => '文件mime类型错误',
    CodeConstant::UPLOAD_FILE_SIZE_BIG        => '文件过大',
    CodeConstant::UPLOAD_FILE_SIZE_SMALL      => '文件太小',
    CodeConstant::UPLOAD_FILE_EXTENSION_ERROR => '文件扩展名错误',
    CodeConstant::UPLOAD_FILE_REQUIRED_ERROR  => '文件错误',
    CodeConstant::UPLOAD_FILE_TOO_MANY        => '上传文件数量过多',
    CodeConstant::UPLOAD_FILE_FAILED          => '文件上传失败',

    /** bbs-plate **/
    CodeConstant::BBS_PLATE_NOT_EXISTS             => '该板块已被删除',
    CodeConstant::BBS_CREATE_PLATE_FAILED          => '板块创建失败',
    CodeConstant::BBS_CREATE_POST_FAILED           => '发帖失败',
    CodeConstant::BBS_POST_COMMENT_FAILED          => '评论帖子失败',
    CodeConstant::BBS_POST_NOT_EXISTS              => '帖子已删除',
    CodeConstant::BBS_POST_FORBIDDEN_ACCOUNT       => '禁止访问的帖子',
    CodeConstant::BBS_DELETE_POST_FORBIDDEN        => '您没有权限删除此帖子',
    CodeConstant::BBS_DELETE_POST_FAILED           => '帖子删除失败',

    /** post-comment */
    CodeConstant::BBS_POST_COMMENT_NOT_EXISTS      => '评论不存在',
    CodeConstant::DELETE_POST_COMMENT_FORBIDDEN    => '您没有权限删除此评论',
    CodeConstant::DELETE_POST_COMMENT_FAILED       => '评论删除失败',
    CodeConstant::BBS_BE_RELATED_FAILED            => '关联吧失败',

    /** tag */
    CodeConstant::TAG_IS_EXISTS                    => '标签已经存在',
    CodeConstant::CREATE_TAG_FAILED                => '标签创建失败',
];
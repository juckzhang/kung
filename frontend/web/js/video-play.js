/**
 * Created by Administrator on 2016/12/12 0012.
 */
document.write("<script language='javascript' src='/js/bx-slider/bx-slider.js'></script>");
document.write("<script src='/js/common/pagination.js'></script>");
//document.write('<script type="text/javascript" src="http://yuntv.letv.com/bcloud.js"></script>');
// var urljs = '';
// if('https:' == document.location.protocol) {
//     // urljs = "https://s.yuntv.letv.com/player/vod/bcloud.js";
//     document.write("<script type='text/javascript' charset='utf-8' src='https://s.yuntv.letv.com/player/vod/bcloud.js'></script>");
// } else {
//     // urljs = "http://yuntv.letv.com/player/vod/bcloud.js";
//     document.write("<script type='text/javascript' charset='utf-8' src='http://yuntv.letv.com/player/vod/bcloud.js'></script>");
// }
// var script = document.createElement('script');
// script.src = urljs;
// document.head.appendChild(script);

document.write("<script src='/js/common/sinaFaceAndEffec.js'></script>");

//获取评论
(function($,window){
    var comment = {
        pageCount:0,
        dataCount:0,
        currentPage:0,
        opt:{
            albumId:null,
            prePage:10,
            apiUrl:''
        },
        isInit:false,
        refresh:function(data){
            var dataList = data.dataList,
                count    = dataList.length;
            this.pageCount = data.pageCount;
            this.dataCount = data.dataCount;
            if( count == 0 ) return;

            //构造数据
            var append = '<h5>最新评论</h5>';
            for(var i = 0; i < count; ++i)
            {
                var item = dataList[i],
                    date = this.parseDate(item.create_time),
                    user    = item.user,
                    content = AnalyticEmotion(item.content);
                if(item.is_mobile == 1){
                    var _from = '来自最影客户端';
                }else {
                    var _from = '';
                }
                append += '<div class="comment-item"><div class="comment-user"><span>'+user.nick_name+'</span><span>'+_from+'</span><span class="comment-time">'+date+
                        '</span></div><div class="comment-content">'+ content +'</div></div>';
                //AnalyticEmotion(item.content)
            }
            $('.comment-num').text(this.dataCount);
            $('#current-page').text(this.currentPage + 1);
            $('.new-comment-list').html(append);
            if(this.isInit == false)
            {
                //分页
                $("#Pagination").pagination(data.pageCount,{
                    current_page:this.currentPage,
                    link_to:'javascript:void(0);',
                    callback:function(new_current_page){
                        comment.currentPage = new_current_page;
                        comment.get_data();
                        return true;
                    }
                });
                //显示数量
                this.isInit = true;
            }
            if(this.dataCount > 0){$('.pages .comment-num-info').show();}
            return this;
        },
        get_data:function(){
            $.post(this.opt.apiUrl, {page:this.currentPage,prePage:this.opt.prePage,albumId: this.opt.albumId},
                function(result){
                    if(result.code != 200) {alert(result.resultDesc);return;}
                    comment.refresh(result.data);
                },'json');
        },
        parseDate:function(date){
            var currentTime = new Date(),
                currentYear  = currentTime.getFullYear(),
                currentMonth = currentTime.getMonth(),
                currentDay  = currentTime.getDate(),
                currentHour = currentTime.getHours(),
                currentMinutes = currentTime.getMinutes(),

                commentDate = new Date(parseInt(date) * 1000),
                commentYear = commentDate.getFullYear(),
                commentMonth = commentDate.getMonth(),
                commentDay   = commentDate.getDate(),
                commentHour  = commentDate.getHours(),
                commentMinutes = commentDate.getMinutes();

            //几年前
            if(currentYear != commentYear) return Math.abs(currentYear - commentYear) + '年前';

            //一年以内
            if(currentMonth != commentMonth) return Math.abs(currentMonth - commentMonth) + '月前';

            //一个月内
            if(currentDay != commentDay) return Math.abs(currentDay - commentDay) + '天前';

            //一天内
            if(commentHour != currentHour) return Math.abs(currentHour - commentHour) + '小时前';

            //一个小时内
            if(commentMinutes != currentMinutes) return Math.abs(currentMinutes - commentMinutes) + '分钟前';

            return '刚刚';
        },
        init:function(opts){
            $.extend(this.opt,opts);
            this.get_data();
        }
    };

    //评论
    $.comment = function(opt){
        comment.init(opt);
        return comment;
    };

    var videoPlay = {
        opts:{
            concernid:'',
            albumId:0,
            subscribeApi:'',
            concernApi:'',
            commentApi:'',
            collectApi:'',
            loginApi:'/site/login.html',
            isLogin:0
        },
        subscribe:function(){
            $('#subscribe').click(function(){
                var subscribeObj = this;
                if(! videoPlay.opts.isLogin) {videoPlay.login();return;}
                //判断是否已经订阅
                var isSubscribe = parseInt($(this).data('subscribe'));
                if(isSubscribe > 0) return ;
                $.post(videoPlay.opts.subscribeApi,{albumId:videoPlay.opts.albumId},function(result){
                    console.log(result);
                    if(result.code != 200) {alert(result.resultDesc);return;}
                    $(subscribeObj).html('已订阅');
                    $(subscribeObj).data('subscribe',1);
                    $(subscribeObj).removeClass('hand');
                    //订阅数加1
                    var subscribeNumObj = $('#subscribe-num'),
                        subscribeNum    = parseInt(subscribeNumObj.data('subscribe-num')) + 1;
                    subscribeNumObj.data('subscribe-num',subscribeNum);
                    videoPlay.parseSubscribeNum();
                },'json');
            });
        },
        concern:function () {
            $('#concern').click(function () {
                var concernObj = this;
                if(! videoPlay.opts.isLogin) {videoPlay.login();return;}
                //判断是否已经关注
                var isConcern = $(this).data('concern');
                if(isConcern) return;
                $.post(videoPlay.opts.concernApi,{concernid:videoPlay.opts.concernid},function(result){
                    console.log(result);
                    if(result.code != 200) {alert(result.resultDesc);return;}
                    $(concernObj).html('已关注');
                    $(concernObj).data('concern',1);
                    $(concernObj).removeClass('hand');
                    //订阅数加1
                    var concernNumObj = $('#concernNum'),
                        concernNum    = parseInt(concernNumObj.data('concern-num')) + 1;
                    concernNumObj.data('concern-num',concernNum);
                    videoPlay.parseconcernNum();
                },'json');

            });
        },
        comment:function(){
            $('#comment').click(function(){
                if(! videoPlay.opts.isLogin) {videoPlay.login();return;}
                var content = $.trim($('#comment-input').val()),
                    contentLength = content.length;
                if(! contentLength) return;
                $.post(videoPlay.opts.commentApi,{albumId:videoPlay.opts.albumId,content:content},function(result){
                    if(result.code != 200){alert(result.resultDesc);return;}
                    //删除评论框内容
                    $('#comment-input').val('');
                    //刷新最新评论
                    comment.isInit = false;
                    comment.currentPage = 0;
                    comment.get_data();
                },'json');
            });
        },
        collect:function(){
            $('#collect').click(function(){
                var collectObj = this;
                if(! videoPlay.opts.isLogin) {videoPlay.login();return;}
                var isCollected = parseInt($(this).data('collected'));
                if(isCollected > 0) return ;
                $.post(videoPlay.opts.collectApi,{albumId:videoPlay.opts.albumId},function(result){
                    if(result.code != 200){alert(result.resultDesc);return;}

                    //$(collectObj).text('已收藏');
                    $(collectObj).data('collected',1);
                    $(collectObj).addClass('collections');
                    $(collectObj).removeClass('hand');
                    $(collectObj).removeClass('collection');
                },'json');
            });
        },
        parseSubscribeNum:function(){
            var subscribeObj = $('#subscribe-num'),
                subscribeNum = parseInt(subscribeObj.data('subscribe-num'));
            if(subscribeNum >= 10000){
                subscribeNum = (subscribeNum / 10000).toFixed(1) + '万';
            }

            subscribeNum += " 粉丝";
            subscribeObj.html(subscribeNum);
        },
        parseconcernNum:function () {
          var  concernObj = $("#concernNum"),
               concernNum = parseInt(concernObj.data('concern-num'));
            if(concernNum >= 10000){
                concernNum = (concernNum / 10000).toFixed(1) + '万';
            }

            concernNum += " 粉丝";
            concernObj.html(concernNum);

        },
        login:function(){
            location.href = this.opts.loginApi;
        },
        init:function(opts){
            $.extend(this.opts,opts);
            this.subscribe();
            this.concern();
            this.comment();
            this.collect();
            this.parseSubscribeNum();
        }
    };
    $.videoPlay = function(opt){
        videoPlay.init(opt);
        return videoPlay;
    };
})(jQuery,window);

$(function(){
    //楼层播放
    $('.scroll').bxSlider({
        slideWidth: 1000,
        minSlides: 4,
        maxSlides: 4,
        moveSlides: 4,
        slideMargin: 15,
        pager: false
    });
    // 绑定表情
    // $('#bao').SinaEmotion($('.emotion'));

    //评论输入框
    $('#comment-input').on('propertychange input',function(){
        var value = $(this).val(),
            val_len = value.length;
        if(val_len > 255) value = value.substr(0,255);
        var valueLen = value.length,
            allowLen = 255 - value.length,
            color = '#292929';
        $('#input-num').text(valueLen);
        $('#allow-num').text(allowLen);
        $(this).val(value);
        if(allowLen < 10) color = '#eeae05';
        $('#allow-num').css('color',color);
    });

    //加载播放器
    var uu  = $('#video-play').data('uu'),
        vu  = $('#video-play').data('vu'),
        snum = $('#video-play').data('snum'),
        lecloud_player_conf = {
            vu:vu,
            // uu:uu,
            // auto_play:1,
            //"gpcflag":1,
            "posterType":2,
            "width": $('#video-play').width(),
            "height":$('#video-play').height(),
            "background-color":transparent,
        },
        player = new CloudVodPlayer();
    if (snum == 0) {
        lecloud_player_conf['uu'] = 'fvaz7udkhj';
        lecloud_player_conf['pu'] = 'e7ecb80b17';
    }
    if (snum == 1) {
        lecloud_player_conf['uu'] = 'fvaz7udkhj';
        lecloud_player_conf['p']  = '102';
        lecloud_player_conf['pu'] = '3108';
    }
    if (snum == 2) {
        lecloud_player_conf['uu'] = '624310fa63';
        lecloud_player_conf['pu'] = '31ff838ee5';
    }
    player.init(lecloud_player_conf,'video-play');

    //播放选集
    $('.albums').click(function(){
        var uu = $(this).data('uu'),
            vu = $(this).data('vu');

       var videoid = $(this).data('videoid');
       var albumid = $(this).data('albumid');
        player.sdk.playNewId({uu:uu,vu:vu});
        //修改样式
        $('.albums').removeClass('active');
        $(this).addClass('active');
        clickrate(videoid,albumid);
    });
});

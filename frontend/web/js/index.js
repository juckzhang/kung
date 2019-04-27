/**
 * Created by Administrator on 2016/12/12 0012.
 */

//document.write("<script language='javascript' src='/js/picCarousel/js/PicCarousel.js'></script>");
document.write("<script language='javascript' src='/js/bx-slider/bx-slider.js'></script>");
document.write("<script language='javascript' src='/js/common/unslider.js'></script>");
$(function(){
    //轮播
    $('.banner').unslider({
        autoplay: false,
        infinite: false,
        arrows: {
            'next': '<a class="sort-order-arrow unslider-arrow next" style="right: -75px;"></a>',
            'prev': '<a class="sort-order-arrow unslider-arrow prev" style="left: -75px;"></a>'
        }
    });

    $('.slider a.next').css({background:'url(/images/slider-right.png) no-repeat left center','background-size': '15px auto',right:'25px',top:'230px'});
    $('.slider a.prev').css({background:'url(/images/slider-left.png) no-repeat left center','background-size': '15px auto',left:'25px',top:'230px'})
        .css({'-moz-transform':'rotate(0deg)','-webkit-transform':'rotate(0deg)',transform:'rotate(0deb)'});


    //楼层播放
    $('.scroll').bxSlider({
        slideWidth: 1000,
        minSlides: 4,
        maxSlides: 4,
        moveSlides: 4,
        slideMargin: 15,
        pager: false
    });


    var firstOne   = $('.video').first().offset().left;
    //查看视频信息
    // $(document).on('mouseover','.video',function(e){
    //     //隐藏所有的info信息
    //     $('.video-info').hide();
    //     //计算left值
    //     var target = e.target,
    //         videoInfo = $('.content .video-info'),
    //         videoInfoWidth = videoInfo.outerWidth(),
    //         selfWidth      = $(target).width(),
    //         offsetLeft = $(target).offset().left,
    //         offsetTop  = $(target).offset().top,
    //
    //         //默认偏移量
    //         left       = offsetLeft + selfWidth + 2,
    //         top        = offsetTop + 5,
    //         //获取简介信息以及跳转地址
    //         introduction = $(target).data('introduction'),
    //         href      = $(target).data('href'),
    //         release   = $(target).data('release'),
    //         name      = $(target).data('name'),
    //         createTime = $(target).data('create-time');
    //     videoInfo.find('.introduction').text(introduction);
    //     videoInfo.find('.release-day').text(release);
    //     videoInfo.find('.create-day').text(createTime);
    //     videoInfo.find('.name').text("《"+name+"》");
    //     videoInfo.find('a').attr('href',href);
    //
    //     if((left + videoInfoWidth - firstOne) > 950 ) left = offsetLeft - (videoInfoWidth  + 2);
    //     //设置位置
    //     videoInfo.css({left:left+'px',top:top+'px'});
    //     videoInfo.show();
    // });
    //查看视频信息
    $(document).on('mouseover','.video',function (e) {
        var _this = $(this);
        _this.find('i').removeClass('hide');
        _this.find('ul li').removeClass('hide');
    });

    $(document).on('mouseout','.video',function (e) {
        var _this = $(this);
        _this.find('i').addClass('hide');
        _this.find('ul li:eq(2)').addClass('hide');
        _this.find('ul li:eq(3)').addClass('hide');
    });

    //banner切换
    $(document).on('mouseover','.banner-list .video',function (e) {
        var _this = $(this);
        var _link = _this.find('a').attr('href');
        var _src = _this.find('img').data('src');
        var _title = _this.find('p').text();
        var _introduction = _this.find('img').attr('title');

        $('.banners .bannersa').attr('href',_link);
        $('.banners .bannersa').attr('title',_introduction);
        $('.banners .bannerimg').attr('src',_src);
        $('.banners .caption a').attr('href',_link);
        $('.banners .caption a').text(_title);

        $('.banners .ts').text(_introduction);

        //alert($('.banners .bannersa').attr('href'));
    });


    //顶部轮播
    $('.poster-item').click(function(){
        var href = $(this).data('href'),
            name = $(this).data('name'),
            introduction = $(this).data('introduction');

        $('#poster-href').attr('href',href);
        $('#album-name').html(name);
        $('#poster-introduction').html(introduction);
    });

    $(document).on('mouseleave','.video-info',function(){
        $('.video-info').hide();
    });

    $("header").addClass("headerAddbackgrounds");
    //当滚动条超过video-info时候隐藏
    $(document).on('scroll',function(){
        $('.video-info').hide();
        var height = $(window).scrollTop();
        // if(height > 2){
        //     $("header").addClass("headerAddbackground");
        //     $("header").removeClass("headerAddbackgrounds");
        // }else {
        //     $("header").addClass("headerAddbackgrounds");
        //     $("header").removeClass("headerAddbackground");
        // }
    });
    //$('.video-info').mouseout(function(){$(this).hide();});
    $(window).on('resize',function(){location.reload();});
});

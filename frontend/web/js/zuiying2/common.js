/*
 *
 *   common(header footer)
 *   common.js
 *
 */


$(document).ready(function () {

    $('body').scrollspy({
        target: '.navbar-fixed-top',
        offset: 80
    });

    // Page scrolling feature
    $('a.page-scroll').bind('click', function (e) {
        var link = $(this);
        $('html, body').stop().animate({
            scrollTop: $(link.attr('href')).offset().top - 50
        }, 500);
        event.preventDefault();
        $("#navbar").collapse('hide');
    });

    var cbpAnimatedHeader = (function () {
        var docElem = document.documentElement,
            header = document.querySelector('.navbar-default'),
            didScroll = false,
            changeHeaderOn = 200;

        function init() {
            window.addEventListener('scroll', function (event) {
                if (!didScroll) {
                    didScroll = true;
                    setTimeout(scrollPage, 250);
                }
            }, false);
        }

        function scrollPage() {
            var sy = scrollY();
            if (sy >= changeHeaderOn) {
                $(header).addClass('navbar-scroll')
            }
            else {
                $(header).removeClass('navbar-scroll')
            }
            didScroll = false;
        }

        function scrollY() {
            return window.pageYOffset || docElem.scrollTop;
        }

        init();

    })();

    // Activate WOW.js plugin for animation on scrol
    new WOW().init();

    var nav = $('#navbar');
    var navAvatar = nav.find('.ivu-dropdown');
    var footer = $("#footer");
    var footerCode = footer.find('.ivu-tooltip');
    navAvatar.hover(function () {
        //你要显示的层，div放到li里面。默认css属性是隐藏
        $(this).find('.ivu-select-dropdown').stop().slideDown();
    }, function () {
        //你要显示的列表形式
        $(this).find('.ivu-select-dropdown').stop().slideUp();
    });
    footerCode.hover(function () {
        //你要显示的层，div放到li里面。默认css属性是隐藏
        $(this).find('.ivu-tooltip-popper').stop().fadeIn(200);
    }, function () {
        //你要显示的列表形式
        $(this).find('.ivu-tooltip-popper').stop().fadeOut(200);
    });

    // 返回顶部 begin
    var goTop = $(".ivu-back-top");
    var timer = null;
    //获取页面可视区域的高度
    var clientHeight = $(window).height();
    //鼠标向下滑动，出现以及消失
    $(window).scroll(function () {
        var osTop = $(document).scrollTop();
        if (osTop >= 800) {
            goTop.addClass('ivu-back-top-show');
        } else {
            goTop.removeClass('ivu-back-top-show');
        }
    });
    goTop.click(function () {
        //设置定时器
        timer = setInterval(function () {
            //获取滚动条高度
            var osTop = $(document).scrollTop();
            //ispeed 最后等于0
            var ispeed = Math.floor(osTop / 5);
            //实现滚动条每次-100px；直到0;
            $(document).scrollTop(osTop - ispeed);
            //到0px后清除浮动
            //这里osTop==(osTop-ispeed)即ispeed等于0时 条件成立
            if (osTop == (osTop - ispeed)) {
                clearInterval(timer);
            }
        }, 30);
    });
    // 返回顶部 end
    //form -------------------------------------
    // selected
    var selectBox = $('.ivu-select');
    var checkboxBox = $('.ivu-checkbox-wrapper');
    var radioItem = $('.ivu-radio-wrapper');
    var btnShow = $('.btnShow');
    selectBox.bind('click', function (e) {
        var _this = $(this);
        var _selectDown = _this.find('.ivu-select-dropdown');
        if (_selectDown.css("display") == "none") {
            _selectDown.slideDown("fast");
            _this.addClass('ivu-select-visible');
        } else {
            _selectDown.slideUp("fast");
            _this.removeClass('ivu-select-visible');
        }
        _selectDown.find('li').click(function () {
            var txt = $(this).text();
            var cateId = $(this).data('id');
            console.log(txt);
            _this.find('span').html(txt).addClass('ivu-select-selected-value').removeClass('ivu-select-placeholder');
            _this.find('input').val(cateId);
            $("#cateId").val(cateId);
            _selectDown.slideUp("fast");
        });

    });
    //checkbox
    checkboxBox.bind('click', function (e) {
        var _this = $(this);
        if (_this.hasClass('ivu-checkbox-wrapper-checked')) {
            console.log(0)
            _this.removeClass('ivu-checkbox-wrapper-checked');
            _this.find('.ivu-checkbox').removeClass('ivu-checkbox-checked');
            _this.find('input').prop('checked', false);
        } else {
            _this.addClass('ivu-checkbox-wrapper-checked');
            _this.find('.ivu-checkbox').addClass('ivu-checkbox-checked');
            _this.parent().parent().removeClass('ivu-form-item-error');
            _this.find('.ivu-form-item-error-tip').remove();
            _this.find('input').prop('checked', true);
        }
    });
    //radioBox
    radioItem.bind('click', function (e) {
        var _this = $(this);
        var radioBox = _this.parent('.ivu-radio-group');
        var radioBoxItem = radioBox.find('.ivu-radio-group-item');
        for (var i = 0; i < radioBoxItem.length; i++) {
            if (_this.index() == i) {
                _this.addClass('ivu-radio-wrapper-checked');
                _this.find('.ivu-radio').addClass('ivu-radio-checked');
                _this.find('input').prop('checked', true);
                _this.siblings().removeClass('ivu-radio-wrapper-checked');
                _this.siblings().find('.ivu-radio').removeClass('ivu-radio-checked');
                _this.siblings().find('input').prop('checked', false);
            }
        }
    });
    //input
    (function () {
        $(':input').on('keyup', function (e) {
            var _this = $(this);
            _this.val().replace('\s+', '');
            if (_this.val() && _this.parents('.ivu-form-item').hasClass('ivu-form-item-error')) {
                _this.parents('.ivu-form-item').removeClass('ivu-form-item-error')
                _this.parents('.ivu-form-item-content').find('.ivu-form-item-error-tip').remove();

            }
        });
        $(":input").blur(function () {
            var _this = $(this);
            if (_this.val() == '' && !_this.parents('.ivu-form-item').hasClass('ivu-form-item-error')) {
                _this.parents('.ivu-form-item').addClass('ivu-form-item-error');
                _this.parents('.ivu-form-item-content').append('<div class="ivu-form-item-error-tip">' + '请输入...' + '</div>');
            }
            if (_this.find('.ivu-form-item-error-tip')) {
            }
        });
    })();
    //brn-show
    btnShow.bind('click', function (e) {
        console.log(0);
        if ($('.activity-sign-list').hasClass('active')) {
            $('.activity-sign-list').removeClass('active');
        } else {
            $('.activity-sign-list').addClass('active');
        }
    });
    //form end -------------------------------------
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "preventDuplicates": true,
        "positionClass": "toast-bottom-right",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    //活动报名
    var activitySign = $('#activitySign');
    var activitySignBtn = $('#activitySign').find('.ivu-btn');
    activitySignBtn.bind('click', function (e) {
        var activitySignParent = $(this).parents('#activitySign');
        var activitySignErrorBox = activitySignParent.find('.ivu-form-item');
        var activitySignCheckbox = activitySignParent.find('.ivu-checkbox-input');

        if (activitySignCheckbox.is(':checked')) {
            toastr.success('恭喜你');
        } else {
            toastr.warning('请阅读最影上传用户协议');
            activitySignErrorBox.addClass('ivu-form-item-error');
        }

    });
    //活动报名 end
    // video

    //表单登陆
    $('#login').click(function(){
        var mobile = $('#login-user').val(),
            remember = $('#remember').is(':checked') ? 1 :0,
            password = $('#login-password').val();
        if(! (/^1[3|5|7|8|9]\d{9}$/).test(mobile)){
            $('.ivu-form-item-content .waring-info').text('手机号格式不正确!');
            return false;
        }
        var postData = {
            username:mobile,
            password:password,
            rememberMe:remember,
        };
        $.post('/site/logins.html',{LoginForm:{username:mobile,password:password,rememberMe:remember}},function(result){
            console.log(result);
            if(result.code == 200)
                window.location.href = "/";
            else
                $('.ivu-form-item-content .waring-info').text(result.resultDesc);
        },'json');
    });

    // video end
    var viewport = document.querySelector("meta[name=viewport]");
    //下面是根据设备像素设置viewport
    if (window.devicePixelRatio == 1) {
        viewport.setAttribute('content', 'width=device-width,initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no');
    }
    if (window.devicePixelRatio == 2) {
        viewport.setAttribute('content', 'width=device-width,initial-scale=0.5, maximum-scale=0.5, minimum-scale=0.5, user-scalable=no');
    }
    if (window.devicePixelRatio == 3) {
        viewport.setAttribute('content', 'width=device-width,initial-scale=0.3333333333333333, maximum-scale=0.3333333333333333, minimum-scale=0.3333333333333333, user-scalable=no');
    }
    var docEl = document.documentElement;
    var fontsize = 10 * (docEl.clientWidth / 320) + 'px';
    docEl.style.fontSize = fontsize;

});

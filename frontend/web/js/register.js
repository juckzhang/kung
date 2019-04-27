//获取评论
(function($,window) {
    var sendCode = {
        time:0,
        opt:{apiUrl:''},
        checkMobile:function(mobile){
            var reg = /^1[3|4|5|8|7][0-9]\d{4,8}$/;
            if(reg.test(mobile)){
                return true;
            }
            return false;
        },
        send:function(){
            var mobile = $('#mobile').val();
            //判断手机号是不是正确
            if(!this.checkMobile(mobile)){this.alertMsg('手机号格式不正确');return;}
            $.post(this.opt.apiUrl,{mobile:mobile},function(result){
                if(result.code == 200)
                {
                    //获取expireTime
                    sendCode.autoIncrement(result.data.expires);
                }
                else{
                    sendCode.alertMsg(result.resultDesc);
                }
            },'json');
        },
        autoIncrement:function(expireTime){
            this.time = expireTime;
            var func = setInterval(function(){
                if(sendCode.time <= 0 )
                {
                    clearInterval(func);
                    $('.send-code').text('获取验证码')
                }else{
                    sendCode.time -= 1;
                    $('.send-code').text(sendCode.time);
                }
            },1000);
        },
        alertMsg:function(msg){
            $('#register .waring-info').text(msg);
        },
        init:function(opt){
            $.extend(this.opt,opt);
        }
    };
    $.sendCode = function(opt){
        sendCode.init(opt);
        $('.send-code').click(function(){
            sendCode.send();
        });
    };

    /** register-btn **/
    var register = {
        opt:{apiUrl:''},
        checkMobile:function(mobile){
            var reg = /^1[3|4|5|8|7][0-9]\d{4,8}$/;
            if(reg.test(mobile)){
                return true;
            }
            return false;
        },
        register:function(){
            var mobile = $('#mobile').val(),
                password = $('#password').val(),
                repeatPassword = $('#repeat-password').val(),
                // agree   = $('#agree').is(':checked');
                code = $('#code').val();
                nick_name = $('#nick_name').val();
            if(!nick_name){
                $('.nick_name').text('请输入用户名');
                return;
            }

            //判断是否认同了本协议
            // if(agree == false) {this.alertMsg('请接受嘴影用户协议!');return;}
            //检查手机号
            if(!this.checkMobile(mobile)){$('.mobile').text('手机号格式不正确！');return;}
            //检查密码
            if(!this.checkPassword(password)){$('.password').text('密码必须是6-16位字母,数字,符号！');return;}
            //检查两次密码是否一致
            if(!this.checkRepeatPassword(password,repeatPassword)){$('.repeatPassword').text('两次密码输入不一致！');return;}
            //检查验证码
            if(!this.checkCode(code)){$('.code').text('验证码不正确！');return;}

            //进入注册主流程
            $('#register').submit();
            //$.post(this.opt.apiUrl,
            //    {RegisterForm:{mobile:mobile,password:password,repeatPassword:repeatPassword,code:code}},
            //    function(result){
            //        if(result.code !== 200){register.alertMsg(result.resultDesc);return;}
            //        else
            //            location.href = '/';
            //    },'json');
        },
        checkPassword:function(password){
            var reg = /^\w{6,16}$/;
            if(reg.test(password)) return true;
            return false;
        },
        checkRepeatPassword:function(password,repeatPassword){
            return !!(password == repeatPassword);
        },
        checkCode:function(code){
            var reg = /^\d{6}$/;
            if(reg.test(code)) return true;
            return false;
        },
        alertMsg:function(msg){
            $('#register .waring-info').text(msg);
        },
        init:function(opt){
            $.extend(register.opt,opt);
        }
    };
    $.register = function(opt){
        register.init(opt);
        $('#register-btn').click(function(){
            register.register();
        });
        //鼠标聚焦输入框
        $('#mobile,#password,#repeat-password,#code').focus(function(){
            //错误提示信息置空
            $('#register .waring-info').text('');
        });
    };


    /** password-btn **/
    var password = {
        opt:{apiUrl:''},
        checkMobile:function(mobile){
            var reg = /^1[3|4|5|8|7][0-9]\d{4,8}$/;
            if(reg.test(mobile)){
                return true;
            }
            return false;
        },
        password:function(){
            var mobile = $('#mobile').val(),
                password = $('#passwords').val(),
                repeatPassword = $('#repeat-passwords').val();



            code = $('#code').val();
            //检查手机号
            if(!this.checkMobile(mobile)){this.alertMsg('手机号格式不正确！');return;}
            //检查密码
            if(!this.checkPassword(password)){this.alertMsg('密码必须是6-16位字母,数字,符号！');return;}
            //检查两次密码是否一致
            if(!this.checkRepeatPassword(password,repeatPassword)){this.alertMsg('两次密码输入不一致！');return;}
            //检查验证码
            if(!this.checkCode(code)){this.alertMsg('验证码不正确！');return;}

            //进入注册主流程
            $('#password').submit();
            //$.post(this.opt.apiUrl,
            //    {RegisterForm:{mobile:mobile,password:password,repeatPassword:repeatPassword,code:code}},
            //    function(result){
            //        if(result.code !== 200){register.alertMsg(result.resultDesc);return;}
            //        else
            //            location.href = '/';
            //    },'json');
        },
        checkPassword:function(password){
            var reg = /^\w{6,16}$/;
            if(reg.test(password)) return true;
            return false;
        },
        checkRepeatPassword:function(password,repeatPassword){
            return !!(password == repeatPassword);
        },
        checkCode:function(code){
            var reg = /^\d{6}$/;
            if(reg.test(code)) return true;
            return false;
        },
        alertMsg:function(msg){
            $('#password .waring-info').text(msg);
        },
        init:function(opt){
            $.extend(register.opt,opt);
        }
    };
    $.password = function(opt){
        password.init(opt);
        $('#password-btn').click(function(){
            password.password();
        });
        //鼠标聚焦输入框
        $('#mobile,#password,#repeat-passwords,#code').focus(function(){
            //错误提示信息置空
            $('#password .waring-info').text('');
        });
    }
})(jQuery,window);

$(function(){
    $('.area').mouseout(function(){$(this).hide();});
    $('.area li').click(function(){$('#area').text($(this).text());$('.area').hide();});
    $('.arrow-bottom').click(function(){$('.area').show();});

});

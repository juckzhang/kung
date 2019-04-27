/*!

 =========================================================
 * Paper Bootstrap Wizard - v1.0.2
 =========================================================

 * Product Page: https://www.creative-tim.com/product/paper-bootstrap-wizard
 * Copyright 2017 Creative Tim (#)
 * Licensed under MIT (https://github.com/creativetimofficial/paper-bootstrap-wizard/blob/master/LICENSE.md)

 =========================================================

 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 */

// Paper Bootstrap Wizard Functions

searchVisible = 0;
transparent = true;

$(document).ready(function () {
    $.validator.addMethod("timeLength", function (value, element, params) {
        var timeLength = /^([0-1]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/;
        return this.optional(element) || (timeLength.test(value));
    }, '标准格式为00:00:00，请满足"时:分:秒"的基本规则');
    $.validator.addMethod("noChinese", function (value, element, params) {
        var noChinese = /^[^\u4e00-\u9fa5]{0,}$/;
        return this.optional(element) || (noChinese.test(value));
    }, '禁止输入中文');
    $.validator.addMethod("noEnglish", function (value, element, params) {
        var noEnglish = /^[^[A-Za-z ]*]{0,}$/;
        return this.optional(element) || (noEnglish.test(value));
    }, '禁止输入英文');
    $.validator.addMethod("onlyNum", function (value, element, params) {
        var onlyNum = /[\d]/g;
        return this.optional(element) || (onlyNum.test(value));
    }, '只能输入数字');

    $.extend($.validator.messages, {
        required: "这是必填字段",
        remote: "请修正此字段",
        email: "请输入有效的电子邮件地址",
        url: "请输入有效的网址",
        date: "请输入有效的日期",
        dateISO: "请输入有效的日期 (YYYY-MM-DD)",
        number: "请输入有效的数字",
        digits: "只能输入数字",
        creditcard: "请输入有效的信用卡号码",
        equalTo: "你的输入不相同",
        extension: "请输入有效的后缀",
        maxlength: $.validator.format("最多可以输入 {0} 个字符"),
        minlength: $.validator.format("最少要输入 {0} 个字符"),
        rangelength: $.validator.format("请输入长度在 {0} 到 {1} 之间的字符串"),
        range: $.validator.format("请输入范围在 {0} 到 {1} 之间的数值"),
        max: $.validator.format("请输入不大于 {0} 的数值"),
        min: $.validator.format("请输入不小于 {0} 的数值")
    });
    // Code for the Validator
    var $validator = $('.wizard-card form').validate({
        rules: {
            videoClassification: {
                required: true,
                minlength: 1
            },
            videoCategory: {
                required: true,
                minlength: 1
            },
            videoSubject: {
                required: true,
                minlength: 1
            },
            videoLength: {
                required: true,
                noChinese: true,
                noEnglish: true,
                timeLength: true

            },
            directorSex1: {
                required: true,
                minlength: 1
            },
            directorSex2: {
                required: true,
                minlength: 1
            },
            directorSex3: {
                required: true,
                minlength: 1
            },
            directorSex4: {
                required: true,
                minlength: 1
            },
            directorSex5: {
                required: true,
                minlength: 1
            },
            performerSex1: {
                required: true,
                minlength: 1
            },
            performerSex2: {
                required: true,
                minlength: 1
            },
            performerSex3: {
                required: true,
                minlength: 1
            },
            performerSex4: {
                required: true,
                minlength: 1
            },
            performerSex5: {
                required: true,
                minlength: 1
            },
            productionMail: {
                required: true,
                noChinese: true,
                email: true
            },
            productionPhone: {
                required: true,
                noChinese: true,
                noEnglish: true,
                onlyNum: true
            },
            productionGender: {
                required: true,
                minlength: 1
            }
        },
    });

    /*  Activate the tooltips      */
    $('[rel="tooltip"]').tooltip();
    // Wizard Initialization
    $('.wizard-card').bootstrapWizard({
        'tabClass': 'nav nav-pills',
        'nextSelector': '.btn-next',
        'previousSelector': '.btn-previous',

        onNext: function (tab, navigation, index) {
            var $valid = $('.wizard-card form').valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            $('.steps').find('li').eq(index - 1).addClass('done')
            if (index == 6) {
                //    接口
                // alert("此处提交数据");
                var videoClassificationArray = new Array();
                var videoCategoryArray = new Array();
                var videoSubjectArray = new Array();
                $('input[name="videoClassification"]:checked').each(function () {
                    videoClassificationArray.push($(this).val());//向数组中添加元素
                });
                $('input[name="videoCategory"]:checked').each(function () {
                    videoCategoryArray.push($(this).val());//向数组中添加元素
                });
                $('input[name="videoSubject"]:checked').each(function () {
                    videoSubjectArray.push($(this).val());//向数组中添加元素
                });
                var videoClassificationData = videoClassificationArray.join(',');//将数组元素连接起来以构建一个字符串
                var videoCategoryData = videoCategoryArray.join(',');//将数组元素连接起来以构建一个字符串
                var videoSubjectData = videoSubjectArray.join(',');//将数组元素连接起来以构建一个字符串
                console.log(videoClassificationData);
                var activityData = {
                    "signup_id":$("#signup_id").val(),
                    "filmfest_id":$("#film_id").val(),
                    "user_id":$("#user_id").val(),
                    //step1
                    "videoName": $("#videoName").val(),
                    "videoLanguage": $("#videoLanguage").find("option:selected").val(),
                    "videoClassification": videoClassificationData,
                    "videoCategory": videoCategoryData,
                    "videoSubject": videoSubjectData,
                    "videoSubtitle": $("#videoSubtitle").find("option:selected").val(),
                    "videoLength": $("#videoLength").val(),
                    "soundFormat": $("#soundFormat").find("option:selected").val(),
                    "videoForms": $("#videoForms").find("option:selected").val(),
                    "videoSpecifications": $("#videoSpecifications").find("option:selected").val(),
                    "videoColor": $("#videoColor").find("option:selected").val(),
                    "videoFormat": $("#videoFormat").find("option:selected").val(),
                    "filmingCountry": $("#filmingCountry").val(),
                    "dataOnline": $("#dataOnline").val(),
                    "dataFinish": $("#dataFinish").val(),

                    //step2
                    "director":[{
                        "directorName": $("#directorName1").val(),
                        "directorSex": $("input[name='directorSex1']:checked").val(),
                        "directorCapital": $("#directorCapital1").val(),
                        "directorNationality": $("#directorNationality1").val(),
                        "directorBirthday": $("#directorBirthday1").val(),
                        "directorAddress": $("#directorAddress1").val(),
                    }],
                    //step3
                    "performer":[{
                        "performerName": $("#performerName1").val(),
                        "performerSex": $("input[name='performerSex1']:checked").val(),
                        "performerCapital": $("#performerCapital1").val(),
                        "performerNationality": $("#performerNationality1").val(),
                        "performerBirthday": $("#performerBirthday1").val(),
                        "performerAddress": $("#performerAddress1").val(),
                    }],

                    //step4
                    "productionCompany": $("#productionCompany").val(),
                    "productionAddr": $("#productionAddr").val(),
                    "productionPhone": $("#productionPhone").val(),
                    "productionFaxNumber": $("#productionFaxNumber").val(),
                    "productionMail": $("#productionMail").val(),
                    "productionPrincipal": $("#productionPrincipal").val(),
                    "productionGender": $("input[name='productionGender']:checked").val(),
                    "productionDepartment": $("#productionDepartment").val(),
                    "productionDepartmentTitle": $("#productionDepartmentTitle").val(),
                    //step5
                    "workerProduction": $("#workerProduction").val(),
                    "workerCamerist": $("#workerCamerist").val(),
                    "workerWriter": $("#workerWriter").val(),
                    "workerMakeUpArtist": $("#workerMakeUpArtist").val(),
                    "workerFilmCutter": $("#workerFilmCutter").val(),
                    "workerArtist": $("#workerArtist").val(),
                    "workerAnimation": $("#workerAnimation").val(),
                    "workerSpecialEffects": $("#workerSpecialEffects").val(),
                    "workerMusic": $("#workerMusic").val(),
                    //step6
                    "notesText": $("#notesText").val(),
                };
                console.log(activityData);
                var directorBox = $("#directorBox");
                var directorBoxItems = $("#directorBox").find(".person-inf-box");
                var performerBox = $("#performerBox");
                var performerBoxItems = $("#performerBox").find(".person-inf-box");
                if (directorBoxItems.length > 1) {
                    for (var i = 0; i < directorBoxItems.length - 1; i++) {
                        var j = i + 2;
                        var directors = {
                            "directorName": $("#" + "directorName" + j).val(),
                            "directorSex": directorBoxItems.eq(i + 1).find("input:radio:checked").val(),
                            "directorCapital": $("#" + "directorCapital" + j).val(),
                            "directorNationality": $("#" + "directorNationality" + j).val(),
                            "directorBirthday": $("#" + "directorBirthday" + j).val(),
                            "directorAddress": $("#" + "directorAddress" + j).val(),
                        };
                        activityData.director.push(directors);

                        // activityData["directorName" + j] = $("#" + "directorName" + j).val();
                        // activityData["directorSex" + j] = directorBoxItems.eq(i + 1).find("input:radio:checked").val();
                        // activityData["directorCapital" + j] = $("#" + "directorCapital" + j).val();
                        // activityData["directorNationality" + j] = $("#" + "directorNationality" + j).val();
                        // activityData["directorBirthday" + j] = $("#" + "directorBirthday" + j).val();
                        // activityData["directorAddress" + j] = $("#" + "directorAddress" + j).val();
                    }
                }
                if (performerBoxItems.length > 1) {
                    for (var i = 0; i < performerBoxItems.length - 1; i++) {
                        var j = i + 2;
                        var performers = {
                            "performerName": $("#" + "performerName" + j).val(),
                            "performerSex": performerBoxItems.eq(i + 1).find("input:radio:checked").val(),
                            "performerCapital": $("#" + "performerCapital" + j).val(),
                            "performerNationality": $("#" + "performerNationality" + j).val(),
                            "performerBirthday": $("#" + "performerBirthday" + j).val(),
                            "performerAddress": $("#" + "performerAddress" + j).val(),
                        };
                        activityData.performer.push(performers);


                        // activityData["performerName" + j] = $("#" + "performerName" + j).val();
                        // activityData["performerSex" + j] = performerBoxItems.eq(i + 1).find("input:radio:checked").val();
                        // activityData["performerCapital" + j] = $("#" + "performerCapital" + j).val();
                        // activityData["performerNationality" + j] = $("#" + "performerNationality" + j).val();
                        // activityData["performerBirthday" + j] = $("#" + "performerBirthday" + j).val();
                        // activityData["performerAddress" + j] = $("#" + "performerAddress" + j).val();
                    }
                }
                // $("#btn-finish").click(function () {
                //     $.ajax({
                //         url: "/filmfest/signup-update.html",
                //         type: "post",
                //         cache: false,
                //         data: activityData,
                //         success: function (data) {
                //             alert("报名成功")
                //             console.log(data);
                //             if(data.code == 200){
                //                 console.log(data);
                //                 // loaddown('/filmfest/filmfest-pay.html','myfilm','centerbody',{});
                //             }else {
                //                 alert('报名失败');
                //             }
                //         },
                //         error: function (e) {
                //             alert("报名失败");
                //         }
                //     });
                // });
            }
            if (index == 6) {
                $.ajax({
                    url: "/filmfest/signup-update.html",
                    type: "post",
                    cache: false,
                    data: activityData,
                    success: function (data) {
                        alert("报名成功")
                        console.log(data);
                        if(data.code == 200){
                            console.log(data);
                            // loaddown('/filmfest/filmfest-pay.html?order_no='+data.data,'myfilm','centerbody',{});
                            window.location = "/filmfest/filmfest-pay.html?order_no="+data.data;
                        }else {
                            alert('报名失败');
                        }
                    },
                    error: function (e) {
                        alert("报名失败");
                    }
                });
                // var time = setInterval(showTime, 1000);
                // var second = 1000;
                //
                // function showTime() {
                //     $("#pageJump").html(
                //         '<p class="times">页面将在 <strong id="times">' + second + '</strong> 秒钟后，跳转到 <a href="/filmfest/filmfest-pay.html" class="c-blue">支付页面</a></p>' +
                //         '<p>支付成功后我们会已短信通知你</p>');
                //     if (second == 0) {
                //         window.location = "/filmfest/filmfest-pay.html";
                //         clearInterval(time);
                //     }
                //     second--;
                // }
            }
        },

        onInit: function (tab, navigation, index) {

            //check number of tabs and fill the entire row
            var $total = navigation.find('li').length;
            $width = 100 / $total;

            navigation.find('li').css('width', $width + '%');

        },

        onTabClick: function (tab, navigation, index) {

            var $valid = $('.wizard-card form').valid();

            if (!$valid) {
                return false;
            } else {
                return true;
            }

        },

        onTabShow: function (tab, navigation, index) {
            var $total = navigation.find('li').length;
            var $current = index + 1;

            var $wizard = navigation.closest('.wizard-card');

            // If it's the last tab then hide the last button and show the finish instead
            if ($current >= $total) {
                $($wizard).find('.btn-next').hide();
                $($wizard).find('.btn-finish').show();
            } else {
                $($wizard).find('.btn-next').show();
                $($wizard).find('.btn-finish').hide();
            }

            //update progress
            var move_distance = 100 / $total;
            move_distance = move_distance * (index) + move_distance / 2;

            $wizard.find($('.progress-bar')).css({width: move_distance + '%'});
            //e.relatedTarget // previous tab

            $wizard.find($('.wizard-card .nav-pills li.active a .icon-circle')).addClass('checked');

        }
    });


    // Prepare the preview for profile picture
    $("#wizard-picture").change(function () {
        readURL(this);
    });

    $('[data-toggle="wizard-radio"]').click(function () {
        wizard = $(this).closest('.wizard-card');
        wizard.find('[data-toggle="wizard-radio"]').removeClass('active');
        $(this).addClass('active');
        $(wizard).find('[type="radio"]').removeAttr('checked');
        $(this).find('[type="radio"]').attr('checked', 'true');
    });

    $('[data-toggle="wizard-checkbox"]').click(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).find('[type="checkbox"]').removeAttr('checked');
        } else {
            $(this).addClass('active');
            $(this).find('[type="checkbox"]').attr('checked', 'true');
        }
    });

    $('.set-full-height').css('height', 'auto');


    $.fn.datepicker.dates['cn'] = {   //切换为中文显示
        days: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
        daysShort: ["日", "一", "二", "三", "四", "五", "六", "七"],
        daysMin: ["日", "一", "二", "三", "四", "五", "六", "七"],
        months: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
        monthsShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
        today: "今天",
        clear: "清除"
    };
    function dataForm() {
        $('.date-add').datepicker({
            autoclose: true, //自动关闭
            beforeShowDay: $.noop,    //在显示日期之前调用的函数
            calendarWeeks: false,     //是否显示今年是第几周
            clearBtn: false,          //显示清除按钮
            daysOfWeekDisabled: [],   //星期几不可选
            endDate: Infinity,        //日历结束日期
            forceParse: true,         //是否强制转换不符合格式的字符串
            format: 'yyyy-mm-dd',     //日期格式
            keyboardNavigation: true, //是否显示箭头导航
            language: 'cn',           //语言
            minViewMode: 0,
            orientation: "auto",      //方向
            rtl: false,
            startDate: -Infinity,     //日历开始日期
            startView: 0,             //开始显示
            todayBtn: "linked",          //今天按钮
            todayHighlight: true,    //今天高亮
            weekStart: 0              //星期几是开始
        }).on('changeDate', function (e) {
            var _this = $(this);
            _this.parents('.ivu-form-item').removeClass('ivu-form-item-error');
            _this.parents('.ivu-form-item').find('.ivu-form-item-error-tip').remove();
        });
    }

    dataForm();
    var btnAddDirector = $("#addDirector");
    var directorBox = $("#directorBox");
    var directorHtml = directorBox.find('.person-inf-box').eq(0).prop("outerHTML");
    var btnAddPerformer = $("#addPerformer");
    var performerBox = $("#performerBox");
    var performerHtml = performerBox.find('.person-inf-box').eq(0).prop("outerHTML");
    btnAddDirector.click(function () {
        var directorNum = directorBox.find('.person-inf-box').length;
        var directorNumId = directorNum + 1;
        directorBox.append(directorHtml);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorLabel1").attr('id', 'performerLabel' + directorNumId);
        // directorBox.find('.person-inf-box').eq(directorNum).find("#"+"directorLabel"+directorNumId).text("No."+directorNumId+" 导演姓名：");
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorName1").attr('name', 'directorName' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorName1").attr('id', 'directorName' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorSexMan1").attr('name', 'directorSex' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorSexMan1").attr('id', 'directorSexMan' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorSexWoman1").attr('name', 'directorSex' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorSexWoman1").attr('id', 'directorSexWoman' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorCapital1").attr('name', 'directorCapital' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorCapital1").attr('id', 'directorCapital' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorNationality1").attr('name', 'directorNationality' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorNationality1").attr('id', 'directorNationality' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorBirthday1").attr('name', 'directorBirthday' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorBirthday1").attr('id', 'directorBirthday' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorAddress1").attr('name', 'directorAddress' + directorNumId);
        directorBox.find('.person-inf-box').eq(directorNum).find("#directorAddress1").attr('id', 'directorAddress' + directorNumId);
        dataForm();
    });
    btnAddPerformer.click(function () {
        var performerNum = performerBox.find('.person-inf-box').length;
        var performerNumId = performerNum + 1;
        performerBox.append(performerHtml);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerLabel1").attr('id', 'performerLabel' + performerNumId);
        // performerBox.find('.person-inf-box').eq(directorNum).find("#"+"performerLabel"+performerNumId).text("No."+performerNumId+" 演员姓名：");
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerName1").attr('name', 'performerName' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerName1").attr('id', 'performerName' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerSexMan1").attr('name', 'performerSex' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerSexMan1").attr('id', 'performerSexMan' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerSexWoman1").attr('name', 'performerSex' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerSexWoman1").attr('id', 'performerSexWoman' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerCapital1").attr('name', 'performerCapital' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerCapital1").attr('id', 'performerCapital' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerNationality1").attr('name', 'performerNationality' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerNationality1").attr('id', 'performerNationality' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerBirthday1").attr('name', 'performerBirthday' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerBirthday1").attr('id', 'performerBirthday' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerAddress1").attr('name', 'performerAddress' + performerNumId);
        performerBox.find('.person-inf-box').eq(performerNum).find("#performerAddress1").attr('id', 'performerAddress' + performerNumId);
        dataForm();
    })


});


//Function to show image before upload

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function completePay(order_no) {
    // alert(order_no);

    $.ajax({
        url: "/filmfest/complete-pay.html",
        type: "post",
        cache: false,
        data: {order_no:order_no},
        success: function (data) {
            alert("付款完成,等待管理员审核")
            console.log(data);
            if(data.code == 200){
                console.log(data);
                // loaddown('/filmfest/filmfest-pay.html?order_no='+data.data,'myfilm','centerbody',{});
                window.location = "/filmfest/filmfest-list.html";
            }else {
                alert('付款失败');
            }
        },
        error: function (e) {
            alert("付款失败");
        }
    });
}

/**
 * Created by dongbin on 2017/3/15.
 */
$(function () {
    //头像
    jQuery(".create-actions").click(function () {
        var options = {
            resetForm:true,

            dataType:'json',
            data:{userid:''},
            url:"upload.html",
            success:function(data) {
                if(data.code == 200){
                    var ob = $(".user-icon img");
                    ob.each(function () {
                        $(this).attr('src',data.data.url);
                        $(".quick_link").attr('src',data.data.url);
                    })
                }else {
                    alert('error');

                }
            }
        };
        var userid = $(this).attr('datauserid');
        options.data.userid = userid;
					// console.log(options);
        var pla=phone();
        jQuery("#uploadForm").ajaxForm(options);
        if (pla == 2){
            $("#cower").click();
        }else {
            jQuery("#cower").click();
        }


    });
    //身份证
    jQuery(".shenfenzheng").click(function () {
        var options = {
            resetForm:true,

            dataType:'json',
            data:{userid:''},
            url:"uploadidcard.html",
            success:function(data) {
                if(data.code == 200){
                    $("#uploadtishi").remove();
                    $("#userident input[name='idcard_url']").val(data.data.fullFileName);
                    $("#shenfenzheng").html("<img style='width: 100px;' src='"+data.data.url+"'>");
                }else {
                    alert('error');

                }
            }
        };
        var userid = $(this).attr('datauserid');
        options.data.userid = userid;
        // console.log(options);
        var pla=phone();
        jQuery("#uploadForm").ajaxForm(options);
        if (pla == 2){
            $("#cower").click();
        }else {
            jQuery("#cower").click();
        }


    });
    jQuery("#cower").change(function () {
        jQuery("#uploadForm").submit();
    });
    function phone() {
        var u = navigator.userAgent;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        if(isAndroid) {
            return 1;
        } else {
            return 2;
        }
    }
});
/**
 * Created by huhqiang on 09/07/2017.
 */
/**
 * Created by huhqiang on 17/2/26.
 */
$(function () {
    function setTemple(tplId, target,$data){
        var $html = template(tplId,$data);
        target.append($html);
    }

    /*
     个人中心导航栏切换
     */
    $(".center").on("click","a",function(){
        var body = 'centerbody';
        var address = $(this).data('item');
        var actionurl = "/filmfest/"+address+".html";
        $(this).addClass("my-border-b");
        $(this).siblings().removeClass("my-border-b");
        var data={
            
        };
        loaddown(actionurl,address,body,data);

    });


    /*
     加载数据
     */
    function loaddown(actionurl,address,body,data){

        $.ajax({
            url:actionurl,
            type:"POST",
            data:data,
            success:function($resData){
                // console.log($resData);
                if($resData.code == 200){
                    $("#"+body).empty();
                    setTemple(address+"Tempate",$("#"+body),$resData);
                }else {
                    $("#"+body).empty();
                    $("#"+body).append('<div class="noticeText" style="text-align: center;margin: 80px auto;font-size: large">'+$resData.code+'</div>');
                }
            }
        });
    }

    //alert(node);
    if(node){
        if(node == 'videoinfo'){
            var data = {
                film_id:film_id,
                // signup_id:signup_id,
            };
            if (signup_id) {
                data.signup_id = signup_id;
            }
            actionurl = "/filmfest/videoinfo.html";
            address = "videoinfo";
            loaddown(actionurl,address,'centerbody',data);
        }else{
            loaddown('/filmfest/'+node+'.html',node,'centerbody',{});
        }

    }else{
        loaddown('/filmfest/home.html','home','centerbody',{});
    }

});
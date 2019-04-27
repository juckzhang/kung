/**
 * Created by huhqiang on 31/03/2017.
 */
$(function () {
    function setTemples(tplId, target,$data){
        var $html = template(tplId,$data);
        target.append($html);
    }
    $("#moreVideo").click(function () {
        var currentPage = parseInt($("input[name='page']").val());
        var pageCount = $("input[name='pageCount']").val();
        var userId = $("input[name='userId']").val();
        if(currentPage >= pageCount - 1) return false;
        currentPage += 1;
        var actionurl = "membercenter.html";
        var data = {
            ids:userId,
            page:currentPage,
        }
        $.ajax({
            url:actionurl,
            type:"POST",
            data:data,
            success:function($resData){
                // console.log($resData);
                if($resData.code == 200){
                    setTemples("membercenterTempate",$("#video-list-load"),$resData);
                    $("input[name='page']").val($resData.data.videos.page);
                    console.log($resData);
                }else {
                    $("#"+body).append('<div class="noticeText" style="text-align: center;margin: 80px auto;font-size: large">'+$resData.code+'</div>');
                }
            }
        });
    });
})
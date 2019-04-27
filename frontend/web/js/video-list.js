/**
 * Created by Administrator on 2016/12/12 0012.
 */
//获取评论
(function($,window){
    var list = {
        pageCount:0,
        dataCount:0,
        currentPage:0,
        opt:{
            cateId:null,
            prePage:10,
            apiUrl:'',
            keyWord:''
        },
        isInit:false,
        refresh:function(data){
            var dataList = data.dataList,
                count    = dataList.length;
            this.pageCount = data.pageCount;
            this.dataCount = data.dataCount;
            if( count == 0 ) return;

            //构造数据
            var append = '';
            for(var i = 1; i <= count; ++i)
            {
                var item = dataList[i-1],
                    yu   = i % 4,
                    createTime = this.timeFormat(item.create_time),
                    className  = 'small-wide-img',
                    src = item.wide_poster;
                //console.log(item);
                if(this.opt.cateId == 3) {src = item.poster_url;className = 'small-poster-img';}
                if(yu > 0) append += "<div class='video item substr'>";
                else append += "<div class='video item substr' style='margin-right: 0;'>";
                append += "<a href='/channel/video-play.html?id="+item.id+"' title=''></a> <i class='bg hide' style='font-style: italic;'></i>";
                append += "<img  class='"+className+"' data-name='"+item.name +
                    "' data-href='/channel/video-play.html?id="+item.id+"' data-introduction='"+item.introduction +
                    "' data-release='"+item.release_time+"' data-create-time='"+createTime+"' data-string-length=120 src='"+ src
                    +"'  width='100%' title='"+item.name+"'/>"+
                    "<span class='p-time hover-hide' style='margin-top: -22px;margin-right: 5px;'><i class='ibg hide'></i><span>"+item.video.play_time+"</span></span>"+
                    "<div class='title'><p class='overflow_hidden' style='margin:0px;font-size: 14px;'>"+item.name+
                    "</p> <p class='overflow_hidden' style='font-size: 11px;color: #646464;'>"+item.introduction +"</p></div>"+
                    "</div></div>";
            }
            $('.video-list').append(append);
            //判断是否需要隐藏更多
            if(this.currentPage >= this.pageCount - 1){
                $('.page-more').hide();
            }
            return this;
        },
        timeFormat:function(timestamp){
            var timestamp = parseInt(timestamp),
                date      = new Date(timestamp),
                year      = date.getFullYear(),
                month     = date.getMonth() + 1,
                day       = date.getDate();
            return year + '-' + month + '-' + day;
        },
        get_data:function(){
            $.post(this.opt.apiUrl, {page:this.currentPage,prePage:this.opt.prePage,cateId: this.opt.cateId,keyWord:this.opt.keyWord},
                function(result){
                    if(result.code != 200) {alert(result.resultDesc);return;}
                    list.refresh(result.data);
                },'json');
        },
        next: function(){
            if(this.currentPage >= this.pageCount - 1) return ;
            this.currentPage += 1;
            this.get_data();
        },
        init:function(opts){
            $.extend(this.opt,opts);
            this.get_data();
        }
    };

    //评论
    $.list = function(opt){
        list.init(opt);
        $('#more').click(function(){
            list.next();
        });
        return list;
    };
    
})(jQuery,window);
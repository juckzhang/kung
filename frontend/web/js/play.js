		$(function(){
					//加载头部页面
				  	$.ajax({
					type:"get",
					url:"header.html",
					dataType:"html",
//					data:parames,
					success:function(data){
						$("#head").html(data);
					},
					error:function(){
			        	console.log('err');
			        }
			});
			
			$.ajax({
					type:"get",
					url:"foot.html",
					dataType:"html",
//					data:parames,
					success:function(data){
						$("#end").html(data);
					},
					error:function(){
			        	console.log('err');
			        }
			});
			});
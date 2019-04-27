	$(function(){
				$.ajax({
					type:"get",
					url:"mheader.html",
					async:true,
					success:function(data){
						$("#mbanner").html(data);
					}
				});
				
				$.ajax({
					type:"get",
					url:"mfoot.html",
					async:true,
					success:function(data){
						$("#footer").html(data);
					}
				});
				
			});
$(function(){
	
	$(".country .adress").click(function(){
		$(".country .adress").attr("style","color:#666666;");
		$(this).attr("style","color:#ffb000;");
		// country = $(this).html();
		// window.location.href = "/filmfest/filmfest-list.html?country="+country+"&month="+month;
	});
	$(".time .adress").click(function(){
		$(".time .adress").attr("style","color:#666666;");
		$(this).attr("style","color:#ffb000;");
		// month = $(this).html();
		// window.location.href = "/filmfest/filmfest-list.html?country="+country+"&month="+month;
	})
	$(".staus .adress").click(function(){
		$(".staus .adress").attr("style","color:#666666;");
		$(this).attr("style","color:#ffb000;");
	})
	});

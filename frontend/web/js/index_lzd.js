$(function(){
	//第一个
		$(".top1 .content_first .word .head_toc").hover(function() {
			$(this).next().show();
		}, function() {
			$(".top1 .top_begin .content_first .word .topic").hide();

		});
		$(".top1 .top_begin .content_first .word .topic").hover(function() {
			$(this).show();
		}, function() {
			$(this).hide(1000);
		});

		//第二个
		$(".top2 .top_two .word .head_toc").hover(function() {
			$(this).next().show();
		}, function() {
			$(".top2 .top_two .word .topic").hide();

		});

		$(".top2 .top_two .word .topic").hover(function() {
			$(this).show();
		}, function() {
			$(this).hide(1000);
		});

		//第三个

		$(".pop .pop_right .content_first .word .head_toc").hover(function() {
			$(this).next().show();
		}, function() {
			$(".pop .pop_right .content_first .word .topic").hide();

		});
		$(".pop .pop_right .content_first .word .topic").hover(function() {
			$(this).show();
		}, function() {
			$(this).hide(1000);
		});

		//第四个
		$(".top4 .top_two .word .head_toc").hover(function() {
			$(this).next().show();
		}, function() {
			$(".top4 .top_two .word .topic").hide();
		});
		$(".top4 .top_two .word .topic").hover(function() {
			$(this).show();
		}, function() {
			$(this).hide(1000);
		});

		//第五个

		$(".top5 .content_first .word .head_toc").hover(function() {
			$(this).next().show();
		}, function() {
			$(".top5 .content_first .word .topic").hide();
		});
		$(".top5 .content_first .word .topic").hover(function() {
			$(this).show();
		}, function() {
			$(this).hide(1000);
		});

		//第六个

		$(".top6 .top_begin .content_first .word .head_toc").hover(function() {
			$(this).next().show();
		}, function() {
			$(".top6 .top_begin .content_first .word .topic").hide();
		});
		$(".top6 .top_begin .content_first .word .topic").hover(function() {
			$(this).show();
		}, function() {
			$(this).hide(1000);
		});
})

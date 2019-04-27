	$(function() {
		var swiper = new Swiper('.swiper-container', {
			pagination: '.swiper-pagination',
			paginationClickable: true,
			autoplay: 3000,
			loop: true

		});
		//加载头部页面
		$.ajax({
			type: "get",
			url: "header.html",
			dataType: "html",
			//					data:parames,
			success: function(data) {
				$("#box .daohang").html(data);
			},
			error: function() {
				console.log('err');
			}
		});

		$.ajax({
			type: "get",
			url: "vrfoot.html",
			async: true,
			success: function(data) {

				$("#box .foot").html(data);

			}
		});
	});
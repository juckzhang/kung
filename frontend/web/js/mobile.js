	$(function() {
			$.ajax({
				type: "get",
				url: "mheader.html",
				async: true,
				success: function(data) {
					$(".banner").html(data);
				}
			});

		})
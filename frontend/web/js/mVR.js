	$(function() {

				$.ajax({
					type: "get",
					url: "mheader.html",
					async: true,
					success: function(data) {
						$("#banner").html(data);
					}
				});

				$.ajax({
					type: "get",
					url: "mfoot.html",
					async: true,
					success: function(data) {
						$("#mfinally").html(data);
					}
				});
			})
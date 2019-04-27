			$(function() {
				function fn() {
					var baseFontSize = window.innerWidth / 10;

					document.getElementsByTagName("html")[0].style.fontSize = baseFontSize + "px";
					console.log(document.getElementsByTagName("html")[0].style.fontSize);

				}
				fn();
				window.onresize = function() {
					fn();

				};

			})
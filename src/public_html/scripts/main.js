const YSH = {};

YSH.jsImport = function (args) {
	const promises = [];
	for (const i in args) {
		promises.push(import(args[i]));
	}
	return Promise.race(promises);
};

YSH.jQueryPromise = YSH.jsImport(["https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js", "./jquery.min.js"]);

Promise.all([
	YSH.jQueryPromise,
	new Promise(resolve => {
		document.getElementById("popper").addEventListener("load", resolve.bind(null));
	})
]).then(
	YSH.jsImport(["https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js", "./bootstrap.min.js"])
);

YSH.jQueryPromise.then(function () {
	$(function () {
		var ua = navigator.userAgent, msie = ua.indexOf("MSIE "), trident = ua.indexOf("Trident/");

		if (ua.indexOf("Mozilla/5.0") > -1 && ua.indexOf("Android ") > -1 && ua.indexOf("AppleWebKit") > -1 && ua.indexOf("Chrome") === -1) {
			$("select.form-control").removeClass("form-control").css("width", "100%");
		}

		if (msie > 0 || trident > 0) {
			$(".alerts").append(
				"<div class=\"alert alert-warning alert-dismissable fade in\"><strong>WARNING:</strong> Detect use of IE" + (msie > 0 ? ua.substring(msie + 5, ua.indexOf(".", msie)) : "11") + "!Consider switching to a more modern browser such as, Edge, Chrome, FireFox so that we can offer better a user experience.<a class=\"close\" href=\"#\" aria-label=\"close\" data-dismiss=\"alert\">&times;</a></div>"
			);
		}


		$("#activate-school").click(function () {
			document.cookie = "school=true; path=/~S151204";
		});

		$("#school").click(function () {
			document.cookie = "school=false; path=/~S151204";
			location.reload();
		});

		$("#login").click(function() {
			sessionStorage.continue = location;
		});

		$("#logout").click(function (e) {
			e.preventDefault();
			$.get("/~S151204/user/logout?noredir=true", function () {
				sessionStorage.clear();
				location.reload();
			});
		});
	});
});

export default YSH;
export { YSH };
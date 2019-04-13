
/**
 * Import one script from multiple urls
 * @param {string[]} urls The list of urls for the same script
 * @returns {Promise<void>}
 */
export function jsImport(urls) {
	return urls.reduce((previous, current) => {
		const p = import(current);
		return previous.catch(() => p);
	}, Promise.reject()).then(() => null);
}

export function loadGapi(onLoad) {
	if (window.gapi) {
		onLoad(window.gapi);
	} else {
		const g = document.getElementById("gapi");
		g.addEventListener("load", function onScriptLoad() {
			g.removeEventListener("load", onScriptLoad);
			onLoad(window.gapi);
		});
	}
}

export function finishLogin() {
	sessionStorage.loggedIn = true;
	if (sessionStorage.continue) {
		const c = sessionStorage.continue;
		sessionStorage.removeItem("continue");
		location.href = c;
	} else {
		location.href = "/~S151204/";
	}
}

export const jQueryPromise = jsImport(["https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js", "//moondanz.tanghin.edu.hk/~S151204/scripts/jquery.min.js"])
	.then(() => window.jQuery);

Promise.all([
	jQueryPromise,
	new Promise(resolve => {
		if (typeof Popper === "undefined") {
			document.getElementById("popper").addEventListener("load", resolve.bind(null));
		} else {
			resolve();
		}
	})
]).then(
	jsImport.bind(null, ["https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js", "//moondanz.tanghin.edu.hk/~S151204/scripts/bootstrap.min.js"])
);

const loadCss = function () {
	const cssDiv = document.createElement("div");
	cssDiv.innerHTML = document.getElementById("deferred-css").innerHTML;
	document.body.appendChild(cssDiv);
};

if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") {
	loadCss();
} else {
	document.addEventListener("DOMContentLoaded", loadCss);
}

jQueryPromise.then(function ($) {
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

export const YSH = {
	jQueryPromise,
	jsImport
};

export default YSH;
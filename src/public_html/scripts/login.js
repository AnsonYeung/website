import YSH from "./main.js";

YSH.jQueryPromise.then(function () {
	$("form").submit(function (e) {
		e.preventDefault();
		sessionStorage.username = $("#username").val();
		$.post("user/login", $(e.target).serialize() + "&noredir=true", function (data) {
			if (data === "true") {
				sessionStorage.loggedIn = true;
				if (sessionStorage.continue) {
					var c = sessionStorage.continue;
					sessionStorage.removeItem("continue");
					location.href = c;
				} else {
					location.href = "/~S151204/";
				}
			} else {
				$("output").text("Username or password wrong!").slideDown().delay(1000).slideUp();
			}
		});
	});
});

const init = function () {
	gapi.load("auth2", function (auth2) {
		auth2 = gapi.auth2.init({
			client_id: "1093588347904-bnd4hlks49ahnqelh7fedg8oqor9n51q.apps.googleusercontent.com",
			cookiepolicy: "single_host_origin"
		});
		YSH.jQueryPromise.then(() => {
			auth2.attachClickHandler(document.getElementById("gsignin"), {}, function (gUser) {
				$("#wait").removeClass("d-none");
				$("#gsignin").addClass("d-none");
				$.post("user/glogin", "jwt=" + gUser.getAuthResponse().id_token + "&noredir=true", result => {
					if (result.startsWith("success")) {
						sessionStorage.loggedIn = true;
						sessionStorage.username = result.substr(7);
						if (sessionStorage.continue) {
							var c = sessionStorage.continue;
							sessionStorage.removeItem("continue");
							location.href = c;
						} else {
							location.href = "/~S151204/";
						}
					} else if (result === "need registration") {
						$("#wait").addClass("d-none");
						$("#gsignin").removeClass("d-none");
						$("output").text("This google account is not registered on our server yet.").slideDown().delay(5000).slideUp();
					} else {
						$("#wait").addClass("d-none");
						$("#gsignin").removeClass("d-none");
						$("output").text("Google account is invalid!").slideDown().delay(5000).slideUp();
					}
				});
			}, function (e) {
				if (e.error !== "popup_closed_by_user") {
					alert("Unknown Google Sign In Error: " + e.error || e);
				}
			});
		});
	});
};

if (window.gapi) {
	init();
} else {
	const g = document.getElementById("gapi");
	g.addEventListener("load", function onLoad() {
		g.removeEventListener("load", onLoad);
		init();
	});
}
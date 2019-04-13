import YSH, { finishLogin } from "./main.js";

YSH.jQueryPromise.then(function ($) {
	$("output").toggleClass("hide").hide();
	$("form").submit(function (e) {
		e.preventDefault();
		sessionStorage.username = $("#username").val();
		$.post("user/create", $(e.target).serialize() + "&noredir=true", function (data, status, xhr) {
			if (data === "Account created successfully.") {
				finishLogin();
			} else {
				if (data !== "") {
					$("output").text(data).slideDown().delay(1000).slideUp();
				} else if (status !== "success") {
					$("output").text("Communication to server failed! Server response with HTTP status code " + xhr.status).slideDown().delay(1000).slideUp();
				} else {
					$("output").html("There are currently some issue in server, contact <a href=\"mailto:s151204@tanghin.edu.hk\">s151204@tanghin.edu.hk</a> for help.").slideDown().delay(1000).slideUp();
				}
			}
		});
	});
});

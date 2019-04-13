import { YSH, loadGapi } from "./main.js";
const init = function (gapi) {
	gapi.load("auth2", function(auth2) {
		auth2 = gapi.auth2.init({
			client_id: "1093588347904-bnd4hlks49ahnqelh7fedg8oqor9n51q.apps.googleusercontent.com",
			cookiepolicy: "single_host_origin"
		});
		YSH.jQueryPromise.then(function ($) {
			auth2.attachClickHandler(document.getElementById("gsignin"), {}, function (gUser) {
				$("#wait").removeClass("d-none");
				$("#gsignin").addClass("d-none");
				$.post("/~S151204/user/gbind", {"jwt": gUser.getAuthResponse().id_token, "csrf-code": $("#csrf-code").val()}, result => {
					switch (result) {
						case "Gbind success":
							$("#gtitle").addClass("d-none");
							$("#wait").addClass("d-none");
							$("#gsuccess").slideDown().delay(1000).slideUp();
							break;
						case "csrf code mismatch":
							alert("csrf code mismatch, this might be due to session timeout.");
							break;
						case "jwt invalid":
							alert("malicious jwt detected.");
							break;
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
loadGapi(init);
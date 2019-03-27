import YSH from "./main.js";

// Fetch users, pages, etc, here.
// 1. preload next page content
YSH.jQueryPromise.then(() => {
	let userCurrentPage = 0;
	let userPages = [document.getElementById("user").innerHTML];
	/**
	 * @type {Object<string, string>}
	 */
	const describe = {
		"up": "upgrade",
		"down": "downgrade",
		"del": "delete"
	};
	const submit = function () {
		let users = [];
		document.querySelectorAll("[value]:checked").forEach((elem) => {
			users.push(elem.value);
		});
		if (users.length === 0) return;
		if (confirm("Are you sure to " + describe[this.id] + " " + users.join(", ") + "?")) {
			$.post("admin/" + this.id, {users: JSON.stringify(users), page: userCurrentPage}, function (data) {
				if (data === "Permission declined") {
					// usually unreachable code
					// this is intended for those who naively modify the dom
					alert("Your permission currently don't allow you to do this.");
					return;
				}
				$("#user").html(data);
				userPages[userCurrentPage] = data;
				$("#sa").prop("checked", false);
				$("#up").prop("disabled", false);
				$("#down").prop("disabled", false);
			});
		}
	};
	$("#user").on("click", ":checkbox[value]:enabled", function () {
		if (!this.checked) {
			$("#sa").prop("checked", false);
			if ($(this).parent().next().next().next().text() === "none") {
				$("#down").prop("disabled", $("tr:has(:checkbox[value]:checked)>td:nth-child(4):contains('none')").length !==  0);
			}
			if ($(this).parent().next().next().next().text() === "admin") {
				$("#up").prop("disabled", $("tr:has(:checkbox[value]:checked)>td:nth-child(4):contains('admin')").length !==  0);
			}
		} else {
			if ($(":checkbox[value]:enabled:not(:checked)").length === 0) {
				$("#sa").prop("checked", true);
			}
			if ($(this).parent().next().next().next().text() === "none") {
				$("#down").prop("disabled", true);
			}
			if ($(this).parent().next().next().next().text() === "admin") {
				$("#up").prop("disabled", true);
			}
		}
	});
	$("#sa").click(function () {
		var s = this.checked;
		$(":checkbox:enabled").each(function () {
			this.checked = s;
			var e = $.Event("click");
			e.target = this;
			$("tbody").trigger(e);
		});
	});
	$("#up:enabled").click(submit);
	$("#down:enabled").click(submit);
	$("#del:enabled").click(submit);
	if (window.EventSource) {
		const sessionId = document.cookie.split("PHPSESSID=")[1].split(";")[0];
		$(new EventSource("//moondanz.tanghin.edu.hk/~S151204/admin?EventSource=access&rows=" + $("#access").children().length +
				"&PHPSESSID=" + sessionId)).on("update", function (e) {
			$("#access").html(e.originalEvent.data);
		});
		$(new EventSource("//moondanz.tanghin.edu.hk/~S151204/admin?EventSource=error&rows=" + $("#error").children().length +
				"&PHPSESSID=" + sessionId)).on("update", function (e) {
			$("#error").html(e.originalEvent.data);
		});
		$(new EventSource("//moondanz.tanghin.edu.hk/~S151204/admin?EventSource=slog&rows=" + $("#slog").children().length * 2 +
				"&PHPSESSID=" + sessionId)).on("update", function (e) {
			$("#slog").html(e.originalEvent.data);
		});
	} else {
		$(".alerts").append("<div class=\"alert alert-danger\">You are using Internet Exporer or Microsoft Edge which is not supported by Access Log and Error Log feature right now. See <a href=\"//caniuse.com/#feat=eventsource\" target=\"_blank\">browser support</a> for this feature also.<a class=\"close\" href=\"#\" aria-label=\"close\" data-dismiss=\"alert\">&times;</a></div>");
	}
	var totalPageCount = +$("#total-page").text();
	var onAllPagesLoaded = function () {
		$("#uprev").click(function () {
			userCurrentPage--;
			$("#curr-page").text(userCurrentPage + 1);
			$("#user").html(userPages[userCurrentPage]);
			if (userCurrentPage === 0) {
				$("#uprev").prop("disabled", true);
			}
			$("#unext").prop("disabled", false);
		});
		$("#unext").click(function () {
			userCurrentPage++;
			$("#curr-page").text(userCurrentPage + 1);
			$("#user").html(userPages[userCurrentPage]);
			if (userCurrentPage === totalPageCount - 1) {
				$("#unext").prop("disabled", true);
			}
			$("#uprev").prop("disabled", false);
		});
		$("#unext").prop("disabled", false);
	};
	let completed = 1;
	for (var i = 1; i < totalPageCount; i++) {
		$.get("admin?page=" + i, (function (userPages, i, total, callback) {
			return function (data) {
				userPages[i] = data;
				completed++;
				if (completed === total) {
					callback();
				}
			};
		})(userPages, i, totalPageCount, onAllPagesLoaded));
	}
});
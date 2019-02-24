import { YSH } from "./main.js";
YSH.jQueryPromise.then(function () {
	$(function ($) {
		sessionStorage.username = $("#nav-content>>a:eq(2)").text().trim();
		var getData = function (e) {
			return JSON.parse(e.originalEvent.data);
		};
		var pushMessage = function (str) {
			var bottom = $("#chat-log").scrollTop() + $("#chat-log").innerHeight() === $("#chat-log").prop("scrollHeight");
			$("#chat-log").append("<div>" + str + "</div>");
			if (bottom) {
				$("#chat-log").scrollTop($("#chat-log").prop("scrollHeight") - $("#chat-log").innerHeight());
			}
		};
		if (window.EventSource) {
			const sessionId = document.cookie.split("PHPSESSID=")[1].split(";")[0];
			$(new EventSource("//moondanz.tanghin.edu.hk/~S151204/projects/chat-room?type=EventSource&&PHPSESSID=" + sessionId)).on("userconnect", function (e) {
				pushMessage("<i>" + getData(e).username + " entered the chat room.</i>");
			}).on("usermessage", function (e) {
				pushMessage(getData(e).username + ": " + getData(e).message);
			}).on("userleave", function (e) {
				pushMessage("<i>" + getData(e).username + " leaved.</i>");
			}).on("updateUsers", function (e) {
				$("#online").text("Online: " + getData(e));
			}).on("error", function (e) {
				throw new Error(e.originalEvent.data);
			}).on("requestClose", function (e) {
				e.target.close();
				pushMessage("<strong>Forced Quit:</strong>");
				pushMessage(e.originalEvent.data);
			});
		} else {
			$(".alerts").append("<div class=\"alert alert-danger\">You are using Internet Exporer or Microsoft Edge which is not supported by this feature right now. See <a href=\"//caniuse.com/#feat=eventsource\" target=\"_blank\">browser support</a> for this feature also.<a class=\"close\" href=\"#\" aria-label=\"close\" data-dismiss=\"alert\">&times;</a></div>");
		}
		var handler = function () {
			let message = $("#chat").val();
			if (message) {
				$.post("chat-room?type=EventSource", {message: message}, function () {
					$("#chat").val("");
				}).fail(function () {
					pushMessage("Unable to send the message \"" + message + "\" due to problem of communicating to the server.");
				});
			}
		};
		$("#chat").on("keydown", function (e) {
			if (e.keyCode === 13) {
				handler();
			}
		});
		$("#send").click(handler);
		$(window).on("unload", function () {
			$.ajax({method: "POST", url: "chat-room?type=EventSource", data: {logout: true}, async: false});
		});
	});
});

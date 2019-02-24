import { YSH } from "./main.js";

YSH.jQueryPromise.then(function () {
	$(function () {
		const commandBox = $("#command");
		const prefix = $("#prefix");
		const logBox = $("#log");
		const tagsToReplace = {
			"&": "&amp;",
			"<": "&lt;",
			">": "&gt;"
		};
		
		function replaceTag(tag) {
			return tagsToReplace[tag] || tag;
		}
		
		function safe_tags_replace(str) {
			return str.replace(/[&<>]/g, replaceTag);
		}
		$(window).on("keypress", function () {
			commandBox.focus();
		});
		commandBox.on("keydown", function (e) {
			if (e.keyCode === 13) {
				logBox.append(prefix.text() + commandBox.val() + " 2>...");
				prefix.hide();
				commandBox.prop("disabled", true);
				$.post("shell", {command: commandBox.val()}, function (data, status) {
					if (status === "success") {
						logBox.append("\n" + safe_tags_replace(data).replace(/[\b]/g, "<span class=\"backspace\"></span>"));
						prefix.show();
						commandBox.prop("disabled", false).focus();
					} else {
						logBox.append("\nSorry, connection error occured, please try reloading the page.\n Status: " + status + "\n Received data: " + data);
						prefix.show();
						commandBox.prop("disabled", false).focus();
					}
				}).fail(function () {
					logBox.append("\nSorry, connection error or server error occured, please try reloading the page.");
					prefix.show();
					commandBox.prop("disabled", false).focus();
				});
				commandBox.val("");
			}
		});
		commandBox.focus();
	});
});

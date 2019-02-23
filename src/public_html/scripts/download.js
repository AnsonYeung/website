import { YSH } from "./main.js";
YSH.jQueryPromise.then(function () {
	$("#download").click(function () {
		var a = $("<a download>download</a>").attr("href", "download-booster?url=" + encodeURIComponent($("#url").val())).appendTo("body")[0];
		a.click();
		a.remove();
	});
});

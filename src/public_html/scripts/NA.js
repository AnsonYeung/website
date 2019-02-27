import { YSH } from "./main.js";

// Model
const NA = {};
const factory = function (NA, $) {
	// View
	NA.reset = function () {
		$("#warning").slideUp("fast").empty();
		$("#output").slideUp("fast").empty();
		$("#input").slideDown("slow");
	};

	NA.displayError = function (e) {
		$("#wait").slideUp("fast");
		$("#warning").text(e).append("<br>").append($("<button class=\"btn btn-default\">OK</button>").click(NA.reset)).slideDown("slow");
	};

	NA.output = function (data) {
		$("#output").html("<h2>Output</h2>");
		const tspace = " &nbsp; ";
		for (let i in data) {
			$("#output")
				.append("<h3>" + (+i + 1) + "." + tspace + data[i][0] + tspace + data[i][1] + tspace + "/" + data[i][2] + "/</h3>")
				.append("<p> - " + data[i][3] + "</p>")
				.append("<p>e.g. " + data[i][4] + "</p");
		}
		$("#wait").slideUp("fast");
		$("#output").append(
			$("<br /><button class=\"btn btn-default\">BACK</button>").click(NA.reset)
		).slideDown("slow");
	};

	NA.displayStatus = function (status) {
		$("#wait").html("<h1>" + status + "</h1>");
	};

	// Set Event Listener
	$(function() {
		$("#work").click(function () {
			$("#input").slideUp("fast");
			$("#wait").html("<h1>Sending request...</h1>").slideDown("slow", NA.process);
		});
	});

	// Controller
	NA.getWordList = function () {
		let wordList = $("#word-list").val().split("\n");
		for (const i in wordList) {
			if (wordList[i] === "") {
				wordList.splice(i, 1);
			}
		}
		return wordList;
	};
	NA.wordListValid = function () {
		const wordList = NA.getWordList().sort();
		if (wordList.length === 0) {
			NA.displayError("You need to enter something!");
			return false;
		}
		for (let i = 0; i < wordList.length - 1; i++) {
			if (wordList[i] === wordList[i + 1]) {
				NA.displayError("Duplicated word, " + wordList[i] + "!");
				return false;
			}
		}
		return true;
	};
	NA.process = function () {
		if (!NA.wordListValid()) {
			return;
		}
		const words = NA.getWordList();
		let doneCount = 0;
		let output = [];
		$.each(words, function (i, v) {
			const normal = true;
			$.each(NA.assets, function(index, value) {
				if (index.toUpperCase() === v.toUpperCase()) {
					value.unshift(index);
					output[i] = value;
					doneCount++;
					normal = false;
					return;
				}
			});
			if (normal) {
				$.get("../browse", {url:"http://dictionary.cambridge.org/search/english/direct/?source=gadgets&q=" + v},
					(function(i, v, output) {
						return function(data) {
							const wDoc = $.parseHTML(data);
							if (!$(".entry-body", wDoc)[0]) {
								NA.displayError("There is no such word, " + v + "!");
								return;
							}
							// test all example and use the shortest one
							let out_eg, out_eg_len;
							const sentenceRegex = /^[A-Z].*\.$/;
							$(".eg:not(.extraexamps .eg)", wDoc).each(function(i, e) {
								var eg = $(e).text();
								var eg_len = eg.length + $(e).parent(".def-block").find(".def").text().length;
								out_eg = out_eg ? out_eg : $(e);
								out_eg_len = out_eg_len ? out_eg_len : eg_len;
								out_eg = eg_len < out_eg_len && sentenceRegex.test(eg) ? $(e) : out_eg;
							});
							if (!out_eg) {
								NA.displayError("Sorry, there is no example sentences with the word, " + v + ", on the dictionary.");
								return;
							}
							const entry = out_eg.parents(".entry-body__el");
							let out = [];
							out[0] = entry.find(".headword").text();
							out[1] = entry.find(".ico-bg").text();
							out[2] = entry.find(".ipa:first").text();
							out[3] = out_eg.parents(".def-block").find(".def").text();
							out[4] = out_eg.text();
							output[i] = out;
							if (words.length === ++doneCount) {
								NA.output(output);
							}
							NA.displayStatus("Getting words (" + doneCount + " out of " + words.length + ")");
						};
					})(i, v, output)
				).fail(function () {
					NA.displayError("Failed to communicate with server.");
				});
			}
		});
		NA.displayStatus("Request all sent");
		if (words.length === doneCount) {
			NA.output(output);
		}
	};
};

YSH.jQueryPromise.then(function () {
	// the variable $ is only available from here
	factory(NA, $);
});

export default NA;
export { NA };
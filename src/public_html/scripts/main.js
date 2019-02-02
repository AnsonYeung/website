// @ts-nocheck
/* eslint-disable */
requirejs({
	deps: ["polyfill", "jquery", "bootstrap", "firebase", "ga", "ie10"],
	paths: {
		"polyfill": "https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.2.5/polyfill.min",
		"jquery": [
			"https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min",
			"jquery.min"
		],
		"bootstrap": [
			"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min",
			"bootstrap.min"
		],
		"popper": [
			"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min",
			"popper.min"
		],
		"firebase": "https://www.gstatic.com/firebasejs/4.12.1/firebase",
		"ga": "https://www.google-analytics.com/analytics",
		"ie10": "https://maxcdn.bootstrapcdn.com/js/ie10-viewport-bug-workaround",
		"gapi": "https://apis.google.com/js/platform",
		"ace": "https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.2",
		"react": "https://unpkg.com/react@16/umd/react.production.min",
		"react-dom": "https://unpkg.com/react-dom@16/umd/react-dom.production.min",
		"prop-types": "https://unpkg.com/prop-types@15/prop-types.min"
	},
	shim: {
		"bootstrap": {
			deps: ["jquery", "popper"],
			exports: "$"
		},
		"firebase": {
			exports: "firebase"
		},
		"ga": {
			exports: "ga"
		},
		"gapi": {
			exports: "gapi"
		}
	}
});

requirejs(["popper"], function (popper) {
	window.Popper = popper;
});

requirejs(["jquery"], function ($) {
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

		if (this.styleSheets[0].cssRules !== null && this.styleSheets[0].cssRules.length === 0 && this.cookie.indexOf("school=") === -1) {
			document.cookie = "school=true; path=/~S151204";
			location.reload();
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

requirejs(["firebase"], function(firebase) {
	firebase.initializeApp({
		apiKey: "AIzaSyBOrZGxBDRMjJAyEwGQp96rHprSkPs4vKY",
		authDomain: "ysh-homepage.firebaseapp.com",
		databaseURL: "https://ysh-homepage.firebaseio.com",
		projectId: "ysh-homepage",
		storageBucket: "ysh-homepage.appspot.com",
		messagingSenderId: "1093588347904"
	});
});

requirejs(["ga"], function (ga) {
	// s151204@tanghin.edu.hk: UA-98324003-2
	// yeungsinhangsmall@gmail.com is used instead now.
	ga("create", "UA-105152581-1", "auto");
	ga("send", "pageview");
});;
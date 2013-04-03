var NB = NB || {};

NB.Header = function () {
	var headerObj = {},
		el = $('header'),
		elCSS = {},
		transitionCSS,
		lastScrollTop = $(window).scrollTop(),
		headerVisible = false, /* start with the assumption that header is not shown */
		options = {
			displaySpeedMS: 200
		},
		fnOnScroll;

	/* PRIVATE */

	fnOnScroll = function (ev) {
		if ((el.position().top + el.height()) > $(window).scrollTop()) { return; }
		var nextScrollTop = $(window).scrollTop();
		if (nextScrollTop < lastScrollTop) {
			/* scrolling up, show header */
			headerObj.show();
		} else {
			/* scrolling down, hide it */
			headerObj.hide();
		}

		lastScrollTop = nextScrollTop;
		return;
	};

	/* PUBLIC */
	headerObj.options = options;

	headerObj.setMode = function (mode) {
		switch (mode) {
		case "voterecorded":
			$('#headspace .search', el).hide();
			$('#headspace .voterecorded', el).show();
			break;
		default:
			$('#headspace .search', el).show();
			$('#headspace .voterecorded', el).hide();
			break; 
		};
	};

	headerObj.show = function () {
		if (headerVisible) { return; }

		// 1. top: -height
		el.css('top', '-'+ el.height() + 'px');

		// 2. position: fixed
		el.css('position', 'fixed');

		// 3. animate top down to 0 
		el.animate({ "top": 0 }, headerObj.options.displaySpeedMS);

		return headerVisible = true;
	};

	headerObj.hide = function () {
		if (!headerVisible) { return; }

		var fnAnimComplete = function () {
			el.css('position', 'absolute');
			el.css('top', 0);
		};

		// 1. animate top off of screen 
		el.animate({ "top": '-'+ $(window).scrollTop() +'px' }, 
			{ "duration": headerObj.options.displaySpeedMS ,
			  "complete": fnAnimComplete
			});

		return headerVisible = false;
	};

	/* attach fnOnScroll to the body onscroll event */
	$(window).scroll(fnOnScroll);

	return headerObj;
};

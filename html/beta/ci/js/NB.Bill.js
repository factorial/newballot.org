var NB = NB || {};

NB.Bill = function (headerObj) {
	var billObj = {},
	    inputs = $('.voteButtons input');

	/* PRIVATE */

	/* Everything that happens on vote happens here */
	inputs.change(function () {
		/* wire up the header reaction to voting */
		headerObj.setMode('voterecorded');
		headerObj.show();
	});

	
	/* PUBLIC */

	return billObj;
};



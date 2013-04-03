var NB = NB || {};

NB.NewUserInfo = function () {
	var userinfoObj = {},
	    el = $('#newuserinfo'),
		options = {
			"cookieExpiryDays": 365
		}


	/* PRIVATE */
	$('#but_closenewuserinfo').click(function () {
		var showNewUserInfo = ($('#chk_hidenewuserinfo').prop('checked')) ? 0 : 1;
		COOKIES.createCookie('showNewUserInfo', showNewUserInfo, options.cookieExpiryDays);
		el.hide();
	});
	
	/* PUBLIC */

	userinfoObj = el;
	return userinfoObj;
};


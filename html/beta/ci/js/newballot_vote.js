NB.init = function () {
    NB.preloadImagesIndex();

    YAHOO.util.Dom.getElementsByClassName(
        'votebutton',
        'a',
        document.body,
        function(el) {
            var billId = el.hash.substr(1);
            var vote = (YAHOO.util.Dom.hasClass(el, 'yeabutton') ? true : false);
            if (NB.loggedIn) {
                el.href = 'javascript:;';
                YAHOO.util.Event.addListener(el, 'click', function () { NB.vote(billId, vote); } );
            }

            // Commented out by John 2010.1.5 to let users know when they've actually voted.
            //YAHOO.util.Event.addListener(el, 'mouseover', function () { NB.showVoteImg(el); } );
            //YAHOO.util.Event.addListener(el, 'mouseout', function () { NB.restoreVoteImg(el); } );
        }
    );
};


NB.showVoteImg = function(a) {
    var buttonType = (YAHOO.util.Dom.hasClass(a, 'yeabutton')? 'Yea' : 'Nay');
    var newClass = 'vote'+ buttonType +'Checked';
    var oldClass = (YAHOO.util.Dom.hasClass(a, newClass)? newClass : 'vote'+ buttonType +'Unchecked');

    a.oldClass = oldClass; // remember this for later in restoreVoteImg
    a.newClass = newClass; // remember this for later in restoreVoteImg

    YAHOO.util.Dom.removeClass(a, oldClass);
    YAHOO.util.Dom.addClass(a, newClass);
}

NB.restoreVoteImg = function(a) {
    YAHOO.util.Dom.removeClass(a, a.newClass);
    YAHOO.util.Dom.addClass(a, a.oldClass);
    a.newClass = a.oldClass = '';
}

NB.vote = function (billid, vote)
{

    var handleSuccess = function (o)
    {
        NB.voteCancelWait();
        if (o.responseText === '0')
        {
            var yeabutton = document.getElementById(billid + 'yea');
            var naybutton = document.getElementById(billid + 'nay');

            if (vote === true)
            {
                yeabutton.oldClass = 'voteYeaChecked';
                YAHOO.util.Dom.addClass(yeabutton, 'voteYeaChecked');
                YAHOO.util.Dom.addClass(naybutton, 'voteNayUnchecked');
                YAHOO.util.Dom.removeClass(yeabutton, 'voteYeaUnchecked');
                YAHOO.util.Dom.removeClass(naybutton, 'voteNayChecked');
            }
            else
            {
                naybutton.oldClass = 'voteNayChecked';
                YAHOO.util.Dom.addClass(yeabutton, 'voteYeaUnchecked');
                YAHOO.util.Dom.addClass(naybutton, 'voteNayChecked');
                YAHOO.util.Dom.removeClass(yeabutton, 'voteYeaChecked');
                YAHOO.util.Dom.removeClass(naybutton, 'voteNayUnchecked');
            }
        }
        else if (o.responseText === '1') {
            // user not logged in.
            alert('Somehow you tried to vote without logging in. Please log in first.');
        } else {
            alert('There was a problem voting. Please let us know about this! Error code: ' + o.responseText);
        }
    };

    var handleFailure = function (o)
    {
        NB.voteCancelWait();
        alert('Failure calling vote web service. Please let us know about this!');
    };

    var callback = { success: handleSuccess, failure: handleFailure };
    var postData = 'vote=' + vote + '&bill=' + billid;
    var transaction = YAHOO.util.Connect.asyncRequest('POST', '/vote', callback, postData);
    NB.voteWait();
};



NB.voteWait = function ()
{
    // todo: do something to show the user their vote is being sent
};



NB.voteCancelWait = function ()
{
    // todo: reverse whatever voteWait did.
};

NB.preloadImagesIndex = function() {
    var i = new Image();
    var srcs = [
                '/i/ballot_box_empty.png',
                '/i/ballot_box_bluecheck.png',
                '/i/ballot_box_redx.png'
               ];
    for (var x=0; x < srcs.length; x++) { i.src = srcs[x]; }
}

YAHOO.util.Event.onDOMReady(NB.init);

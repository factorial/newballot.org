NB.init = function () {
    NB.charlimit = 1023; // you have to change this if it changes in the db. i know that sucks, blow me.

    document.getElementById('chars').innerHTML = NB.charlimit - document.getElementById('feedbacktxt').value.length;
    YAHOO.util.Event.addListener('feedbacktxt', 'keyup', NB.countChars);
    YAHOO.util.Event.addListener('feedbacksubmit', 'click', NB.submitTest);
};


NB.submitTest = function() {
    if (document.getElementById('feedbacksubmit').disabled) { return false; }
    return true;
}

NB.countChars = function() {
    var submitButton = document.getElementById('feedbacksubmit');
    var textarea = document.getElementById('feedbacktxt');
    var charlimitmsg = document.getElementById('chars');
    
    var remainingChars = NB.charlimit - textarea.value.length;
    submitButton.disabled = (remainingChars < 0);
    charlimitmsg.innerHTML = remainingChars;
}

YAHOO.util.Event.onDOMReady(NB.init);

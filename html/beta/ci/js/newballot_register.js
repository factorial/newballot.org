NB.init = function ()
{
    // event handlers
    // submits the form when the enter key is clicked
    var keylistener = new YAHOO.util.KeyListener(document, { keys : 13 }, { fn : NB.registerForm });
    keylistener.enable();

    YAHOO.util.Event.addListener('username', 'change', NB.validateUsername );
    YAHOO.util.Event.addListener('email', 'change', NB.validateEmail);
    YAHOO.util.Event.addListener('password', 'keyup', NB.validatePasswords);
    YAHOO.util.Event.addListener('passwordConfirm', 'keyup', NB.validatePasswords);
    YAHOO.util.Event.addListener('submit', 'click', NB.registerForm);

    // document.getElementById('submit').disabled = true;
    document.getElementById('username').focus();
};



NB.setSubmitButtonStatus = function ()
{
    return !(document.getElementById('submit').disabled = !(NB.validPW && NB.validPWConfirm && NB.validEmail && NB.validUsername));
};



NB.registerForm = function ()
{
    if (!NB.setSubmitButtonStatus()) { return false; }

    var form = document.getElementById('registerform');
    var formFields = ['username', 'email', 'password', 'passwordConfirm'];

    var handleSuccess = function (o)
    {
        var status = document.getElementById('submitstatus');
        form.style.display = 'none';
        if (o.responseText === '0') {
            status.innerHTML = "Your new account has been created and you are now logged in! "+
                               "Time to <a href='/'>start voting</a>, but don't forget to "+
                               "<a href='/my'>fill out your personal info</a> so NewBallot "+
                               "can work for you.";
        } else {
            status.innerHTML = "There was an error registering your account (error " + o.responseText +
                               "). Please <a href='/feedback'>let us know</a> about this error and "+
                               "try again.";
        }
    };

    var handleFailure = function (o)
    {
        var status = document.getElementById('submitstatus');
        status.innerHTML = 'Error: ' + o.status + ' : ' + o.statusText;
    };

    var callback = { success: handleSuccess, failure: handleFailure };

    YAHOO.util.Connect.setForm(form);
    var transaction = YAHOO.util.Connect.asyncRequest('POST', 'create_user', callback);
};



NB.validUsername = false;
NB.validPW = false;
NB.validPWConfirm = false;
NB.validEmail = false;



NB.validateUsername = function ()
{
    var username = this;
    var userNameShort = document.getElementById('userNameShort');
    var userNameOk = document.getElementById('userNameOk');
    var userNameUnavailable = document.getElementById('userNameUnavailable');
    var userNameInvalid = document.getElementById('userNameInvalid');

    // default state
    userNameShort.style.display = 'none';
    userNameOk.style.display = 'none';
    userNameUnavailable.style.display = 'none';
    userNameInvalid.style.display = 'none';
    NB.validUsername = false;

    // length check
    if (username.value.length < 6)
    {
        userNameShort.style.display = 'inline';
        return NB.setSubmitButtonStatus();
    }

    // available check
    var handleSuccess = function (o)
    {
        if (o.responseText !== undefined)
        {
            if (o.responseText === '1')
            {
                userNameUnavailable.style.display = 'inline';
            } else {
                userNameOk.style.display = 'inline';
                NB.validUsername = true;
            }

            return NB.setSubmitButtonStatus();
        }
    };

    var handleFailure = function (o) { if (o.responseXML !== undefined) { alert('Failure checking username validity.'); } };

    var callback = { success : handleSuccess, failure : handleFailure, argument : {} };
    var postData = 'username=' + encodeURIComponent(username.value);
    var sUrl = '/check_username';
    var transaction = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback, postData);
};



NB.validateEmail = function() {
    document.getElementById('emailOk').style.display = 'none';
    document.getElementById('emailInvalid').style.display = 'none';
    document.getElementById('emailUnavailable').style.display = 'none';

    var email = document.getElementById('email');

    NB.validEmail = false;

    var emailTest = /^[a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.]{1,64}@[a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.]{1,253}\.[a-zA-Z]{2,4}$/; //'
    if (!emailTest.test(email.value))
    {
        document.getElementById('emailInvalid').style.display = 'inline';
        return NB.setSubmitButtonStatus();
    }

    // available check
    var handleSuccess = function (o)
    {
        if (o.responseText !== undefined)
        {
            if (o.responseText === '1')
            {
                document.getElementById('emailUnavailable').style.display = 'inline';
            }
            else
            {
                document.getElementById('emailOk').style.display = 'inline';
                NB.validEmail = true;
            }

            return NB.setSubmitButtonStatus();
        }
    };

    var handleFailure = function (o) { if (o.responseXML !== undefined) { alert('Failure checking email availability.'); } };

    var callback = { success : handleSuccess, failure : handleFailure, argument : {} };
    var postData = 'email=' + encodeURIComponent(email.value);
    var sUrl = '/check_email';
    var transaction = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback, postData);
    return NB.setSubmitButtonStatus();
};



NB.validatePasswords = function()
{
    NB.validatePassword();
    NB.validatePasswordConfirm();
};



NB.validatePassword = function()
{
    document.getElementById('passwordAcceptable').style.display = 'none';
    document.getElementById('passwordShort').style.display = 'none';
    NB.validPW = false;
    var minPwLen = 6;

    if (document.getElementById('password').value.length < minPwLen)
    {
        document.getElementById('passwordShort').style.display = 'inline';
    }
    else
    {
        document.getElementById('passwordAcceptable').style.display = 'inline';
        NB.validPW = true;
    }

    return NB.setSubmitButtonStatus();
};



NB.validatePasswordConfirm = function()
{
    document.getElementById('passwordMatch').style.display = 'none';
    document.getElementById('passwordDontMatch').style.display = 'none';
    NB.validPWConfirm = false;

    if (document.getElementById('password').value === document.getElementById('passwordConfirm').value)
    {
        document.getElementById('passwordMatch').style.display = 'inline';
        NB.validPWConfirm = true;
    }
    else
    {
        document.getElementById('passwordDontMatch').style.display = 'inline';
    }

    return NB.setSubmitButtonStatus();
};



YAHOO.util.Event.onDOMReady(NB.init);

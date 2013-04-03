<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?= $doctype; ?>
<html>
<head>
<title><?= $page_title; ?></title>
<?= $favicon; ?>
<?= $stylesheets; ?>
<?= $pre_load_js; ?>
</head>

<body>
<div id='bodywrapper'>

<?= $header ?>

<div id='body'>
    <div class='bluebox_tl'>
        <div class='bluebox_tr'>
            <div class='bluebox_bl'>
                <div class='bluebox'>
                    <h2>Welcome to NewBallot!</h2>
                    <h3>
                        You'll be voting on the same legislation as your Congress members in no time.
                        To create your account, just fill out the information below. Easy, huh? (We'll ask 
                        for your voting district information later.)
                    </h3>
                    <form id='registerform' method='post'>
                        <?= validation_errors(); ?>
                        <div id='usernameDiv'>
                            Username: <input type='text' name='username' id='username' />
                            <span id='userNameOk' style='display:none;'><img src='/i/checkMark.png' style='vertical-align:middle;' /> Username is available.</span>
                            <span id='userNameUnavailable' style='display:none;'><img src='/i/warning.png' style='vertical-align:middle;' /> Username is not available.</span>
                            <span id='userNameInvalid' style='display:none;'><img src='/i/warning.png' style='vertical-align:middle;' /> Username must start with a letter and can only contain letters, numbers, and underscores.</span>
                            <span id='userNameShort' style='display:none;'><img src='/i/warning.png' style='vertical-align:middle;' /> Username must be at least 6 characters long.</span>
                            
                        </div>
                        <div id='emailDiv'>
                            Email Address: <input type='text' id='email' name='email' />
                            <span id='emailOk' style='display:none;'><img src='/i/checkMark.png' style='vertical-align:middle;' /> Email address is acceptable.</span>
                            <span id='emailInvalid' style='display:none;'><img src='/i/warning.png' style='vertical-align:middle;' /> Email address is not a real email address. Please check your spelling.</span>
                            <span id='emailUnavailable' style='display:none;'><img src='/i/warning.png' style='vertical-align:middle;' /> Email address is already used. <a href='/login'>Click here to Login</a>.</span>
                            
                        </div>
                        <div id='passwordDiv'>
                            Password: <input type='password' name='password' id='password' />
                            <span id='passwordAcceptable' style='display:none;'><img src='/i/checkMark.png' style='vertical-align:middle;' /> Password is acceptable.</span>
                            <span id='passwordShort' style='display:none;'><img src='/i/warning.png' style='vertical-align:middle;' /> Password must be at least 6 characters.</span>
                            
                        </div>
                        <div id='confirmPasswordDiv'>
                            Confirm Password: <input type='password' name='passwordConfirm' id='passwordConfirm' />
                            <span id='passwordMatch' style='display:none;'><img src='/i/checkMark.png' style='vertical-align:middle;' /> Passwords match.</span>
                            <span id='passwordDontMatch' style='display:none;'><img src='/i/warning.png' style='vertical-align:middle;' /> Paswords do not match!</span>
                            
                        </div>
                        <input id='submit' type='button' value='Register Now!' disabled='disabled'/>
                    </form>
                    <div id='submitstatus'></div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- body -->

</div> <!-- bodywrapper -->

<?= $post_load_js; ?>
<?= $google_analytics_tracking; ?>
</body>
</html>

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
            <?php echo validation_errors(); ?>

            <div class='redbox_tl'>
                <div class='redbox_tr'>
                    <div class='redbox_bl'>
                        <div class='redbox'>
                            <h2>You must be logged in to use NewBallot.</h2>
                            <h3>Don't have an account? <a href='/register'>Sign up! It's free, quick and painless</a>.</h3>
                            
                            <form id='loginform' name='login' method='post'>
                        
                                <h5>Username</h5>
                                <input type='text' name='username' />
                
                                <h5>Password</h5>
                                <input type='password' name='password' />
                    
                                <input type='submit' value='Submit' />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
</div> <!-- /body -->
    <div id='footer'>
        <?= $footer; ?>
    </div>

</div> <!-- /bodywrapper -->
</body>
<?= $post_load_js; ?>
<?= $google_analytics_tracking; ?>

</html>

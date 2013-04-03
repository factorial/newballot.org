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
                        <h2>NewBallot needs your help! Tell us what you think.</h2>
                        <p>
                            <strong>NewBallot is you.</strong> It exists for your benefit, so naturally
                            we need feedback from you. Please fill out the form below any time you
                            <strong>find a bug</strong>, have a <strong>problem</strong> that needs to be fixed, 
                            an <strong>idea</strong> that would improve the site, or just a comment on
                            what you already like about NewBallot. Any and all feedback is greatly appreciated!
                        </p>
        
                        <div>
                        <?= validation_errors(); ?>
                            <p id='charlimitmsg'>Characters left: <span id='chars'></span></p>
                            <form id='feedbackform' name='feedback' method='post'>
                                <textarea name='feedbacktxt' id='feedbacktxt'></textarea>
                                <input type='submit' id='feedbacksubmit' value='Submit'>
                            </form>
                        </div>
    
                        <p>
                            Problems using this form? Wow, that's a really bad bug, isn't it? 
                            Please <a href='mailto:feedback@newballot.org'>email feedback@newballot.org</a> instead.
                        </p>
                    </div>
                </div> <!-- bluebox_bl -->
            </div> <!-- bluebox_tr -->
        </div> <!-- bluebox_tl -->
        
    </div> <!-- /body -->
    <div id='footer'>
        <?= $footer; ?>
    </div>


</div> <!-- /body_wrapper -->
<?= $post_load_js; ?>
<?= $google_analytics_tracking; ?>
</body>
</html>

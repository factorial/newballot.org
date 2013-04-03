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
                        <h2>Thank you very much!</h2>
                        <p>
                            We've gotten your feedback. It will be read, and if it requires further 
                            communication with you (and you are logged in, or left us some contact info)
                            then we'll be in touch.
                        </p>
    
                        <p>
                            Now how about you <a href='/'>go vote on some stuff</a>? :)
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

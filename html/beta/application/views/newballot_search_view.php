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
    <div class='bluebox_tl'>
        <div class='bluebox_tr'>
            <div class='bluebox_bl'>
                <div class='bluebox'>
                    <h2>Search</h2>
                    <h3>It's pretty easy. Just type something in and hit "Search." You can do this in the little
                        box up above, too.</h3>
                    
                    <form id='searchform' name='search' method='post'>
                    
                        <input type='text' name='q' />
                        <input type='submit' value='Search' />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> <!-- /body -->

</div> <!-- /bodywrapper -->
</body>
<?= $post_load_js; ?>
<?= $google_analytics_tracking; ?>

</html>

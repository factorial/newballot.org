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

                    <h2>The NewBallot Privacy Policy.</h2>
                    <p>
                        I'm not a lawyer. If you are, and want to do a charitable deed by writing us a legally
                        sound privacy policy, <a href='mailto:john@newballot.org'>I'm all ears</a>.
                    </p>
    
                    <p>
                        That said, NewBallot will never sell your personally identifiable information to 
                        anyone for any purpose, even if you beg us to. By default, your votes are purely
                        anonymous. Your Congress members will see vote counts only. In the future, you
                        will be able to opt-in to sharing this information with your legislators, but that 
                        will never be required. NewBallot is a private ballot.
                    </p>
        
                </div> <!-- aboutWrapper -->
            </div> <!-- aboutWrapperBottom -->
        </div> <!-- aboutWrapperTop -->
    </div>

    <div id='footer'>
        <?= $footer; ?>
    </div>

</div> <!-- /body_wrapper -->
<?= $post_load_js; ?>
<?= $google_analytics_tracking; ?>
</body>
</html>

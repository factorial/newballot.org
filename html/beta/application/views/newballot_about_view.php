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
        <div class='greybox sidebar' id='connect'>
        <div class='greybox_tl'>
        <div class='greybox_tr'>
        <div class='greybox_bl'>
        <div class='greybox_br'>
            <h3>Connect with NewBallot</h3>
            <div id='social_google' class='social'>
                <a href="http://groups.google.com/group/newballotorg-mailing-list">
                    <img src="/i/google_groups.png" alt="Subscribe to the NewBallot Google Group">
                </a>
            </div>
            
            <div id='social_twitter' class='social'>
                <a href="http://www.twitter.com/NewBallotOrg">
                    <img src="/i/twitter.png" alt="Follow NewBallotOrg on Twitter"/>
                    <span>Follow us on Twitter</span>
                </a>
            </div>
            
            <div id='social_fb' class='social'>
                <a href="http://www.facebook.com/pages/NewBallotorg/309470986770">
                    <img src="/i/facebook.png" alt="Become a fan of NewBallot.org on Facebook"/>
                    <span>Find us on Facebook</span>
                </a>
            </div>
        </div>
        </div>
        </div>
        </div>
        </div>

        <div class='bluebox_tl' id='about'>
        <div class='bluebox_tr'>
        <div class='bluebox_bl'>
        <div class='bluebox'>
            <h2>What is NewBallot?<br>The next revolution<br>in American democracy.</h2>
            <h3>NewBallot is the power of the Internet finally utilized to connect the People to their government.</h3>

            <p>
                Your elected representatives write, debate, and vote on federal legislation daily. 
                300 million lives are affected by that legislation, but the votes lie in the hands of 
                only 535 people in Washington D.C. <strong>NewBallot is your vote</strong>.
            </p>

            <p>
                NewBallot collects your decisions on any legislation in Congress today, combines them
                with the votes of others in your district, and sends those votes directly to your 
                Congress members every weekend. The voice of their constituency is waiting on your 
                representative's desk every Monday morning.
            </p>

            <p>
                Nice, huh?
            </p>
            
            <p>
                (By the way, NewBallot is in Beta right now, and to us that means it's not quite
                finished. Please check back often to watch the page improve!)
            </p>
        </div>
        </div> 
        </div> 
        </div>
        
        <div id='who'>
            <h2>Who is NewBallot?</h2>
            <h3>First and foremost, YOU. NewBallot is only meaningful if the People use it. So thank you for your support.
            </h3>
    
            <p>
                NewBallot was created by <a href='mailto:john@newballot.org'>John Hayes</a>,
                and could not have launched without the efforts of 
                <a href='http://www.linkedin.com/in/jeffracioppo'>Jeff Racioppo</a>, 
                <a href='http://psyjinx.com/'>Jamie Young</a>, 
                <a href='http://www.linkedin.com/pub/jay-heinlein/11/106/72b'>Jay Heinlein</a>,
                and <a href='mailto:mg.newballot@gmail.com'>Michael Gilreath</a>&mdash;all talented 
                developers to whom NB is grateful and indebted. Thank you, gentlemen.
            </p>
    
            <p>
                While not yet organized as a corporation, NewBallot is not for profit. It was created by
                citizens for citizens. Our mission is to be the essential tool for communication between the People
                and their federal representatives: nothing more than a public service, nothing less than a revolution.
            </p>

        </div>
    </div>

    <div id='footer'>
        <?= $footer; ?>
    </div>
</div> <!-- /body_wrapper -->
<?= $post_load_js; ?>
<?= $google_analytics_tracking; ?>
</body>
</html>

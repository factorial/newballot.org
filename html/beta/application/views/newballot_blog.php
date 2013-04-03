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

        <div id='main'>

            <div class='bluebox_tl'>
            <div class='bluebox_tr'>
            <div class='bluebox_bl'>
            <div class='bluebox'>
                <h1>The NewBallot Blog</h1>
            </div>
            </div>
            </div>
            </div>
    
            <div class='blogbox'>
                <h2>
                    <p class='postdate'>2010.08.13</p> 
                    <p class='posttitle'>What Happened???</p>
                </h2>
                <p>
                    I'm afraid work on the site has come to a grinding halt lately. I just don't have
					the personal time required to bring this project to its conclusion. Do you?
					Would you like to help out? <a href='/feedback'>Please let me know</a>. I'll accept
					help from literally anyone who wants to help out.
                </p>
                <p class='signature'>&mdash; John</p>
            </div>
            <div class='blogbox'>
                <h2>
                    <p class='postdate'>2010.03.09</p> 
                    <p class='posttitle'>So What's Next?</p>
                </h2>
                <p>
                    Here's the minimum of what's planned for the next site update:
                <p>
                    <ul>
                        <li>Showing you the communications which have been sent to your
                            legislators via the <a href='/my'>My Account</a> page.
                        </li>
                        <li>Stop showing enacted or rejected bills for voting.</li>
                        <li>Not sending them to Congress either (we currently do this, it's a bug).</li>
                        <li>If at all possible, an off-site discussion forum, with topics for every bill.</li>
                    </ul>
                </p>
                <p>
                    There are other things I'd like to include but can't promise, so I won't list them
                    here. Do you think something's missing that <strong>really</strong> ought to be
                    included in the next update? <a href='/feedback'>Please let me know</a>.
                </p>
                <p class='signature'>&mdash; John</p>
            </div>
            <div class='blogbox'>
                <h2>
                    <p class='postdate'>2010.02.28</p> 
                    <p class='posttitle'>Site Update</p>
                </h2>
                <p>
                    You're here, so you've already noticed the slick new design. The
                    glossy improved look comes from
                    <a href='http://www.valeriebouchard.com'>Valerie Bouchard</a>, and
                    was quite reasonably priced. Good work, Valerie! The site looks fine in
                    Firefox, Chrome, IE8, but might have some minor issues in IE7 and below.
                    Sorry, update your browser. :) If you see any issues, please
                    <a href='/feedback'>let us know</a>.
                </p>
                <p>
                    Apart from the site design update, this update also fixes a few bugs
                    you guys have reported recently:
                </p>
                <p>
                    <ul>
                        <li>If entering your personal data results in an error, the 
                            <a href='/my'>My Account</a> page actually lets you know.</li>
                        <li>Bills without a summary no longer show up on the front page.</li>
                        <li>Login keeps you on the page you logged in from.</li>
                        <li>Registering logs you in.</li>
                    </ul>
                </p>
                <p>
                    This should be the last design-oriented site update for a long while,
                    so from here on out we'll be focusing on functional improvements. 
                    Next up: categories on the front page to help you
                    find the legislation you're really looking for. There are also a few
                    bugs remaining to work out (e.g. you can currently vote on legislation
                    that's already made it through the legislative process). That will
                    hopefully be fixed this time next week.
                </p>
                <p>
                    Finally, tonight I'm sending my own legislators an email to ask their
                    thoughts on the NewBallot communications they've been getting. A blog
                    update will surely follow their responses. Until then, feel free
                    to <a href='http://groups.google.com/group/newballotorg-mailing-list'>
                    discuss on the Google Group</a>. Until next time!
                </p>
                <p class='signature'>&mdash; John</p>
            </div>

            <div class='blogbox'>
                <h2>
                    <p class='postdate'>2010.02.13</p> 
                    <p class='posttitle'>NewBallot Blog is here!</p>
                </h2>
                <p>
                    Well here it is: the first entry in the NewBallot Blog. This one's 
                    really not much more than a test, but soon we will be updating you on
                    how site development is progressing, political stories you might be
                    interested in, links to friends of the site, and more. 
                </p>
                <p>
                    Now that this page is here, our 
                    <a href="http://groups.google.com/group/newballotorg-mailing-list">Google Groups mailing list</a>
                    is becoming an open forum for people to discuss NewBallot. Feel free to make
                    your own postings there.
                </p>
                <p>
                    What else is going on? Well, here's the prioritized list of upcoming improvements
                    to the site:
                </p>
                <p>
                    <ol>
                        <li>Categories on the front page to help you find bills you're interested in.</li>
                        <li>Showing messages that have been sent to your representatives on the <a href='/my'>My NewBallot</a> page.</li>
                        <li>The "Vote Rationale" bill-commenting system.</li>
                        <li>User verification, so your legislators can be sure you really are their constituency.</li>
                        <li>Legislator sponsorship info on each bill's page</li>
                    </ol>
                    If you have other suggestions, please let us know via the 
                    <a href='/feedback'>feedback page</a>.
                </p>
                <p class='signature'>&mdash; John</p>
                                
    
            </div> <!-- blogbox -->
         </div> <!-- /main -->
    

    </div> <!-- /body -->

    <div id='footer'>
        <?= $footer; ?>
    </div>
</div> <!-- /body_wrapper -->
<?= $post_load_js; ?>
<?= $google_analytics_tracking; ?>
</body>
</html>

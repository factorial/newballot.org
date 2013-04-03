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
    <div class='redbox_tl'><div class='redbox_tr'><div class='redbox_bl'><div class='redbox'>
        <h2><a href='<?=$bill['url'] ?>'><?= $bill['title']; ?></a></h2>
    </div></div></div></div>

    <div class='greybox'>
    <div class='greybox_tl'>
    <div class='greybox_tr'>
    <div class='greybox_bl'>
    <div class='greybox_br'>
        <h3>Bill Summary</h3>
        <div id='billSummary'>
            <?= $bill['summary']; ?>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
            <?php
                $this->load->helper('url');
                $uri_string = uri_string();
                $referrer = substr($uri_string, 1);
                $buttonHref = ($userData? '#'. $bill['id'] : "/login/$referrer");
                $buttonClassYea = ($bill['vote'] === true? 'voteYeaChecked' : 'voteYeaUnchecked');
                $buttonClassNay = ($bill['vote'] === false? 'voteNayChecked' : 'voteNayUnchecked');
            ?>
    <h2 id='comments_soon'>Coming soon: Vote Rationale (a comments system). For now, just vote!</h2>
    <div class='votebuttons'>
        <a class='votebutton yeabutton <?=$buttonClassYea?>' id='<?=$bill['id'] ?>yea' href='<?=$buttonHref ?>'>Yea</a>
        <a class='votebutton naybutton <?=$buttonClassNay?>' id='<?=$bill['id'] ?>nay' href='<?=$buttonHref ?>'>Nay </a>
    </div>
<!--
					<div id="billComments">
                        <h2>Vote Rationale</h2>

						<div id="commentsYea">
                            <h3 id='commentYeaTitle'>Yea</h3>
								<div class="billComment">
                                    <div class='billCommentTitleBar'>
                                        <span class='billCommentName'>USERNAME</span>
                                        <a class='billCommentPlus'>+</a>
                                        <a class='billCommentMinus'>-</a>
                                        <span class='billCommentScore'>SCORE</span>
                                    </div>
                                    <p class='billCommentContent'>COMMENT</p>
								</div>
						</div>
						
                        <div id="commentsNay">
                            <h3 id='commentNayTitle'>Nay</h3>
								<div class="billComment">
                                    <div class='billCommentTitleBar'>
                                        <span class='billCommentName'>USERNAME</span>
                                        <a class='billCommentPlus'>+</a>
                                        <a class='billCommentMinus'>-</a>
                                        <span class='billCommentScore'>SCORE</span>
                                    </div>
                                    <p class='billCommentContent'>COMMENT</p>
								</div>
						</div>
					</div>
-->

</div> <!-- body -->
    
    <div id='footer'>
        <?= $footer; ?>
    </div>
</div> <!-- bodywrapper -->
<?= $post_load_js; ?>
<script type='text/javascript'>
    NB.loggedIn = <?= ($userData? 'true' : 'false'); ?>;
</script>
<?= $google_analytics_tracking; ?>
</body>
</html>

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
    <div id='currentLegislation'>
        <h2>Search Results</h2>

        <table id='billstable'>
        <?php
            $left = false;
            foreach ($searchresults as $bill):?>
            <?php
                $left = !$left;
                $buttonHref = ($userData? '#'. $bill['id'] : '/login');
                $buttonClassYea = ($bill['vote'] === true? 'voteYeaChecked' : 'voteYeaUnchecked');
                $buttonClassNay = ($bill['vote'] === false? 'voteNayChecked' : 'voteNayUnchecked');

                $bill['article_number'] = $bill['type'] .'.'. $bill['number'];
                if (!trim($bill['summary'])) { $bill['summary'] = "No summary yet for this bill. Click the title or (more) link for more information."; }
            ?>

            <?php if ($left): ?>
            <tr>
            <?php endif; ?>
            
            <td>
            <div class='smallVoteBox <?= ($left? 'voteboxleft' : 'voteboxright')?>'>
                <h3 class='voteTitle'><a href='/bill/<?= $bill['article_number']; ?>'><?= $bill['title']; ?></a></h3>

                <div class='voteSummary' id='<?= $bill['article_number']; ?>'>
                    <h4>Summary:</h4>
                    <p><?= $bill['summary'] ?>... <a class='voteSummaryMore' href='/bill/<?=$bill['article_number']?>'>(more)</a></p>
                </div>
    
                <div class='votebuttons'>
                    <a class='votebutton yeabutton <?=$buttonClassYea?>' id='<?=$bill['id']?>yea' href='<?=$buttonHref ?>'>Yea</a>
                    <a class='votebutton naybutton <?=$buttonClassNay?>' id='<?=$bill['id']?>nay' href='<?=$buttonHref ?>'>Nay </a>
                </div>

            </div>
            </td>

            <?php if (!$left): ?>
            </tr>
            <?php endif; ?>

        <?php endforeach; ?>
        </table>
    </div> <!-- currentLegislation -->
        
</div> <!-- body -->
<div id='footer'>
    <!-- put the most recent update to the congress xml data date here -->
</div>
</div><!-- bodywrapper -->

<?= $post_load_js; ?>
<script type='text/javascript'>
    NB.loggedIn = <?= ($userData? 'true' : 'false'); ?>;
</script>
<?= $google_analytics_tracking; ?>

</body>
</html>


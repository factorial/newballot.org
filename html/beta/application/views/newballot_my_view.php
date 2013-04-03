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
        <?php if (!$userData->is_verified) :
            // todo: one day have verification. right now this does nothing
        endif; ?>

        <?php if (trim($userData->address)): ?>
            <div class='bluebox_tl' id='reps'>
            <div class='bluebox_tr'>
            <div class='bluebox_bl'>
            <div class='bluebox'>
            <?php if ($userData->district && $userData->state) : ?>
                <h2>
                    You belong to <?= htmlentities(strtoupper($userData->state), ENT_QUOTES) ?> district <?= htmlentities($userData->district, ENT_QUOTES) ?>.
                    Here are your legislators:
                </h2>
                <ul>
                <?php 
                    if (is_array($congressmembers)) :
                        foreach ($congressmembers as $c) :
                            $name = ($c->is_senator? 'Senator' : 'Rep.'). ' '. 
                                    $c->first_name .' '.
                                    $c->last_name;
                ?>
                    <li class='congressmember'>
                        <a href='<?= $c->website?>'><?= $name; ?></a>
                    </li>
                <?php endforeach; endif; ?>
                </ul>
            <?php else : ?>
                <h3>
                    We could not determine your congressional district! If you have a 
                    suite or apartment number, remove that and try again.
                </h3>
                <h4>If that doesn't work, just <a href='/'>go vote</a> on some legislation. 
                    We check on these things regularly and will have it fixed for you  
                    soon enough. Or let us know now through the <a href='/feedback'>feedback
                    page</a>.
                </h4>
            <?php endif; ?>
            </div>
            </div>
            </div>
            </div>
        <?php endif; ?>
    
        <div class='redbox_tl'>
        <div class='redbox_tr'>
        <div class='redbox_bl'>
        <div class='redbox'>
        <h2>User Information</h2>
        <h3>NewBallot only works if we know your address. How else could we know which legislator to send your votes to?</h3>
        <p>So please fill out the information below. <a href='/privacy'>Rest assured, this information will <strong>absolutely never</strong> be sold. Click here for our privacy policy.</a>
        </h3>
        <form method='post' id='addressform'>
            <?php echo validation_errors(); ?>
            <div id='required'>
                <h4>First, the stuff we need to know:</h4>
                <label for='address'>Address: <input type='text' name='address' id='address' value='<?= htmlentities($userData->address, ENT_QUOTES) ?>'></label>
                <label for='city'>City: <input type='text' name='city' id='city' value='<?= htmlentities($userData->city, ENT_QUOTES) ?>'></label>
                <label for='state'>State: <input type='text' name='state' id='state' length=2 value='<?= strtoupper(htmlentities($userData->state, ENT_QUOTES)) ?>'></label>
                <label for='zip'>Zip: <input type='text' name='zip' id='zip' length=10 value='<?= htmlentities($userData->zip, ENT_QUOTES) ?>'></label>
            </div>

            <div id='optional'>
                <h4>The rest of this is optional:</h4>
                <label for='firstname'>First Name: <input type='text' name='firstname' id='firstname' value='<?= htmlentities($userData->firstname, ENT_QUOTES) ?>'></label>
                <label for='lastname'>Last Name: <input type='text' name='lastname' id='lastname' value='<?= htmlentities($userData->lastname, ENT_QUOTES) ?>'></label>
                <!--
                
                <label for='password'>Change password: <input type='password' name='password' id='password' value=''></label>
                <label for='passwordconfirm'>Confirm new password: <input type='password' id='passwordconfirm' value=''></label>
                -->
                <label for='email'>Email address: <input type='text' name='email' id='email' length=10 value='<?= htmlentities($userData->email, ENT_QUOTES) ?>'></label>
            </div>

            <input type='submit' id='submitbutton' value='Update Info'>
        </form>
        </div>
        </div>
        </div>
        </div>

<!-- todo!! implement it in the controller first -->
        <!--<div id='myvotes'>
            <h2>My recent votes</h2>
            <p>TODO</p>
        </div>-->

</div> <!-- body -->
    <div id='footer'>
        <?= $footer; ?>
    </div>

</div> <!-- bodywrapper -->
<?= $post_load_js; ?>
<?= $google_analytics_tracking; ?>
</body>
</html>

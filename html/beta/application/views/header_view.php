<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<header>
	<h1><span>New</span><span>Ballot</span></h1>
	<div id='headspace'>
		<div class='search'>
            <form action='/search' method='post'>
				<input type='text' value='' name='q'>
			</form>
		</div>
		<div class='voterecorded'>
			<p>Vote Recorded.</p>
			<div id='voterecorded_loggedout'>
				<span>Login to make it count:</span>
				<input type='password'>
				<input type='button' value='login'>
				<a href='/register' class='button'>New user? Vote away! When you're ready, click here to register.</a>
			</div>
		</div>
	</div>
</header>

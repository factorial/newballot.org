<!doctype html>
<html>
<head>
	<title>NewBallot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<style>
		html { background-color: #fafafa}
		body {padding: 0; margin:0; font-family: "TREBUCHET MS", serif; font-size:12px;}
		@media only screen and (min-width:730px) {
			body { font-size:16px; }
		}

		/* header */
		header { height: 100px; position:absolute; top:0; width:100%; background-color:#eee; border-bottom: solid 5px #ccc; z-index:100}
		header p { margin:0}
		header h1 { font-family: georgia, serif; margin:0; font-size:60px; margin-left: 5px; float:left}
		header div#headspace { padding-top: .75em; float:right; width:50%; padding-right: 1%;}
		header h1 span { display:block; float: left; color:#000f66; margin-right:20px;}
		header h1 span:first-child { color: #c92522; margin-right:0;} 
		header h1 span::first-letter { font-size:60px; }
		header .search { width:100%}
		header .search input:first-child { font-size: 24px; width:85%;  }
		header .search #searchbutton {width:10%}
		header div.voterecorded { display: none}
		header div#voterecorded_loggedout input[type=password] {width:6em}
		header div#voterecorded_loggedout a {display:block}
		@media only screen and (max-width:730px) { 
			header h1 span { font-size: 0;}
		}

		/*#newuserinfo {
			-moz-transition:height 1s -moz-transform 1s;
			-webkit-transition:height 1s -webkit-transform 1s;
			-o-transition:height 1s -o-transform 1s;
			transition:height 1s transform 1s;
		}*/

		/* new user info box */
		#newuserinfo { background-color: #c92522; color: #fcfcf1; padding:5px; margin:5px;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;
		}
		#newuserinfo > p { margin:0; padding:5px; background-color:white; color:#000; font-weight:bold; font-style:italic; text-align:center;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;
		}
		#newuserinfo ol { list-style-type: none; padding:0}
		#newuserinfo ol li { padding: 0 0 .5em 50px; font-size:90%; margin: 0 0 .5em 10px; min-height:2em;}
		#newuserinfo ol li.step1 {background: url(/i/howitworks_1.png) center left no-repeat; background-size: 2em}
		#newuserinfo ol li.step2 {background: url(/i/howitworks_2.png) center left no-repeat; background-size: 2em}
		#newuserinfo ol li.step3 {background: url(/i/howitworks_3.png) center left no-repeat; background-size: 2em}

		/* bill */
		.bill { background-color: #ececec; margin: 5px; padding:0.5%; margin: 1em 0.5%; position:relative}
		.bill h2 { display:inline-block; width: 80%}
		.bill .billdesc {width: 60% }
		.bill .billdesc p { display:none}
		.bill .billdesc p:first-child { display:block }
		.bill .voteButtons {position: absolute; top:0; right:0; margin-right:10px; line-height:5em; height:100%}
		.bill .voteButtons label {display:block}
		.bill .voteButtons label span { font-size: 300%}
		.bill .voteButtons label span:after {content: "\2610"; }
		.bill .voteButtons .labelyea { color:#000f66}
		.bill .voteButtons .labelnay { color:#c92522}
		.bill .voteButtons .labelyea input:checked + span:after {color:#000f66; content:"\2611"}
		.bill .voteButtons .labelnay input:checked + span:after {color:#c92522; content:"\2612"}
		.bill .voteButtons input {display:none}

		@media only screen and (min-width:730px) { 
			.bill { width: 48%; float:left; }
		}


		#main { margin-top:110px; }
	</style>
</head>
<body>
	<header>
		<h1><span>New</span><span>Ballot</span></h1>
		<div id='headspace'>
			<div class='search'>
				<input type='text' value='' name='q'>
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
	<div id='main'>
<?php
if (!isset($_COOKIE['showNewUserInfo']) || $_COOKIE['showNewUserInfo']):
?>
		<div id='newuserinfo'>
			<p>NewBallot is your direct line to Congress.<br>Here's how it works:</p>
			<ol>
				<li class='step1'>
					You vote on the same laws Congress is considering.
				</li>
				<li class='step2'>
					NewBallot counts your district's votes and sends them to your Congress members.
				</li>
				<li class='step3'>
					Each week your representative gets a report on how you want them to vote.
				</li>
			</ol>
			<label for='chk_hidenewuserinfo'>
				<input type='checkbox' id='chk_hidenewuserinfo' value='hide' checked='checked'>
				<span>Don't show this again</span>
			</label>
			<input type='button' id='but_closenewuserinfo' value='Close'>
		</div>
<?php
endif;
?>

<?php
for ($i=0; $i < 10; $i++):
?>
		<div class='bill'>
			<h2>
				<span class='billnumber'>H. <?= $i ?></span> - <span class='billtitle'>Bill Title</span>
			</h2>
			<div class='billdesc'>
				<p>
					There's a bill description in here. But if you want to see it...
				</p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<p> you're going to have to click the more button. There, that was easy, wasn't it?  </p>
				<input type='button' value='More'>
			</div>
			<div class='voteButtons'>
				<label class='labelyea' for='voteonh<?= $i ?>yea'><input type='radio' value='yea' id='voteonh<?= $i ?>yea' name='voteonh<?= $i ?>'><span>Yea</span></label>
				<label class='labelnay' for='voteonh<?= $i ?>nay'><input type='radio' value='nay' id='voteonh<?= $i ?>nay' name='voteonh<?= $i ?>'><span>Nay</span></label>
			</div>
		</div>
<?php
endfor;
?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="/js/COOKIES.js"></script>
	<script src="/js/NB.Header.js"></script>
	<script src="/js/NB.NewUserInfo.js"></script>
	<script src="/js/NB.Bill.js"></script>
	<script>
		var NB = NB || {};


		$().ready(function () {
			/* On DOM Ready */
			var header = NB.Header(),
			    newUserInfo = NB.NewUserInfo(),
				billsCollection = NB.Bill(header);
		});
	</script>
</body>
</html>

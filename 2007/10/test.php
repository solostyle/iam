<?		session_start();
	include '../../235/func.php';
	include '../../235/storevars.php';
	include '../../inc/header.php';
	include '../../left.html'; ?><? $_SESSION['entry_id'] = "tag:iam.solostyle.net,2007-10-11:/2007/10/test"; ?>
		<div class="publishedentry">
			<h2>Test</h2>
			<h3>11 October 2007  @ 22:31 by solostyle</h3>
			<p>Herro! blah! i'm archana!</p>
			<div style="clear:both; padding-bottom: 0.25em;"></div>
			<p>
				<em><a name="bot" href="http://iam.solostyle.net/comment.php">comments</a></em>
			</p>
		</div>
	<?	include '../../comment.html';
	include '../../right.php';
	include '../../inc/footer.php';	?>
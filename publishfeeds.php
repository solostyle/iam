<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

// check to see if logged in first.
if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

	print '
	<h2>Publish Feeds</h2>
	<form name="publishfeed" method="post" action="publishfeeds-confirm.php">
		<p>
		<input type="checkbox" name="rss" />rss&nbsp;&nbsp;
		<select name="rss_num">
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7" selected="selected">7</option>
		</select>
		<br />
		<input type="checkbox" name="atom" />atom&nbsp;&nbsp;
		<select name="atom_num">
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7" selected="selected">7</option>
		</select>
		<br />
		<input type="submit" name="submit" value="Submit" /></p>
	</form>';
}

else
	print "<p>Sorry, that function is limited to authenticated users.</p>";

	include 'inc/footer.php';
?>

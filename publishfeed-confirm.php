<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

// check to see if logged in first.
if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

	select_db($s, $u, $p, $db);
	if (isset($_POST['rss'])) {
		publish_feed("rss", $_POST['rss_num']);
		print '<h1>RSS feed published</h1>';
	}
	if (isset($_POST['atom'])) {
		publish_feed("atom", $_POST['atom_num']);
		print '<h1>Atom feed published</h1>';
	}
	mysql_close();
}

else
	print "<p>Sorry, that function is limited to authenticated users.</p>";

	include 'inc/footer.php';
?>

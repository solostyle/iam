<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

// check to see if logged in first.
if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

	print '<h2>Delete Entries</h2>';

	select_db($s, $u, $p, $db);
	print '<div><form action="delete-confirm.php" method="post">';
	$now = 'current_timestamp';
	print show_entries_by_time(0, $now, "republish"); //i know it shouldnt say republish
	print '<input type="submit" name="submit_delete" value="Delete" />
		</form></div>';
	mysql_close();
}
else
	print "<p>Sorry, that function is limited to authenticated users.</p>";

	include 'inc/footer.php';
?>

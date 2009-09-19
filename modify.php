<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

// check to see if logged in first.
if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

	print '<h2>Modify an Entry</h2>';

	select_db($s, $u, $p, $db);
	print '<div><form name="modify" method="post" action="edit.php">';
	$now  = my_mktime();
	$now_f = strftime('%G-%m-%d %H:%M:%S',$now);
	print show_entries_by_time(0, $now_f, "republish"); //i know it shouldnt say republish
	print '<input type="submit" name="submit_edit" value="Edit" />
		</form></div>';
	mysql_close();
}
else
	print "<p>Sorry, that function is limited to administrators.</p>";

	include 'inc/footer.php';
?>

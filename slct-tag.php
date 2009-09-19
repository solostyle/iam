<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

// list all tags to choose from
if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

	select_db($s, $u, $p, $db);

	print '<h2>Select tag</h2>';
	// first display a form to allow the user to choose a range of entries
	print '<form name="choose" method="post" action="slct-tag-confirm.php">';
	print list_tags();
	print '<input type="submit" name="submit" value="Submit" /></form>';

	mysql_close();
}
else
	print "<p>Sorry, that function is limited to administrators.</p>";

	include 'inc/footer.php';
?>

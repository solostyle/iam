<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include_once 'inc/header.php';
	include 'left.php';

if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

	select_db($s, $u, $p, $db);

	// create a new blog_id from the title in case it has changed
	$newyear = substr($_POST['oldtime'], 0, 4);
	$newmonth = substr($_POST['oldtime'], 5, 2);
	$newdate = substr($_POST['oldtime'], 8, 2);
	$newid = create_id($_POST['newtitle'], $newyear, $newmonth, $newdate);

	// add slashes to entry and title
	$newentry = addslashes ($_POST['newentry']);
	$newtitle = addslashes ($_POST['newtitle']);

	// use the old id to get the entry from the table
	$uv = array($newid, $_POST['oldtime'], $newtitle, $newentry);
	update_record($af,$uv,$_POST['oldid']); // but update record with new id
	mysql_close();
	print("<h1>Your entry was edited!</h1>");
}
else
	print "<p>Sorry, that function is limited to administrators.</p>";

	include 'inc/footer.php';
?>

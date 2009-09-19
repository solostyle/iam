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

	$blog_id_array = $_POST["republish"];
	for ($i = 0;$i<count($blog_id_array);$i++) {
		$entryarray = get_entry($blog_id_array[$i]);
		$republish_values = array($entryarray[0], $entryarray[1], $entryarray[2], $entryarray[3], $entryarray[4]);
		publish_entry($republish_values, 'w+');
		$url = make_url($entryarray[0]);
		print "<p>Your entry was republished! <a href=\"$url\">View</a></p>";
	}
	mysql_close();
}
else
	print "<p>Sorry, that function is limited to authenticated users.</p>";

	include 'inc/footer.php';
?>

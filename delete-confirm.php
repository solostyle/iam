<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

// check to see if logged in first.
if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

	if (!$_POST['republish']) print("<h1>Oops!</h1><p>You did not select anything.</p>");

	else if (isset($_POST['republish'])) {
		select_db($s, $u, $p, $db);
		$blog_id_array = $_POST["republish"];

		for ($i = 0;$i<count($blog_id_array);$i++) {
			$entryarray = rtrv_entries($blog_id_array[$i]);
			$deletetitle = $entryarray[3];
			delete_record($blog_id_array[$i]);
			print "<p><em>$deletetitle</em> was deleted!</p>";
		}
	} // end else if statement
}
else
	print "<p>Sorry, that function is limited to administrators.</p>";

	include 'inc/footer.php';
?>

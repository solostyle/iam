<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include_once 'inc/header.php';
	include 'left.php';

if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

	select_db($s, $u, $p, $db);

	print '<h2>Categorize Entries</h2>';
	// first display a form to allow the user to choose a range of entries
	print '<form name="choose" method="post">';
	print '<p>Starting date: ';
	print list_months("start_month",$_SESSION['cat_start_month']);
	print '&nbsp;';
	print list_years("start_year",$_SESSION['cat_start_year']);
	print '</p>';
	print '<p>Ending date: ';
	print list_months("end_month",$_SESSION['cat_end_month']);
	print '&nbsp;';
	print list_years("end_year",$_SESSION['cat_end_year']);
	print '</p>';

	print '<input type="submit" name="submit" value="Submit" /></form>';

	if (isset($_POST['start_month'])) {
		$start  = mktime(0, 0, 0, $_POST['start_month'], 1, $_POST['start_year']);
		$start_f = strftime('%G-%m-%d %H:%M:%S',$start);
		// get the last day of the month, because we want to be inclusive!!
		$end = mktime(0, 0, 0, $_POST['end_month'], 1, $_POST['end_year']);
		$days_in_month = date("t", $end);
		$end = mktime(0, 0, 0, $_POST['end_month'], $days_in_month, $_POST['end_year']);

		$end_f = strftime('%G-%m-%d %H:%M:%S',$end);

		// show entries in range with ability to select multiple tags 
		// maybe just allow tag assignment for one entry at a time
		print '<div><form name="tag" method="post" action="categorize-confirm.php">';
		print show_entries_by_time($start_f, $end_f, "categorize");
		print '<input type="submit" name="submit_categorize" value="categorize" />
			</form></div>';

		// Save in SESSION: list_months and list_years use these 
		$_SESSION['cat_start_month'] = $_POST['start_month'];
		$_SESSION['cat_start_year'] = $_POST['start_year'];
		$_SESSION['cat_end_year'] = $_POST['end_year'];
		$_SESSION['cat_end_month'] = $_POST['end_month'];
	}

	mysql_close();
}
else
	print "<p>Sorry, that function is limited to administrators.</p>";

	include 'inc/footer.php';
?>

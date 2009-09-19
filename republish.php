<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include_once 'inc/header.php';
	include 'left.php';

if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

	select_db($s, $u, $p, $db);

	print '<h2>Republish Entries</h2>';
	// first display a form to allow the user to choose a range of entries
	print '<form name="choose" method="post">';
	print '<p>Starting date: ';
	print list_months("start_month",$_SESSION['rep_start_month']);
	print '&nbsp;';
	print list_years("start_year",$_SESSION['rep_start_year']);
	print '</p>';
	print '<p>Ending date: ';
	print list_months("end_month",$_SESSION['rep_end_month']);
	print '&nbsp;';
	print list_years("end_year",$_SESSION['rep_end_year']);
	print '</p>';

	print '<input type="radio" name="republish" value="individual" /> Choose Individual Entries <br />
		<input type="radio" name="republish" value="range" /> Republish All in Range <br />
		<input type="submit" name="submit" value="Submit" /></form>';

	if (isset($_POST['republish'])) {
		$start  = mktime(0, 0, 0, $_POST['start_month'], 1, $_POST['start_year']);
		$start_f = strftime('%G-%m-%d %H:%M:%S',$start);
		$end = mktime(0, 0, 0, $_POST['end_month'], 1, $_POST['end_year']);
		$end_f = strftime('%G-%m-%d %H:%M:%S',$end);

		// if user wants to choose individual ones, show the entries and process
		if ($_POST['republish'] == 'individual') {
			print '<div><form name="republish" method="post" action="republish-confirm.php">';
			print show_entries_by_time($start_f, $end_f, "republish");
			print '<input type="submit" name="submit_republish" value="Republish" />
				</form></div>';
		}

		// otherwise, republish all entries in the chosen range
		else {
			$result = get_entries_by_time($start_f, $end_f);
			while ($row = mysql_fetch_array($result)) {
				$republish_values = array($row[0], $row[1], $row[2], $row[3], $row[4]);
				publish_entry($republish_values, 'w+');
				$url = make_url($row[0]);
				print "<p>Your entry was republished! <a href=\"$url\">View</a></p>";
			}
		}
	}
	else print '<p>Please select an option</p>';

	// Save in SESSION: list_months and list_years use these 
		$_SESSION['rep_start_month'] = $_POST['start_month'];
		$_SESSION['rep_start_year'] = $_POST['start_year'];
		$_SESSION['rep_end_year'] = $_POST['end_year'];
		$_SESSION['rep_end_month'] = $_POST['end_month'];


	mysql_close();
}
else print "<p>Sorry, that function is limited to administrators.</p>";

	include 'inc/footer.php';
?>

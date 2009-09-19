<?
// This file will set session variables for the selected start_month, start_year, end_month, and end_year
// values entered in this form

	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

// anyone can see this
select_db($s, $u, $p, $db);

if (isset($_POST['view_typ'])) {
	// Format form values
	$start  = mktime(0, 0, 0, $_POST['start_month'], 1, $_POST['start_year']);
	$start_f = strftime('%G-%m-%d %H:%M:%S',$start);
	// get the last day of the month, because we want to be inclusive!!
	$end = mktime(0, 0, 0, $_POST['end_month'], 1, $_POST['end_year']);
	$days_in_month = date("t", $end);
	$end = mktime(0, 0, 0, $_POST['end_month'], $days_in_month, $_POST['end_year']);
	$end_f = strftime('%G-%m-%d %H:%M:%S',$end);

	// Save in SESSION: list_months and list_years use these 
	$_SESSION['arch_start_month'] = $_POST['start_month'];
	$_SESSION['arch_start_year'] = $_POST['start_year'];
	$_SESSION['arch_end_year'] = $_POST['end_year'];
	$_SESSION['arch_end_month'] = $_POST['end_month'];
	$_SESSION['arch_view_typ'] = $_POST['view_typ'];

	// Preview
	if ($_POST['view_typ'] == 'preview') 
		print show_entries_by_time($start_f, $end_f, "preview");

	// Full 
	else 
		print show_entries_by_time($start_f, $end_f, "full");
}

mysql_close();

	include 'inc/footer.php';
?>

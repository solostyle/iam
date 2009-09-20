<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';

	// load the left pane
        include 'left.php';

	// show the entry requested in the URL
	select_db($s, $u, $p, $db);
	$id = $_GET['id'];
	$e = rtrv_entries($id);
	print make_entry($e);
	mysql_close();

	include 'inc/footer.php';
?>

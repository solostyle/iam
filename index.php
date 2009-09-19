<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';

	include 'left.php';

	select_db($s, $u, $p, $db);
	print preview_recent(3);
	mysql_close();

	include 'inc/footer.php';
?>

<?php
$s = "mysql.solostyle.net";
$u = "solostyle";
$p = 'qas??wed';
$db = "bkm_website";

$doc_root = $_SERVER['DOCUMENT_ROOT'];

//entries

$af = array('id','username','time','title','entry');  								// all fields
$f = array('username','time','title','entry');        								// fields to insert
	// Need to take the apostrophes out
	$postentry = addslashes ($_POST["entry"]);
	$posttitle = addslashes ($_POST["title"]);
$v = array($_POST["username"],$_POST["time"],$posttitle,$postentry);      // values to insert, but actually i add ID, created after submitting

// comments

$acf = array('tag','post_tag','name','website','email','comment','time');
$cf = array('name','website','email','comment','time');
$cv = array($_POST['name'],$_POST['website'],$_POST['email'],$_POST['comment'],$_POST['time']);

//for archive purposes
$ARCHIVE_YEARMONTH = date('Ym');

?>

<?
$s = "mysql.solostyle.net";
$u = "solostyle";
$p = 'qas??wed';
$db = "iam";

$doc_root = $_SERVER['DOCUMENT_ROOT'];

//entries

// do not enter username for now (was 2nd item in array)
// all fields
$af = array('id','time','title','entry');
// fields to insert
$f = array('time','title','entry');
	// Need to take the apostrophes out
	$postentry = addslashes ($_POST["entry"]);
	$posttitle = addslashes ($_POST["title"]);

// values to insert, but actually i add ID, created after submitting
$v = array($_POST["time"],$posttitle,$postentry);

// comments

$acf = array('comment_id','blog_id','name','website','email','comment','time');
$cf = array('name','website','email','comment','time');
$cv = array($_POST['name'],$_POST['website'],$_POST['email'],$_POST['comment'],$_POST['time']);

//for archive purposes
$ARCHIVE_YEARMONTH = date('Ym');

?>

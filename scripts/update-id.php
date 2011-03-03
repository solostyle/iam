<?
session_start();
include '235/func.php';

print '<html><head><title>blah</title></head>
	<body>
	<form method="post">
	<input type="submit" value="submit" name="submit" />
	</form>
	</body>
	</html>';

if (isset($_POST['submit'])) {

$s = "mysql.solostyle.net";
$u = "solostyle";
$p = 'qas??wed';
$db = "iam";

select_db($s, $u, $p, $db);

update_blog_id_again();

mysql_close();

}


?>
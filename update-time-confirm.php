<?
session_start();
include '235/func.php';

$s = "mysql.solostyle.net";
$u = "solostyle";
$p = 'qas??wed';
$db = "iam";

select_db($s, $u, $p, $db);

update_entry_time();

mysql_close();

?>

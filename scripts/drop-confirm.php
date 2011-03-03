<?
session_start();

function select_db($server, $usr, $pw, $dbname) {
   mysql_connect($server,$usr,$pw) or die("Couldn't connect!<br />");
   mysql_select_db($dbname);
}

function copy_tables($prefix) {
	$tables = get_tables($prefix);

	for ($i=0; $i < count($tables); $i++)
		$query .= "insert into `blog` (`id`,`username`,`time`,`title`,`entry`) (select * from `" . $tables[$i] . "`); ";

	print $query;
	mysql_query($query);
}

function drop_tables($prefix) {
	$tables = get_tables($prefix);

	$query = 'drop table `' . $tables[0] . "`";
	for ($i=1; $i < count($tables); $i++)
		$query .= ", `" . $tables[$i] . "`";
	$query .= ";";

	print $query;
	mysql_query($query);
}

function get_tables($prefix) {

	$gettables = "SHOW TABLES LIKE '" . $prefix . "%'";
	print $gettables;

	$tableslist = mysql_query($gettables);
	$tablesarray = array();

	$num_tables = mysql_num_rows($tableslist);
	for ($i=0; $i < $num_tables; $i++) {
		$row = mysql_fetch_array($tableslist);
		array_push($tablesarray, $row[0]);
	}
	mysql_free_result($tableslist);
	return $tablesarray;
}



$s = "mysql.solostyle.net";
$u = "solostyle";
$p = 'qas??wed';
$db = "iam";

select_db($s, $u, $p, $db);

$year = $_POST['year'];
print $year;
print $_POST['func'];
if ($_POST['func'] == "copy") 
	copy_tables($year);
if ($_POST['func'] == "drop")
	drop_tables($year);

mysql_close();

?>

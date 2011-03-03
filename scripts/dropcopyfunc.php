<?php
function select_db($server, $usr, $pw, $dbname) {
   mysql_connect($server,$usr,$pw) or die("Couldn't connect!<br />");
   mysql_select_db($dbname);
}

function copy_tables($prefix) {
	$tables = get_tables($prefix);

	for ($i=0; $i < count($tables); $i++)
		$query .= "insert into `blog` (select * from `" . $tables[$i] . "`);";

	mysql_query($query);
}

function del_tables($prefix) {
	$tables = get_tables($prefix);

	$query = 'drop table ' . $tables[0];
	for ($i=0; $i < count($tables); $i++)
		$query .= "," . $tables[$i];
	$query .= ";";

	mysql_query($query);
}

function get_tables($prefix) {

	$gettables = "SHOW TABLES LIKE '" . $prefix . "%'";

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



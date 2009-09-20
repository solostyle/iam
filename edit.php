<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include_once 'inc/header.php';
	include 'left.php';

if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

	select_db($s, $u, $p, $db);

	$blog_id_array = $_POST["republish"];
	for ($i = 0;$i<count($blog_id_array);$i++) {
		$entryarray = rtrv_entries($blog_id_array[$i]);
		$editusername = $entryarray[1];
		$edittime = $entryarray[2];
		$parsedtime = parse_date($entryarray[2]) . parse_time($entryarray[2]);
		$edittitle = $entryarray[3];
		$edittext = $entryarray[4];

		print("<h2>Modify the Entry</h2>';

	<div><form action=\"edit-confirm.php\" method=\"post\">
	<p>Username: <input type=\"text\" readonly=\"readonly\" name=\"oldusername\" size=\"15\" value=\"$editusername\" /></p>
	<p>Title: <input type=\"text\" name=\"newtitle\" size=\"50\" value=\"$edittitle\" /></p>
	<p>Use HTML tags to format.</p>
	<p>Entry: <textarea name=\"newentry\" cols=\"45\" rows=\"20\">$edittext</textarea></p>
	<p>Time: <input type=\"text\" readonly=\"readonly\" size=\"30\" value=\"$parsedtime\" /><input type=\"hidden\" name=\"oldtime\" value=\"$edittime\" />

	<input type=\"hidden\" name=\"oldid\" value=\"$entryarray[0]\" />
	<input type=\"hidden\" name=\"table\" value=\"$table\" />

	<input type=\"Reset\" name=\"reset\" value=\"Reset\" />&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"submit\" value=\"Submit\" />
	</form></div>
		");
	}
	mysql_close();
}
else
	print "<p>Sorry, that function is limited to authenticated users.</p>";

	include 'inc/footer.php';
?>

<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

// check to see if logged in first.
if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {
	select_db($s, $u, $p, $db);

	// retrieve the blog_id and the category
	$blog_id = $_POST['blog_id'];
	$cat_nm = $_POST['cat_nm'];
	assign_category($blog_id, $cat_nm);

	// process the write-in
	if ($_POST['cat_wi']>'')
		assign_category($blog_id, $_POST['cat_wi']);

	$url = make_url($blog_id);
	print "<p>Your entry was categorized! <a href=\"$url\">View</a></p>";
	mysql_close();
}
else
	print "<p>Sorry, that function is limited to authenticated users.</p>";

	include 'inc/footer.php';
?>

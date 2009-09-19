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

	// retrieve the blog_id and the tags
	$blog_id = $_POST['blog_id'];
	$tag_nms = $_POST['tag_nms'];

	// delete all the tags for this blog_id, and re-add them
	unassign_tags($blog_id);

	for ($i = 0;$i<count($tag_nms);$i++) {
		assign_tag($blog_id, $tag_nms[$i]);
	}

	// process the write-ins
	if ($_POST['tag_wi']>'') {
		$tag_wi_array = explode(",",$_POST['tag_wi']);
		for ($j=0;$j<count($tag_wi_array);$j++) {
			assign_tag($blog_id, $tag_wi_array[$j]);
		}
	}

	$url = make_url($blog_id);
	print "<p>Your entry was tagged! <a href=\"$url\">View</a></p>";
	mysql_close();
}
else
	print "<p>Sorry, that function is limited to authenticated users.</p>";

	include 'inc/footer.php';
?>

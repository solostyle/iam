<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

// check to see if logged in first.
if ( (isset($_SESSION['user_id']) &&
     (substr($_SERVER['PHP_SELF'],-11) != 'signout.php')) ) {

    $verify = trim($_POST['verify']);
    if ($verify != $_SESSION['verify_string']) {
     	print "<p>You do not have a brain. Go away.</p>";
     	exit();
     }

	$blog_id = create_id($_POST['title'], $_POST['year'], $_POST['month'], $_POST['date']);
	$blog_id_array = array($blog_id);
	$av = array_merge($blog_id_array, $v);
	$url = make_url($blog_id);

	select_db($s, $u, $p, $db);
	
	// insert the blog entry
	$posttime = $_POST["year"] . $_POST["month"] . $_POST["date"];
	insert_record('blog', $af, $av);
	
	// insert the category entry
	assign_category($blog_id,$_POST['category']);
	mysql_close();
	
	// Print confirmation
	print '<p><em>Inserted record</em></p>';
	print '<a href="' . $url . '">Permalink</a></p>';
	
	print '<p><a href="http://iam.solostyle.net/publishfeed.php">Publish feeds!</a></p>';
}

else
	print "<p>Sorry, that function is limited to authenticated users.</p>";


include 'inc/footer.php';
?>

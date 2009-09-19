<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

$blog_id = $_SESSION['entry_id'];

// testing the form
if(isset($_POST['submit_cnt'])) {

	select_db($s, $u, $p, $db);

	// check species
    $verify = trim($verify);
    if ($verify == $_SESSION['verify_string']) echo "You are a human.";
    else {
     	print "<p>Your code was incorrect. Please go back and refresh.</p>";
     	exit();
    }

   // check to see if name has been entered
   if (!$name) print "<p style=\"color:yellow\">Please enter a name.</p>";
   else $n_comm = $name;
   $website = 'http://' . $website;
   $email = $alias . '@' . $domain . '.' . $ext;
   if ($comment) $c_comm = $comment;

	// Email myself the comment
		$to = '1style@gmail.com';
		$subject = "A message from " . $n_comm . ": iam.solostyle.net";

		// message
		$message = '
		<html>
			<head>
				<title>The Message</title>
			</head>
		<body>
			<p>Dear iam.solostyle.net,</p>
			<p>' . $c_comm . '</p>
			<p>Thanks!</p>
			<p>' . $name . '<br />
			<a href="' . $website . '">' . $website . '</a><br />
			<a href="mailto:' . $email . '">' . $email . '</a></p>
			<p>Do not reply to this message.</p>
		</body>
		</html>';
		// Additional headers
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "To: $e_forgot" . "\r\n";
		$headers .= "From: energy <solo@solostyle.net>" . "\r\n";
		$headers .= "Reply-To: energy <solo@solostyle.net>" . "\r\n";

		// Mail it
		mail($to, $subject, $message, $headers);

// if there is no blog_id, then either there was a problem with setting the session entry_id
// or it was just a comment that has no related post
// in either case, the comment gets emailed (see above) and not posted to the db

	if ($blog_id) {
	   if ($n_comm && $c_comm) {
		  $comment_id = create_id($_POST['name'], $_POST['year'], $_POST['month'], $_POST['date']);
		  $comment_id .= '-' . randomkey(8);
		  $acv = array($comment_id, $blog_id, $n_comm, $website, $email, $c_comm, $_POST['time']);
		  $t = 'comments';
		  insert_record($t, $acf, $acv);
		  //publish_comment($acv, 'x');
		  $entryarray = get_entry($blog_id);
		  publish_entry($entryarray, 'w+');
		  mysql_close();
		  print("<h1>Your words were heard!</h1>");
	   }
	   else // one of the data tests failed
		  echo '<p style="color:yellow">There was a server problem. Try again?</p>';
	}
	else {
		  mysql_close();
		  print '<h1>Your words were heard!</h1>';
	}
}


include 'commentform.php';

	include 'inc/footer.php';
?>

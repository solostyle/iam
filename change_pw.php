<?
	ob_start();
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

if(isset($_POST['submit_newpw'])) {

	select_db($s, $u, $p, $db);

   // check to see that all fields are entered
   if (!$u_form) {print "<p style=\"color:yellow\">Please enter a username.</p>";}
   else $u_change = $u_form;
   if (!$p_form) {print "<p style=\"color:yellow\">Please enter the old password.</p>";}
   else $p_change = $p_form;
   if (!$np_form) {print "<p style=\"color:yellow\">Please enter the new password.</p>";}
   else $np_change = $np_form;
   if (!$cnp_form) {print "<p style=\"color:yellow\">Please confirm the new password.</p>";}
   else $cnp_change = $cnp_form;
   if ($cnp_form != $np_form) {print "<p style=\"color:yellow\">The new passwords do not match.</p>";}

   // all fields are entered, now verify
   if ($u_change && $p_change && $np_change && cnp_change) {

      // Match username and old password
   	  $match_query = "SELECT * FROM `users` WHERE `username`='$u_change' AND `password`=MD5('$p_change')";
	  $match = mysql_query($match_query);
	  // If they match, update the password, and send them mail
	  if ($row=mysql_fetch_array($match)) {
	  	$e_change = $row[3];
	  	$update_pw_query = "UPDATE `users` SET `password`=MD5('$np_change') WHERE `username`='$u_change'";
	  	mysql_query($update_pw_query);
		// send mail
			$to = $e_change;
			// subject
			$subject = 'iam.solostyle.net Password';

			// message
			$message = '
			<html>
				<head>
					<title>New Password</title>
				</head>
			<body>
				<p>You or someone on your behalf has requested a password change for <b>' . $u_change . '</b> on <a href="http://iam.solostyle.net">energy.solostyle.net</a>. Your new password is ' . $np_change . '</p>
				<p>To sign in, <a href="http://iam.solostyle.net/login.php">go here</a>.<br />
				To change your password, go to <a href="http://iam.solostyle.net/change_pw.php">this page</a><br />
				If you forget your password, go to <a href="http://iam.solostyle.net/forgot_pw.php">this page</a></p>
				<p>Thanks!</p>
				<p><a href="http://iam.solostyle.net">energy.solostyle.net</a></p>
			</body>
			</html>';

			// Additional headers
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "To: $e_change" . "\r\n";
			$headers .= "From: energy <solo@solostyle.net>" . "\r\n";
			$headers .= "Reply-To: energy <solo@solostyle.net>" . "\r\n";

			// Mail it
			mail($to, $subject, $message, $headers);
			ob_end_clean(); // delete buffer
			header("Location: http://" . $_SERVER['HTTP_HOST']);
			exit();
	  }
	  else print '<p style="color:yellow">The username and password do not match our records.</p>';
   }
}
?>

<h2>Change your password</h2>

<p>Enter your username, your old password, and your new password. An email including a confirmation of this change will be delivered to the address provided at registration.</p>
<form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
	<table>
		<tr><td>Username</td><td><input type="text" size="20" name="u_form" tabindex="1" /></td></tr>
		<tr><td>Old Password</td><td><input type="password" size="20" name="p_form" tabindex="2" /></td></tr>
		<tr><td>New Password</td><td><input type="password" size="20" name="np_form" tabindex="2" /></td></tr>
		<tr><td>Confirm New Password</td><td><input type="password" size="20" name="cnp_form" tabindex="2" /></td></tr>
		<tr><td></td><td><span class="field"><input type="submit" name="submit_newpw" value="Update password" tabindex="2" /></td></tr>
	</table>
</form>

<?
	include 'inc/footer.php';
?>

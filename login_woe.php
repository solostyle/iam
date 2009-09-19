<?
	ob_start();
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';
	include 'left.php';

if(isset($_POST['submit_username'])) {

	select_db($s, $u, $p, $db);

   // check to see that all fields are entered
   if (!$u_form) {print "<p style=\"color:yellow\">Please enter a username.</p>";}
   else $u_forgot = $u_form;

   // all fields are entered, now verify
   if ($u_forgot) {

      // If username exists, get email address
   	  $exist_query = "SELECT * FROM `users` WHERE `username`='$u_forgot'";
	  $exist = mysql_query($exist_query);
	  if ($row=mysql_fetch_array($exist)) {
	  	$e_forgot = $row[3];
      	// create new password
      	$new_pw = gen_pw(8);
		// update database with new password
		$update_pw_query = "UPDATE `users` SET `password`=MD5('$new_pw') WHERE `username`='$u_forgot'";
		mysql_query($update_pw_query);
		// send mail
			$to = $e_forgot;
			// subject
			$subject = 'iam.solostyle.net Password';

			// message
			$message = '
			<html>
				<head>
					<title>New Password</title>
				</head>
			<body>
				<p>You or someone on your behalf has experienced a memory lapse and cannot remember the password for this account. Therefore a new password for <b>' . $u_forgot . '</b> on <a href="http://iam.solostyle.net">energy.solostyle.net</a> has been generated.<br />
				Your new password is ' . $new_pw . '</p>
				<p>This password is probably even more difficult to remember than the previous password that was forgotten. You may want to change it. <br />
				To change your password, go to <a href="http://iam.solostyle.net/change_pw.php">this page</a><br />
				If you forget your password, go to <a href="http://iam.solostyle.net/forgot_pw.php">this page</a><br />
				To sign in, <a href="http://iam.solostyle.net/login.php">go here</a>.</p>
				<p>Thanks!</p>
				<p><a href="http://iam.solostyle.net">energy.solostyle.net</a></p>
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
		ob_end_clean(); // delete buffer
		header("Location: http://" . $_SERVER['HTTP_HOST']);
		exit();
      }
      else print '<p style="color:yellow">That username does not match our records.</p>';
   }
}

if(isset($_POST['submit_email'])) {

	select_db($s, $u, $p, $db);

   // check to see that all fields are entered
   if (!$a_form || !$d_form || !$e_form)
   	print "<p style=\"color:yellow\">Complete the email address.</p>";
   else $e_forgot = $a_form . "@" . $d_form . "." . $e_form;

   // all fields are entered, now verify
   if ($e_forgot) {

		// If email address exists, get username(s) associated and send them off
		$exist_query = "SELECT * FROM `users` WHERE `email`='$e_forgot'";
		$exist = mysql_query($exist_query);

		//collect usernames
		$u_multiple = array();
		while ($row=mysql_fetch_array($exist)) {
			array_push($u_multiple, $row[1]);
		}

		// bad logic, but if $u_multiple is empty, then do not send
		if (count($u_multiple) < 1) {
			print '<p style="color:yellow">That email does not match our records.</p>';
			exit();
		}

		// send them off
		$to = $e_forgot;
		$subject = 'iam.solostyle.net Username';

		// iterate through array
		for ($i = 0; $i < count($u_multiple); $i++) {
			$message_usernames .= "<br />" . $u_multiple[$i];
		}
		$message = '
			<html>
				<head>
					<title>Usernames</title>
				</head>
			<body>
				<p>You or someone on your behalf has requested the usernames registered with <b>' . $e_forgot . '</b> on <a href="http://iam.solostyle.net">energy.solostyle.net</a>.<br />
				The usernames registered with this email address are ' . $message_usernames . '</p>
				<p>To sign in, <a href="http://iam.solostyle.net">go here</a>.<br />
				To change your password, go to <a href="http://iam.solostyle.net/change_pw.php">this page</a><br />
				If you forgot your password, go to <a href="http://iam.solostyle.net/forgot_pw.php">this page</a></p>
				<p>Thanks!</p>
				<p><a href="http://iam.solostyle.net">energy.solostyle.net</a></p>
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
		ob_end_clean(); // delete buffer
		header("Location: http://" . $_SERVER['HTTP_HOST']);
		exit();
   }
}
?>

<div>
<h2>Forgot your password?</h2>
<p>Enter your username, and a new password will be set for you. An email including the details of this change will be delivered to the address provided at registration.</p>

	<form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
	<table>
		<tr><td>Username</td><td><input type="text" size="20" name="u_form" tabindex="1" /></td></tr>
		<tr><td></td><td><input type="submit" name="submit_username" value="Send password" tabindex="2" /></td></tr>
	</table>
	</form>
</div>

<div>
<h2>Forgot your username?</h2>
<p>Enter the email address you provided at registration, and your username will be sent to you.</p>

	<form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
	<table>
		<tr><td>Email</td><td><input type="text" size="4" name="a_form" tabindex="3" />&nbsp;@&nbsp;<input type="text" size="4" name="d_form" tabindex="4" />&nbsp;.&nbsp;<input type="text" size="1" name="e_form" tabindex="5" /></td></tr>
		<tr><td></td><td><input type="submit" name="submit_email" value="Send username" tabindex="6" /></td></tr>
	</table>
	</form>
</div>

<?
	include 'inc/footer.php';
?>

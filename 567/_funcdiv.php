<?php

print '
<div id="func">';

	if (isset($_SESSION['mbr_email']) AND (substr($_SERVER['PHP_SELF'],-10) != 'signout.php'))
		print '<ul>
			<li><a href="http://iam.solostyle.net/members/">members</a></li>
			<li><a href="http://iam.solostyle.net/signout.php">Sign out</a></li>
		</ul>';

	else {

//the craziness begins!
if(isset($_POST['signin'])) {

	select_db($s, $u, $p, $db);

   // check to see if username and password have been entered
   if (!$_POST['email']) {
      echo "enter your email address. \n";
   }
   else $u_login = $_POST['email'];
   if (!$_POST['password']) {
      echo "enter your password. \n";
   }
   else $p_login = $_POST['password'];

   if ($u_login && $p_login) {
      $q = "SELECT `email`, `password`, `last_login` FROM `member_login` WHERE `mbr_nbr`='$u_login' AND `password`= MD5('$p_login')";
      $result = mysql_query($q);

      if ($row=mysql_fetch_array($result)) { // a match was made
         // set session vars
         $_SESSION['mbr_email']=$row[0];
         $_SESSION['last_login']=$row[2];
         // save the time logged in as current_login, and previous current_login as last_login
		 $now  = my_mktime();
		 $now_f = strftime('%G.%m.%d %H:%M',$now);
		 $update_login_times = "UPDATE `member_login` SET `current_login` = '$now_f', `last_login` = '$row[2]' WHERE `email` = '$u_login'";
		 mysql_query($update_login_times);
	 ob_start();
         header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']); 
         ob_end_flush(); // delete buffer
         exit();
      }
      // no match was made
      echo 'user does not exist, or bad password';

   }
   else // one of the data tests failed
      echo 'technical problem. try again.';
}

		print '
		<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
			<ul>
				<li>Email: <input type="text" size="8" name="email" tabindex="1" /> </li>
				<li>Passw: <input type="password" size="7" name="password" tabindex="2" /> </li>
				<li><input type="submit" name="signin" value="Sign in" tabindex="3" /> </li>
			</ul>
		</form>';
//end craziness!
		}



print '</div>';

?>

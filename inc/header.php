<?
$pagetitle='solostyle.net :: iam';
$cssfile='http://iam.solostyle.net/layout.css';
$extra_header = '<link rel="alternate" type="application/rss+xml" title="RSS" href="http://iam.solostyle.net/rss.xml" />
<link rel="service.feed" type="application/atom+xml" title="Atom" href="http://iam.solostyle.net/atom.xml" />';

$markup_head = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>' . $pagetitle . '</title>
<style type="text/css">
@import url("' . $cssfile . '");
@import url("http://iam.solostyle.net/format.css");
</style>
' . $extra_header . '</head>';

$markup_nav = 
'<body>

<div id="page">
	<h1 id="title"><a href="/">meditations</a></h1>

	<div><!-- /begin nav links -->';

		if (isset($_SESSION['user_id']) AND (substr($_SERVER['PHP_SELF'],-10) != 'signout.php'))
			$markup_nav .= '<ul>
				<li><a href="http://iam.solostyle.net/add.php">add entry</a></li>
				<li><a href="http://iam.solostyle.net/modify.php">modify entry</a></li>
				<li><a href="http://iam.solostyle.net/delete.php">delete entry</a></li>
				<li><a href="http://iam.solostyle.net/republish.php">republish entry</a></li>
				<li><a href="http://iam.solostyle.net/publishfeed.php">publish feeds</a></li>
				<li><a href="http://iam.solostyle.net/tag.php">tag</a></li>
				<li><a href="http://iam.solostyle.net/slct-tag.php">see tag</a></li>
				</ul><ul>
				<li><a href="http://iam.solostyle.net/change_pw.php">Change password</a></li>
				<li><a href="http://iam.solostyle.net/login_woe.php">Login woe?</a></li>
				<li><a href="http://iam.solostyle.net/signout.php">Sign out</a></li>
				</ul>';

		else {

//the craziness begins!
if(isset($_POST['signin'])) {

	select_db($s, $u, $p, $db);

   // check to see if username and password have been entered
   if (!$_POST['username']) {
      echo "enter a username. \n";
   }
   else $u_login = $_POST['username'];
   if (!$_POST['password']) {
      echo "enter a password. \n";
   }
   else $p_login = $_POST['password'];

   if ($u_login && $p_login) {
      $q = "SELECT `user_id`, `username`, `last` FROM `users` WHERE `username`='$u_login' AND `password`= MD5('$p_login')";
      $result = mysql_query($q);

      if ($row=mysql_fetch_array($result)) { // a match was made
         // start session
         $_SESSION['user_id']=$row[0];
         $_SESSION['username']=$row[1];
         $_SESSION['last_last']=$row[2];
         // save the time logged in as LAST, and previous last as LAST LAST
		 $now  = my_mktime();
		 $now_f = strftime('%G.%m.%d %H:%M',$now);
		 $update_lasts = "UPDATE `users` SET `last` = '$now_f', `last_last` = '$row[2]' WHERE `username` = '$u_login'";
		 mysql_query($update_lasts);
	 ob_start();
         header("Location: http://" . $_SERVER['HTTP_HOST']); 
         ob_end_flush(); // delete buffer
         exit();
      }
      // no match was made
      echo 'user does not exist, or bad password';

   }
   else // one of the data tests failed
      echo 'technical problem. try again.';
}

			$markup_nav .= '
				<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<ul>
					<li>Name: <input type="text" size="8" name="username" tabindex="1" /> </li>
					<li>Pass: <input type="password" size="7" name="password" tabindex="2" /> </li>
					<li><input type="submit" name="signin" value="Sign in" tabindex="3" /> </li>
				</ul>
				</form>';
//end craziness!
			$markup_nav .= '<ul><li><a href="http://iam.solostyle.net/login_woe.php" tabindex="9">Login woe?</a></li></ul>';
		}



$markup_nav .= '
        </div><!-- /end nav links -->';

$markup_content = '
	<div id="content">';

print $markup_head . $markup_nav . $markup_content;

?>

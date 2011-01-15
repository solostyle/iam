<?php
ob_start(); // keeping in case something is outputted before header() is called
session_start();


if (($_SERVER['REQUEST_URI']) == '/members/log_out') {

    session_unset();
    session_destroy();
    $_SESSION = array();


    // If its desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
    }

    $back = (isset($_SERVER['HTTP_REFERER']))? htmlspecialchars($_SERVER['HTTP_REFERER']) : make_url('');
    header("Location: ".$back);  // won't work after <html>
}

if(isset($_POST['login_submit'])) {

    // check to see if username and password have been entered
    if (!$_POST['username']) echo "enter a username. \n";
    else $u_login = mysql_real_escape_string($_POST['username']);
    if (!$_POST['password']) echo "enter a password. \n";
    else $p_login = mysql_real_escape_string($_POST['password']);
    
    if ($u_login && $p_login) {
        select_db();
        $q = "SELECT `user_id`, `username`, `last` FROM `users` WHERE `username`='$u_login' AND `password`= MD5('$p_login')";
        $result = mysql_query($q);

        if (mysql_num_rows($result)>0) { // a match was made
            // start session
            session_regenerate_id();
            $user=mysql_fetch_assoc($result);
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id']=$user['user_id'];
            $_SESSION['username']=$user['username'];
            $_SESSION['last_last']= $last_last = $user['last'];
            session_write_close();
            // save the time logged in as LAST, and previous last as LAST LAST
            $now  = my_mktime();
            $now_f = strftime('%G.%m.%d %H:%M',$now);
            $update_lasts = "UPDATE `users` SET `last` = '$now_f', `last_last` = '$last_last' WHERE `username` = '$u_login'";
            mysql_query($update_lasts);

            header("Location: http://" . HOST . $_SERVER['REQUEST_URI']);  // won't work after <html>

        } else {
        // no match was made
        echo 'user does not exist, or bad password';
        }

    }
    else // one of the data tests failed
        echo 'technical problem. try again.';
}
?>

<html>
<head>
<title>Test MVC for iam.solostyle.net</title>
<!-- Individual YUI JS files --> 
<?php $html = new HTML();?>
<?php echo $html->includeJs('yui28yahoo');?>
<?php echo $html->includeJs('yui28event');?>
<?php echo $html->includeJs('yui28connection');?>
<?php echo $html->includeJs('yui28dom');?>
<?php echo $html->includeJs('iam');?>
<?php echo $html->includeJs('iam.shell');?>
<?php echo $html->includeJs('iam.blog');?>
<?php echo $html->includeJs('iam.archmenu');?>
<?php echo $html->includeJs('iam.admin');?>
<?php echo $html->includeCss('layout');?>
<?php echo $html->includeCss('format');?>
</head>
<body>
<div id="page">
    <h1 id="pagetitle"><a href="/">meditations</a></h1>
    <div id="login">

        <?php if (isset($_SESSION['logged_in']) AND substr($_SERVER['REQUEST_URI'],-8) != 'log_out'): ?>
            <ul>
            <?php 
                $adminFuncs = array('publish_feeds' => 'publish feeds',
                                'tag_entries' => 'tag entries',
                                'categorize_entries' => 'categorize entries');
                foreach ($adminFuncs as $link => $name) {
                    echo make_list_item(make_link($name, make_url('admin/'.$link)));
                }
            ?>
            </ul>
            <ul>
            <?php
                $loginFuncs = array('change_pw' => 'change password',
                                'login_woe' => 'login woe?',
                                'log_out' => 'log out');
                foreach ($loginFuncs as $link => $name) {
                    echo make_list_item(make_link($name, make_url('members/'.$link)));
                }
            ?>
            </ul>

        <?php else: ?>

            <ul>
                <form action="<?php echo make_url(substr($_SERVER['REQUEST_URI'], 1))?>" method="post">
                <li>Name: <input type="text" size="8" name="username" tabindex="1" /> </li>
                <li>Pass: <input type="password" size="7" name="password" tabindex="2" /> </li>
                <li><input type="submit" name="login_submit" value="Log in" tabindex="3" /> </li>
                <li><a href="http://iam.solostyle.net/members/login_woe" tabindex="9">login woe?</a></li>
                </form>
            </ul>

        <?php endif; ?>

    </div><!-- end div#login -->

    <div id="content"> 
        <div id="left">
            <?php
               include (ROOT.DS. 'app' .DS. 'views' .DS. 'archmenu' .DS. 'index.php');
            ?>

        </div><!-- end #left -->

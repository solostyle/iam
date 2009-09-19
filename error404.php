<?

$page_redirected_from = $_SERVER['REQUEST_URI'];  // this is especially useful with error 404 to indicate the missing page.
$server_url = "http://" . $_SERVER["SERVER_NAME"];
$redirect_url = $_SERVER["REDIRECT_URL"];
$redirect_url_array = parse_url($redirect_url);
$end_of_path = strrchr($redirect_url_array["path"], "/");

# "404 - Not Found"
$error_code = "404 - Not Found";
$explanation = "Oops! The requested resource could not be found on this server.  Verify the address and try again.";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

	<title>Error Code! <?php print ($server_url); ?><?php print ($page_redirected_from); ?></title>

</head>
<body>

<h1><?php print ($error_code); ?></h1>

<p><?PHP echo($explanation); ?></p>

<p>You may also try starting from the home page: <a href="<?php print ($server_url); ?>"><?php print ($server_url); ?></a></p>

<hr />

<p><i>A project of <a href="<?php print ($server_url); ?>"><?php print ($server_url); ?></a>.</i></p>

</body>
</html>

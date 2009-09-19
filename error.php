<?

$page_redirected_from = $_SERVER['REQUEST_URI'];  // this is especially useful with error 404 to indicate the missing page.
$server_url = "http://" . $_SERVER["SERVER_NAME"];
$redirect_url = $_SERVER["REDIRECT_URL"];
$redirect_url_array = parse_url($redirect_url);
$end_of_path = strrchr($redirect_url_array["path"], "/");

switch($_SERVER['REDIRECT_STATUS'])
{
	# "400 - Bad Request"
	case 400:
	$error_code = "400 - Bad Request";
	$explanation = "Yikes! The syntax of the URL submitted by your browser could not be understood.  Verify the address and try again.";
	$redirect_to = "";
	break;

	# "401 - Unauthorized"
	case 401:
	$error_code = "401 - Unauthorized";
	$explanation = "Oops! You may not be allowed to view this section. If you feel you have reached this page in error, return to the login page and try again, or contact the webmaster if you continue to have problems.";
	$redirect_to = "";
	break;

	# "403 - Forbidden"
	case 403:
	$error_code = "403 - Forbidden";
	$explanation = "Oops! You may not be allowed to view this section. If you feel you have reached this page in error, return to the login page and try again, or contact the webmaster if you continue to have problems.";
	$redirect_to = "";
	break;

	# "404 - Not Found"
	case 404:
	$error_code = "404 - Not Found";
	$explanation = "Oops! The requested resource '" . $page_redirected_from . "' could not be found on this server.  Verify the address and try again.";
	break;

	# "500 - Internal Server Error"
	case 500:
	$error_code = "500 - Internal Server Error";
	$explanation = "The server experienced an unexpected error.  Verify the address and try again.";
	$redirect_to = "";
	break;

	# "503 - Service Unavailable"
	case 500:
	$error_code = "503 - Service Unavailable";
	$explanation = "The server is currently unable to handle the request due to a temporary overloading or maintenance of the server. This may be a temporary condition which will be alleviated after some delay.";
	$redirect_to = "";
	break;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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

<?
	$css_file = "http://iam.solostyle.net/fluid-3col-em.css";
	$page_title = "Template File: Fluid, 3-column, em-based layout";

print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<title>' . $page_title . '</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style type="text/css" media="screen, projection">
@import url("' . $css_file . '");
</style>

</head>

';

print '<body>
	
';



<div id="header">
	<h1 style="background-color:#ccc">blog title: iam.solostyle.net</h1>
</div>



<div id="func">
	<a href="#">login</a>
</div>



<div id="center" class="col">
	<h2>blog entry title</h2>
	<div>
		<p>I mean it: I&#8217;ve got your <i xml:lang="de">Neue Haas Grotesk</i> right here, buddy. Along with some lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

		<p>Yeah, you heard me.</p>

		<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

		<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	</div>
</div>



<div id="left" class="col">
	<h3>Related Entries</h3>
	<h3>Tags</h3>
	<h3>[Categories]</h3>
</div>



<div id="right" class="col">
	<h3>The Archives</h3>
	<p>Choose a date range</p>
</div>



<div id="footer">
	<p>copyright something something amen</p>
</div>


</body>
</html>

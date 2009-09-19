<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<title>3 columns, fluid, em-based layout</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style type="text/css" media="screen, projection">
/*	Layout     */
/*
|    |           |    |                                                  |    |                   |    |
|15px|   124px   |15px|                       541px                      |15px|       263px       |15px|
|    |           |    |                                                  |    |                   |    |
|pad | left col  |pad |                     center col                   |pad |     right col     |pad |
|    |           |    |                                                  |    |                   |    |
*/
body {
	margin: 2.5em auto;		/* 40px / 16px = 2.5em */
	padding-left: 9.15625em;	/* 124 + 15 + 7.5 / 16px = 9.15625 */
	padding-right: 17.84375em;	/* 263 + 15 + 7.5 / 16px = 17.84375 */
	max-width: 61.75em;		/* 988px / 16px = 61.75em */
}

#header, #footer {
	/* to expand the header and footer to the full screen width:
	/*margin-left: -9.15625em;	/* body padding-left - (7.5/16) = 8.6875em */
	/*margin-right: -17.84375em;	/* body padding-right */
	padding:0 .46875em;
	margin-right: -9.15625em;
}

#func {
	padding:0 .46875em;
}

.col {
	float:left;
	position:relative;
	padding:0 .46875em;		/* because all columns have 7.5px padding, */
	                                /* there's a total of 15px in the gutters  */
}

#center {
	width:100%;
}

#left {
	width:7.55em;			/* 124 / 16px = 7.75em - .2 for some reason */
	margin-left:-100%;
	right:9.625em;			/* body padding-left + (7.5/16px) = 9.625em */
}

#right {
	width:15.5em;			/* (263 - 15) / 16px = 15.5em 16.4375em */
	margin-right:-100%;
}

h1, h2, h3 {
	font-size:1em;
}

#footer {
	clear:both;
}

#footer p {
	font-size:.8em;
}

</style>

</head>

<body>



<div id="header">
	<h1 style="background-color:#ccc">blog title: iam.solostyle.net</h1>
</div>


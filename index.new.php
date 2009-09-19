<?php

// security first
if(strlen($_SERVER['REQUEST_URI'])>50){
	header("HTTP/1.1 404 Not Found");
	exit;
}

$_SERVER['REQUEST_URI'] = strip_tags($_SERVER['REQUEST_URI']);

//print $_SERVER['REQUEST_URI'];

//0. when this file is requested, go to index.php
if ($_SERVER['REQUEST_URI']=="/index.new.php") {
	//print $_SERVER['REQUEST_URI'] . 'request was for index.new.php';
	include("index.php");
	exit();
}

//1. check if a "real" file exists..

if(file_exists($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'])
	and ($_SERVER['SCRIPT_FILENAME']!=$_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'])
	and ($_SERVER['REQUEST_URI']!="/")) {
		$url=$_SERVER['REQUEST_URI'];
		//print $url . 'request was for real file';
		include($_SERVER['DOCUMENT_ROOT'].$url);
		exit();
}

//2. if not, check for dynamic content.
$url_array=explode("/",$_SERVER['REQUEST_URI']);
array_shift($url_array); //the first one is empty anyway

if(empty($url_array) 
	or ((count($url_array)==1) and (empty($url_array[0]))
	)) { //we got a request for the index
	//print $_SERVER['REQUEST_URI'] . ' request was blank';
	include("index.php"); 
	exit();
}

//Check if anything in the Database matches the request 
//ex: /members/profile, /members/edit, /members
//more: /news, /about, /biographies, /contact
//more: /events, /events/event_name
//more: /admin/events, /admin/events/event_name
//more: /admin/news, 
check_db($url_array);

function check_db($array) {
	$first = $array[0];
	array_shift($array);
	//array should have only one element

	switch ($first) {
	case "members":
		check_db_members($array);
		break;
	case "news":
		check_db_news($array);
		break;
	case "events":
		check_db_events($array);
		break;
	case "admin":
		check_db_admin($array);
		break;
	case "about":
		check_db_about($array);
		break;
	case "biographies":
		check_db_bios($array);
		break;
	case "contact":
		check_db_contact($array);
		break;

//3. nothing in DB either  Error 404!
	default:
		//default sends successful 200 code
		//header("Status: 404");
		header("Location: http://iam.solostyle.net/error404.php");
	}
	exit();
}

function check_db_members($array) {
	// they are requesting member page
	// make sure they are a member
	// check session to see if logged in
	
	//now display the member page
	//if array is empty
	//display the main members page
	//has links to new items since last login
	
	//there could be a trailing slash
	//if so, treat it the same as if
	//there is no trailing slash
	switch ($array[0]) {
	case "view":
		disp_member_view();
		break;
	case "edit":
		disp_member_edit();
		break;
	default:
		#header("Status: 404");
		#header("Location: http://iam.solostyle.net/error404.php");
		header("Location: http://iam.solostyle.net/forms.php");
	}
	exit();
}

function disp_member_view() {
	//use the login information to display
	//use global variables
	//1. retrieve information from table
	//2. format display divs
	//3. return the page
	rtrv_member_info();
	$content = format_member_view();
	return $content;
	include("add.php");
}

function disp_member_edit() {
	include("edit.php");
}


function rtrv_member_info() {
	if (isset($_SESSION['mbr_email'])) {
		$mbr_email = $_SESSION['mbr_email'];
	}
	else
		exit();
	// retrieve names
	// retrieve phone number
	// retrieve address
	// retrieve email
	// retrieve member type, status, payment (amt, date)
	mmbr("rtrv",$mbr_email);
}


?>

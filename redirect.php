<?php

include '235/func.php';
include '235/storevars.php';

function basic_redirect() {


	 // declare variables
	 $root = $_SERVER['DOCUMENT_ROOT'];
	 $redirect_page = 'redirect.php';
	 $home_page = 'index.php';
	 $login_page = 'signin.php';
	 $error_page = 'error404.php';
	 $exec_script = $_SERVER['SCRIPT_FILENAME'];
	 $request_uri = $_SERVER['REQUEST_URI'];
	 $uri = strip_tags($request_uri);
	 $uri_array = explode("/",$uri);
	 array_shift($uri_array);	// first one is empty anyway

	 //echo 'prefix: ' . $uri_array[0] . '<br />';

	// security first
	if(strlen($uri)>50)
		return $error_page;

	// 1. when to go to index.php
	if (	($uri=="/".$redirect_page)	// requested the redirect page
			or
			(	(empty($uri_array))			// requested nothing
				or
				(	(count($uri_array)==1)		// requested something...
					and
					(empty($uri_array[0]))		// ...that is empty
				)
			)
		)
		return $home_page;

	// 2. if it's a "real" file, go there
	if(file_exists($root.$uri)				// if the file exists, and it is
		and ($root.$uri!=$exec_script)			// not the executing script
		and ($uri!="/"))				// and it is not empty
		return $root.$uri;

	return 0;

} // basic redirect

//if begins with admin or members, do those functions
//check for info or profile or other static pages
//check for tag
//check for date
//else, show error page

function advanced_redirect() {

	 // declare variables
	 $root = $_SERVER['DOCUMENT_ROOT'];
	 $redirect_page = 'redirect.php';
	 $home_page = 'index.php';
	 $login_page = 'signin.php';
	 $error_page = 'error404.php';
	 $exec_script = $_SERVER['SCRIPT_FILENAME'];
	 $request_uri = $_SERVER['REQUEST_URI'];
	 $uri = strip_tags($request_uri);
	 $uri_array = explode("/",$uri);
	 array_shift($uri_array);	// first one is empty anyway

	 switch (true) {
	 	case ($uri_array[0] == "admin"):
		     //do something
		     break;
		case ($uri_array[0] == "members"):
		     //do something
		     break;
		case ($uri_array[0] == "news"):
		     //do something
		     break;
		case ($uri_array[0] == "contact"):
		     //do something
		     break;
		case ($uri_array[0] == "tag"):
		     handle_url_tag($uri_array);
		     break;
		case (substr($uri_array[0], 0, 1) == "2"):
		     handle_url_date($uri_array);
		default:
			return $error_page;
	}
}

function handle_url_tag() {
}

function handle_url_date($arr) {

	 // for each segment in the array, add it to the regular expression
	 $id_str = implode("/", $arr);

	 // get the array of entries
	 $s = "mysql.solostyle.net";
	 $u = "solostyle";
	 $p = 'qas??wed';
	 $db = "iam";
	 
	 select_db($s, $u, $p, $db);
	 $entries_arr = rtrv_entries($id_str);

	 // send it to the guy who can display them
	 // if (count($entries_arr) > 3) show preview, else full
	 // this should construct the whole page and print it to the screen
	 $entries_markup = show_entries($entries_arr);

	 mysql_close();

	 // display the page
	 display_page($entries_markup);

	 //print 
"<?
	session_start();
	include_once '235/func.php';
	include_once '235/storevars.php';
	include 'inc/header.php';

	// load the left pane
        include 'left.php';
?>";

	//print $entries_markup;

	//print 
"
	include 'inc/footer.php';
?>";


}

$result = basic_redirect();
//echo 'bas rdr: ' . $result . '<br />';

if($result)
	include($result);
else
	advanced_redirect($result);


// urls to support
//http://iam.solostyle.net/

//http://iam.solostyle.net/admin/add_entry
//http://iam.solostyle.net/admin/modify_entry
//http://iam.solostyle.net/admin/delete_entry
//http://iam.solostyle.net/admin/publish_feeds
//http://iam.solostyle.net/admin/tag
//http://iam.solostyle.net/admin/categorize (maybe not needed)

//http://iam.solostyle.net/members/news (new entries since last login)
//http://iam.solostyle.net/members/profile (maybe not needed)

//http://iam.solostyle.net/news (maybe this is all that is needed)

//http://iam.solostyle.net/contact

//(num_entries_to_displ > x)?preview:full
//http://iam.solostyle.net/2008/ (all entries for this year preview)
//http://iam.solostyle.net/2008/09 (all entries in september 2008)
//http://iam.solostyle.net/2008/09/16/ (all entries in 16 sept 08)
//http://iam.solostyle.net/2008/09/16/blog-entry (this particular entry)

//http://iam.solostyle.net/tag/spirituality/ (all entries tagged with spirituality)
//http://iam.solostyle.net/tag/spirituality|health/ (all entries tagged with either)
//http://iam.solostyle.net/tag/spirituality&health/ (all entries tagged with both)
//would it be difficult to offer such functionality to users?

?>
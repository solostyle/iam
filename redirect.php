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
		     handle_admin($uri_array);
		     break;
		case ($uri_array[0] == "members"):
		     // menu
		     // may not be necessary
		     break;
		case ($uri_array[0] == "news"):
		     // show updates since last login
		     // if not logged in,
		     // show the latest stuff
		     // maybe news IS the home page
		     break;
		case ($uri_array[0] == "contact"):
		     handle_contact();
		     break;
		case ($uri_array[0] == "articles"):
		     handle_article($uri_array);
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


// Handle the request for admin functions
// allow admin functions
// 27 sep 09: created
function handle_admin($arr) {

	if (!logged_in()) {
	   return $error_page;
	} else {
	   //handle each admin function differently
	   $func = "admin_func_" . $arr[1];
	   // first make sure it is a function
	   $func();
	}
}


// Handle the request for contact information
// if there is anything after the /contact/ part of the url
// show contact information and rewrite the url
function handle_contact() {

	 // don't need to connect to database,
	 // just store the markup somewhere
	 $contact_json = json_contact();

	 //return json 
	 return $contact_json;
}


// Handles the request for an article
// right now these are links actually stored in the directory
// 27 sep 09: created
function handle_article($arr) {

	 $article_name = $arr[1];

	 // retrieve this article
	 select_db($GLOBALS["s"], $GLOBALS["u"], $GLOBALS["p"], $GLOBALS["db"]);
	 $article = rtrv_article($article_name);
	 mysql_close();

	 $article_json = json_writ($article);
	 return $article_json;
}


// Handles the url if it requests entries by tag
// the tags are separated by & or |
// right now, it does not handle both
// 20 sep 09: created
function handle_url_tag($arr) {

	 $tag_str = $arr[1];

	 $tag_arr = array();

	 // entries that have any of the tags
	 switch (true) {
	 	case (strpos($tag_str,"|")):
		     $method = "or";
		     $tag_arr = explode("|",$tag_str);
		     break;
	 // entries that have all the given tags
	    	case (strpos($tag_str,"&")):
		     $method = "and";
		     $tag_arr = explode("&",$tag_str);
		     break;
		default:
			$method = '';
			$tag_arr[0] = $tag_str;
	 }

	 // get the array of entries
	 select_db($GLOBALS["s"], $GLOBALS["u"], $GLOBALS["p"], $GLOBALS["db"]);
	 $entries_arr = rtrv_entries_by_tag($tag_arr, $method);
	 mysql_close();

	 $entries_json = json_writ($entries_arr);

	 return $entries_json;
}

function handle_url_date($arr) {

	 // for each segment in the array, add it to the regular expression
	 $id_str = implode("/", $arr);

	 // get the array of entries
	 select_db($GLOBALS["s"], $GLOBALS["u"], $GLOBALS["p"], $GLOBALS["db"]);
	 $entries_arr = rtrv_entries($id_str);
	 mysql_close();

	 $entries_json = json_writ($entries_arr);

	 return $entries_json;
}

$result = basic_redirect();
//echo 'bas rdr: ' . $result . '<br />';

if($result)
	include($result);
else
	advanced_redirect();


// REMEMBER!!!
// get rid of display_page(), instead return json
// have a render.js that creates the markup

// urls to support
//http://iam.solostyle.net/ =  /news?

//http://iam.solostyle.net/admin/add_entry
//http://iam.solostyle.net/admin/modify_entry
//http://iam.solostyle.net/admin/delete_entry
//http://iam.solostyle.net/admin/publish_feeds
//http://iam.solostyle.net/admin/tag
//http://iam.solostyle.net/admin/categorize (maybe not needed)

//http://iam.solostyle.net/members/news same as /news?
//http://iam.solostyle.net/members/profile (maybe not needed)

//http://iam.solostyle.net/contact

//http://iam.solostyle.net/articles/Fastest-Way-to-Meditation-Success
//http://iam.solostyle.net/articles/tag/meditation/
// should articles have tags?
// or should they have categories?
// articles = professional writing
// blog posts = personal writing (diary)

// it would be sweet if after you logged in, all the functions would appear in the
// web parts where they apply

// main layout: space for static articles, other things
// blog layout: just a left bar for navigation, main content, right pane for post metadata

// where should articles go?
// maybe their own table
// what does the json content look like?
// article = {
// 	   markup : "text from the database",
//	   container: "main",
//	   date_written: "timestamp",
//	   author: "solostyle",
//	   related_articles: [],
//	   category: ""
//	 }

?>
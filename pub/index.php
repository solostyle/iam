<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('HOST', $_SERVER['HTTP_HOST']);

$url = $_GET['url'];

require_once (ROOT . DS . 'lib' . DS . 'config.php');
require_once (ROOT . DS . 'lib' . DS . 'routing.php');
require_once (ROOT . DS . 'lib' . DS . 'inflection.php');
require_once (ROOT . DS . 'lib' . DS . 'shared.php');
// end of file here

// include (ROOT . DS . '235' . DS . 'func.php');
// 
// function basic_redirect() {
// 
// 
// 	 // declare variables
// 	 $root = $_SERVER['DOCUMENT_ROOT'];
// 	 $redirect_page = 'redirect.php';
// 	 $home_page = 'index.php';
// 	 $login_page = 'signin.php';
// 	 $error_page = 'error404.php';
// 	 $exec_script = $_SERVER['SCRIPT_FILENAME'];
// 	 $request_uri = $_SERVER['REQUEST_URI'];
// 	 $uri = strip_tags($request_uri);
// 	 $uri_array = explode("/",$uri);
// 	 array_shift($uri_array);	// first one is empty anyway
// 
// 	 //echo 'prefix: ' . $uri_array[0] . '<br />';
// 
// 	// security first
// 	if(strlen($uri)>50)
// 		return $error_page;
// 
// 	// 1. when to go to index.php
// 	if (	($uri=="/".$redirect_page)	// requested the redirect page
// 			or
// 			(	(empty($uri_array))			// requested nothing
// 				or
// 				(	(count($uri_array)==1)		// requested something...
// 					and
// 					(empty($uri_array[0]))		// ...that is empty
// 				)
// 			)
// 		)
// 		return $home_page;
// 
// 	// 2. if it's a "real" file, go there
// 	if(file_exists($root.$uri)				// if the file exists, and it is
// 		and ($root.$uri!=$exec_script)			// not the executing script
// 		and ($uri!="/"))				// and it is not empty
// 		return $root.$uri;
// 
// 	return 0;
// 
// } // basic redirect
// 
// //if begins with admin or members, do those functions
// //check for info or profile or other static pages
// //check for tag
// //check for date
// //else, show error page
// 
// function advanced_redirect() {
// 
// 	 // declare variables
// 	 $root = $_SERVER['DOCUMENT_ROOT'];
// 	 $redirect_page = 'redirect.php';
// 	 $home_page = 'index.php';
// 	 $login_page = 'signin.php';
// 	 $error_page = 'error404.php';
// 	 $exec_script = $_SERVER['SCRIPT_FILENAME'];
// 	 $request_uri = $_SERVER['REQUEST_URI'];
// 	 $uri = strip_tags($request_uri);
// 	 $uri_array = explode("/",$uri);
// 	 array_shift($uri_array);	// first one is empty anyway
// 
// 	 switch (true) {
// 	 	case ($uri_array[0] == "admin"):
// 		     //do something
// 		     break;
// 		case ($uri_array[0] == "members"):
// 		     //do something
// 		     break;
// 		case ($uri_array[0] == "news"):
// 		     //do something
// 		     break;
// 		case ($uri_array[0] == "contact"):
// 		     //do something
// 		     break;
// 		case ($uri_array[0] == "articles"):
// 		     handle_articles($uri_array);
// 		     break;
// 		case ($uri_array[0] == "tag"):
// 		     handle_url_tag($uri_array);
// 		     break;
// 		case (substr($uri_array[0], 0, 1) == "2"):
// 		     handle_url_date($uri_array);
// 		default:
// 			return $error_page;
// 	}
// }
// 
// // Handles the request for an article
// // right now these are links actually stored in the directory
// // may want to integrate them with blog?
// // 24 sep 09: created
// function handle_articles($arr) {
// 
// 	 // for each segment in the array, add it to the regular expression
// 	 $id_str = implode("/", $arr);
// 
// 	 // get the array of entries
// 	 select_db($GLOBALS["s"], $GLOBALS["u"], $GLOBALS["p"], $GLOBALS["db"]);
// 	 $entries_arr = rtrv_entries($id_str);
// 
// 	 // send it to the guy who can display them
// 	 // if (count($entries_arr) > 3) show preview, else full
// 	 // this should construct the whole page and print it to the screen
// 	 $entries_markup = show_entries($entries_arr);
// 
// 	 mysql_close();
// 
// 	 // display the page
// 	 display_page($article_markup);
// }
// 
// 
// // Handles the url if it requests entries by tag
// // the tags are separated by & or |
// // right now, it does not handle both
// // 20 sep 09: created
// function handle_url_tag($arr) {
// 
// 	 $tag_str = $arr[1];
// 
// 	 $tag_arr = array();
// 
// 	 // entries that have any of the tags
// 	 switch (true) {
// 	 	case (strpos($tag_str,"|")):
// 		     $method = "or";
// 		     $tag_arr = explode("|",$tag_str);
// 		     break;
// 	 // entries that have all the given tags
// 	    	case (strpos($tag_str,"&")):
// 		     $method = "and";
// 		     $tag_arr = explode("&",$tag_str);
// 		     break;
// 		default:
// 			$method = '';
// 			$tag_arr[0] = $tag_str;
// 	 }
// 
// 	 // get the array of entries
// 	 select_db($GLOBALS["s"], $GLOBALS["u"], $GLOBALS["p"], $GLOBALS["db"]);
// 	 $entries_arr = rtrv_entries_by_tag($tag_arr, $method);
// 
// 	 $entries_markup = show_entries($entries_arr);
// 
// 	 mysql_close();
// 
// 	 // display the page
// 	 display_page($entries_markup);
// }
// 
// function handle_url_date($arr) {
// 
// 	 // for each segment in the array, add it to the regular expression
// 	 $id_str = implode("/", $arr);
// 
// 	 // get the array of entries
// 	 select_db($GLOBALS["s"], $GLOBALS["u"], $GLOBALS["p"], $GLOBALS["db"]);
// 	 $entries_arr = rtrv_entries($id_str);
// 
// 	 // send it to the guy who can display them
// 	 // if (count($entries_arr) > 3) show preview, else full
// 	 // this should construct the whole page and print it to the screen
// 	 $entries_markup = show_entries($entries_arr);
// 
// 	 mysql_close();
// 
// 	 // display the page
// 	 display_page($entries_markup);
// }
// 
// $result = basic_redirect();
// //echo 'bas rdr: ' . $result . '<br />';
// 
// if($result)
// 	include($result);
// else
// 	advanced_redirect($result);


// REMEMBER!!!
// get rid of display_page(), instead return json
// have a render.js that creates the markup

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

//http://iam.solostyle.net/articles/Fastest-Way-to-Meditation-Success
//http://iam.solostyle.net/articles/tag/meditation/
// should articles have tags?
// or should they have categories?
// or should they exist as blog posts?
// it seems like they are a separate section
// right now i want them to remain separate.
// articles = professional writing
// blog posts = personal writing (diary)

//http://iam.solostyle.net/2008/ (all entries for this year preview)
//http://iam.solostyle.net/2008/09 (all entries in september 2008)
//http://iam.solostyle.net/2008/09/16/ (all entries in 16 sept 08)
//http://iam.solostyle.net/2008/09/16/blog-entry (this particular entry)

//http://iam.solostyle.net/tag/spirituality/ (all entries tagged with spirituality)
//http://iam.solostyle.net/tag/spirituality|health/ (all entries tagged with either)
//http://iam.solostyle.net/tag/spirituality&health/ (all entries tagged with both)
//would it be difficult to offer such functionality to users?

?>
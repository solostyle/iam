<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('HOST', $_SERVER['HTTP_HOST']);

$url = $_GET['url'];
//echo 'url is ' . $url;

require_once (ROOT . DS . 'lib' . DS . 'config.php');
require_once (ROOT . DS . 'lib' . DS . 'routing.php');
require_once (ROOT . DS . 'lib' . DS . 'inflection.php');
//require_once (ROOT . DS . 'lib' . DS . 'shared.php');
// end of file here

include (ROOT . DS . '235' . DS . 'func.php');

/** Check if environment is development and display errors **/
function SetReporting() {
    if (DEVELOPMENT_ENVIRONMENT == true) {
        error_reporting(E_ALL);
        ini_set('display_errors','On');
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors','Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
    }
}

/** Check for Magic Quotes and remove them **/
function stripSlashesDeep($value) {
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

function RemoveMagicQuotes() {
    if ( get_magic_quotes_gpc() ) {
        $_GET    = stripSlashesDeep($_GET   );
        $_POST   = stripSlashesDeep($_POST  );
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

/** Check register globals and remove them **/
function UnregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Routing **/
function routeURL($url) {
  global $routing;

  foreach ( $routing as $pattern => $result ) {
        if ( preg_match( $pattern, $url ) ) {
            return preg_replace( $pattern, $result, $url );
        }
  }

  return ($url);
}

function DetermineRequest() {
    // declare variables
    global $url;
    global $default;
    $error_page = ROOT . DS . 'error404.php';
    $url_page = ROOT . DS . $url;
    $queryString = array();

//echo 'prefix: ' . $uri_array[0] . '<br />';

    if (!isset($url)) {
        $controller = $default['controller'];
        $action = $default['action'];
    }
    elseif (file_exists($url_page)) {
        include $url_page;
        exit;
    }
    else {

        //if begins with admin or members, do those functions
        //check for info or profile or other static pages
        //check for tag
        //check for date
        //else, show error page

        $url = routeURL($url);
        $urlArray = array();
        $urlArray = explode("/",$url);

        // If the url is a blog_id, handle specially
        if (is_numeric($urlArray[0])) {
            $controller = 'ids';
            $action = 'index';
            $queryString = $url;
        } else {
            $controller = $urlArray[0];
            array_shift($urlArray);

            // tags action is always index
            if ($controller == 'tags') {
                $action = 'index';
                $queryString = $urlArray;
            }

            if (isset($urlArray[0])) {
                $action = $urlArray[0];
                array_shift($urlArray);
                $queryString = $urlArray;
            } else {
                $action = 'index'; // Default Action
            }
        }
//         switch (true) {
//             case ($controller == "admin"):
//                 //do something
//                 break;
//             case ($controller == "members"):
//                 //do something
//                 break;
//             case ($controller == "news"):
//                 //do something
//                 break;
//             case ($controller == "contact"):
//                 //do something
//                 break;
//             case ($controller == "articles"):
//                 //handle_articles($urlArray);
//                 break;
//             case ($controller == "tag"):
//                 //handle_url_tag($urlArray);
//                 break;
//             case (is_numeric($controller)): // such as the year
//                 //handle_articles($urlArray);
//             default:
//                 return $error_page;
//         }
    }

    $controllerName = ucfirst($controller) . 'Controller';

    $dispatch = new $controllerName($controller,$action);
  
    if ((int)method_exists($controllerName, $action)) {
        call_user_func_array(array($dispatch,$action),$queryString);
    } else {
        /* The controller does not have the action specified */
        /* Here we decouple controller and action and look up the URL in the routing table */
        /* look up the url in the routing table */
        /* if it exists, use the controller and action specified */
    }
}


/** Autoload any classes that are required **/
function __autoload($className) {
    if (file_exists(ROOT . DS . 'lib' . DS . strtolower($className) . '.class.php')) {
        require_once(ROOT . DS . 'lib' . DS . strtolower($className) . '.class.php');
    } else if (file_exists(ROOT . DS . 'app' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'app' . DS . 'controllers' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'app' . DS . 'models' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'app' . DS . 'models' . DS . strtolower($className) . '.php');
    } else {
        /* Error Generation Code Here */
        echo 'error: class ' . $className . ' not found';
    }
}

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

$inflect =& new Inflection();
SetReporting();
RemoveMagicQuotes();
UnregisterGlobals();
DetermineRequest();

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

// done implementing:
//http://iam.solostyle.net/2008/ (all entries for this year preview)
//http://iam.solostyle.net/2008/09 (all entries in september 2008)
//http://iam.solostyle.net/2008/09/16/ (all entries in 16 sept 08)
//http://iam.solostyle.net/2008/09/16/blog-entry (this particular entry)

//http://iam.solostyle.net/tag/spirituality/ (all entries tagged with spirituality)
//http://iam.solostyle.net/tag/spirituality|health/ (all entries tagged with either)
//http://iam.solostyle.net/tag/spirituality&health/ (all entries tagged with both)
//give users an easy way of selecting these tags

?>
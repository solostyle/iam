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
// end of file could be here

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

        $url = routeURL($url);
        $urlArray = array();
        $urlArray = explode("/",$url);

        
        $controller = $urlArray[0];
        array_shift($urlArray);

        if (isset($urlArray[0])) {
            $action = $urlArray[0];
            array_shift($urlArray);
            $queryString = $urlArray;
        } else {
            $action = 'index'; // Default Action
        }
//             case ($controller == "admin"):
//             case ($controller == "members"):
//             case ($controller == "news"):
//             case ($controller == "contact"):
//             case ($controller == "articles"):
//                 //handle_articles($urlArray);
    }

    $controllerName = ucfirst($controller) . 'Controller';

    $dispatch = new $controllerName($controller,$action);
    //echo $controller . ' is the controller, ' . $action . ' is the action, ';
    //print_r($queryString);
    //echo ' is the query string';
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
// 	 select_db();
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

$inflect =& new Inflection();
SetReporting();
RemoveMagicQuotes();
UnregisterGlobals();
DetermineRequest();

// REMEMBER!!!
// get rid of display_page(), instead return json
// have a render.js that creates the markup from json

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
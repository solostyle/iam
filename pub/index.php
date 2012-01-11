<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('HOST', $_SERVER['HTTP_HOST']);

require_once (ROOT . DS . 'lib' . DS . 'config.php');
require_once (ROOT . DS . 'lib' . DS . 'routing.php');
require_once (ROOT . DS . 'lib' . DS . 'inflection.php');
//require_once (ROOT . DS . 'lib' . DS . 'shared.php');
// end of file could be here

include (ROOT . DS . '235' . DS . 'func.php');

/** Secondary Call Function **/
function performAction($controller,$action,$queryString = null,$render = 0) {
  
  $controllerName = ucfirst($controller).'Controller';
  $dispatch = new $controllerName($controller,$action);
  $dispatch->render = $render;
  return call_user_func(array($dispatch,$action),$queryString);
}


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

function DetermineRequest() {
    // declare variables
    global $url;
    global $default;
    $error_page = ROOT . DS . 'error404.php';
    
    $queryString = array();

    $url = routeURL($url);
    $url_page = ROOT . DS . 'pub' . DS . $url;

    if (!isset($url)) {
        $controller = $default['controller'];
        $action = $default['action'];
		$queryString = $default['queryString'];
    }
    elseif (file_exists($url_page)) {
        include $url_page;
        exit;
    }
    else {

        //$url = routeURL($url);
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
    }

    $controllerName = ucfirst($controller) . 'Controller';

    $dispatch = new $controllerName($controller,$action);
    //echo $controller . ' is the controller, ' . $action . ' is the action, ';
    //print_r($queryString);
    //echo ' is the query string';
    if ((int)method_exists($controllerName, $action)) {
        call_user_func(array($dispatch,$action),$queryString);
    } else {
        /* The controller does not have the action specified */
        /* Here we decouple controller and action and look up the URL in the routing table */
        /* look up the url in the routing table */
        /* if it exists, use the controller and action specified */
    }
}

$url = $_GET['url'];
//echo 'url is ' . $url;

$inflect =& new Inflection();
$cache =& new Cache();
SetReporting();
RemoveMagicQuotes();
UnregisterGlobals();
DetermineRequest();

?>
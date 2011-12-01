<?php

/*
 * The setReporting() function helps us display errors 
 * only when the DEVELOPMENT_ENVIRONMENT is true. 
 * The next move is to remove global variables and magic quotes. 
 * Another function that we make use of is __autoload 
 * which helps us load our classes automagically. 
 * Finally, we execute the callHook() function which does the main processing.
 * 
 * Each of our URLs - site.com/controllerName/actionName/queryString
 * 
 * So callHook() basically takes the URL which we have 
 * received from index.php and separates it out as 
 * $controller, $action and the remaining as $queryString. 
 * $model is the singular version of $controller.
 * 
 * e.g. if our URL is todo.com/items/delete/1/first-item, then
 *   Controller is items
 *   Model is item (corresponding mysql table)
 *   View is delete
 *   Action is delete
 *   Query String is an array (1,first-item)
 * 
 * After the separation is done, it creates a new object 
 * of the class $controller.”Controller” 
 * and calls the method $action of the class.
 */

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

/** Secondary Call Function **/
function performAction($controller,$action,$queryString = null,$render = 0) {
  
  $controllerName = ucfirst($controller).'Controller';
  $dispatch = new $controllerName($controller,$action);
  $dispatch->render = $render;
  return call_user_func_array(array($dispatch,$action),$queryString);
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

/** Main Call Function **/
function DetermineRequest() {
  global $url;
  global $default;

  $queryString = array();

  // Determine the controller and the action
  if (!isset($url)) {
        // Go to the home page
    $controller = $default['controller'];
    $action = $default['action'];
  } else {
    $url = routeURL($url);
    $urlArray = array();
    $urlArray = explode("/",$url);
    $controller = $urlArray[0]; // Save off the controller

    array_shift($urlArray);
    if (isset($urlArray[0])) {
        if (is_numeric($urlArray[0])) {
            $action = 'view';
        } else {
            $action = $urlArray[0];
            array_shift($urlArray);
        }
        $queryString = $urlArray;
    } else {
        $action = 'index'; // Default Action
    }
  }
 
  $controllerName = ucfirst($controller) . 'Controller';

    /* __autoload() checks that $controllerName exists
     * If it doesn't exist, echoes error */
  $dispatch = new $controllerName($controller,$action);
  
  // Run the controller and action
  if ((int)method_exists($controllerName, $action)) {
        if (method_exists($dispatch,"beforeAction")) {
            call_user_func_array(array($dispatch,"beforeAction"), $queryString);
        }
		call_user_func_array(array($dispatch,$action),$queryString);
        if (method_exists($dispatch,"afterAction")) {
            call_user_func_array(array($dispatch,"afterAction"),$queryString);
        }
  } else {
        /* The controller does not have the action specified */
        /* Here we decouple controller and action and look up the URL in the routing table */
        /* look up the url in the routing table */
        /* if it exists, use the controller and action specified */
  }
    /* if there's no controller/action or a routing table match, use default, or throw 404 error */
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

/** Disabled because not working on current server **/
/** GZip Output **/
function gzipOutput() {
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ')
        || false !== strpos($ua, 'Opera')) {
        return false;
    }

    $version = (float)substr($ua, 30); 
    return (
            $version < 6
            || ($version == 6  && false === strpos($ua, 'SV1'))
            );
}

/** Get Required Files **/
//gzipOutput() || ob_start("ob_gzhandler");


$cache =& new Cache();
$inflect =& new Inflection();

SetReporting();
RemoveMagicQuotes();
UnregisterGlobals();
DetermineRequest();
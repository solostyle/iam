<?php

$routing = array(
                 '/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1_\2/\3'
                 /* keep these separated by slashes */
                 /* '/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1/\2/\3' */
                 );

/* If the root domain name is requested
 * e.g. test.solostyle.net/
 * then this is where they will be directed
 */
//$default['controller'] = 'comments';
$default['controller'] = 'shells';
$default['action'] = 'index';
<?php

$routing = array(
                '/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1_\2/\3',
                /* keep these separated by slashes */
                /* '/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1/\2/\3'
                */
                '/^([0-9]{4}\/.*)/' => 'ids/index/\1',
                '/^tags\/(.*?)/' => 'tags/index/\1',
                '/^categories\/(.*?)/' => 'categories/index/\1',
                '/^about/' => 'about.php',
                 );

/* If the root domain name is requested
 * e.g. test.solostyle.net/
 * then this is where they will be directed
 */
//$default['controller'] = 'comments';
$default['controller'] = 'shells';
$default['action'] = 'index';
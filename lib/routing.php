<?php

$routing = array(
                '/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1_\2/\3',
                /* keep these separated by slashes */
                /* '/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1/\2/\3'
                */
                '/^([0-9]{4}\/.*)/' => 'blog/id/0/\1',
				'/^tag\/(.*?)/' => 'tags/view_by_tag/0/\1',
                '/^category\/(.*?)/' => 'blog/category/0/\1',
                '/^about/' => 'passives/about',
                 );

/* If the root domain name is requested
 * e.g. test.solostyle.net/
 * then this is where they will be directed
 */
$default['controller'] = 'blog';
//$default['controller'] = 'shells';
$default['action'] = 'index';
$default['queryString'] = array('0'); // do render the header
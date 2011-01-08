<?php 
    require_once (ROOT . DS . '235' . DS . 'presentfunc.php');
    require_once (ROOT . DS . '235' . DS . 'dbfunc.php');
?>

<?php
    $offset = 60 * 60 * 24 * 3;
    $CacheControlStr = 'Cache-Control: max-age=' . $offset;
    header($CacheControlStr);
    //header('HTTP/1.1 304 Not Modified'); //prints "request failure:"
?>

<h3>archives</h3>

<div>

<?php
    select_db($GLOBALS["s"], $GLOBALS["u"], $GLOBALS["p"], $GLOBALS["db"]);

    $archive_nav_array = create_archive_nav_array();
    $archive_nav_menu = create_archive_nav_menu($archive_nav_array);

    echo $archive_nav_menu;
?>

</div>

<?php mysql_close(); ?>
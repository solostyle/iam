<?php 
    require_once (ROOT . DS . '235' . DS . 'presentfunc.php');
    require_once (ROOT . DS . '235' . DS . 'dbfunc.php');
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
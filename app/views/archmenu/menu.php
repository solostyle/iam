<?php
    select_db();

    echo json_encode(create_archive_nav_array());

    mysql_close();
?>
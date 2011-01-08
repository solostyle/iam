<div id="content"> 
    <div id="left">
        <h3>archives</h3>
        <?php
            select_db(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $archive_nav_array = create_archive_nav_array();
            $archive_nav_menu = create_archive_nav_menu($archive_nav_array);
            mysql_close();
        ?>
        <div><?php print $archive_nav_menu?></div>
    </div>
    <div id="blog">
        <?php 
            select_db(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            print implode('', array_map("make_entry", rtrv_entries('', 3)));
            mysql_close();
        ?>
    </div>
</div>

<!--<script type="text/javascript">
this.Iam.Shell.LoadWebParts();
</script>-->
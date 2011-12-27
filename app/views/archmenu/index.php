<h3>Archives</h3>

<div id="archmenuWP">

<?php
    select_db();

    $archive_nav_array = create_archive_nav_array();
    $archive_nav_menu = create_archive_nav_menu($archive_nav_array);
    echo $archive_nav_menu;

    mysql_close();
?>

</div>

<script type="text/javascript">
Iam.Objects.ArchNavMenu = <?php echo json_encode($archive_nav_array); ?>;
</script>

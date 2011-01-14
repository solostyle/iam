<div id="right"> 
    <div id="blogEntries">
        <?php 
            select_db();
            print implode('', array_map("make_entry", rtrv_entries_by_tag($tags_arr, $method)));
            mysql_close();
        ?>
    </div>
</div>

<!--<script type="text/javascript">
this.Iam.Shell.LoadWebParts();
</script>-->
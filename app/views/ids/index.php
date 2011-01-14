<div id="right"> 
    <div id="blogEntries">
        <?php 
            select_db();
            print implode('', array_map("make_entry", rtrv_entries($blog_id)));
            mysql_close();
        ?>
    </div><!-- end #blogEntries -->
</div><!-- end #right -->

<!--<script type="text/javascript">
this.Iam.Shell.LoadWebParts();
</script>-->
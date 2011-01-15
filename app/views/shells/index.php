    <div id="blogEntries">
        <?php 
            select_db();
            print implode('', array_map("make_entry", rtrv_entries('', 3)));
            mysql_close();
        ?>
    </div><!-- end #blogEntries -->
</div><!-- end #right -->

<script type="text/javascript">
this.Iam.Shell.LoadWebParts();
</script>
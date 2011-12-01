<div id="blogEntries">

<?php select_db(); ?>
<?php foreach ($blog as $entry):?>

<?php 
    require_once (ROOT . DS . '235' . DS . 'presentfunc.php');
    $e = stripslashes($entry['Entry']['entry']);
    $e = nl2p_or_br($e);
    $ttl = stripslashes($entry['Entry']['title']);
    $l = make_url($entry['Entry']['id']);
    $date = parse_date($entry['Entry']['time']);
    $time = parse_time($entry['Entry']['time']);
    $tags = show_tags($entry['Entry']['id']);
		$cat = get_category($entry['Entry']['id']);
		$c = make_link($cat, make_url('categories/' . $cat));
?>
    <div class="entry" id="entry_<?php echo $entry['Entry']['id']?>">
        <div class="main">
        
            <!--allow editing of title only if logged in-->
            <?php if (isset($_SESSION['logged_in'])):?>
                <div class="entryEditButton" id="editTitle_<?php echo $entry['Entry']['id']?>">Edit</div>
            <?php endif; ?>
            
            <h2 id="entryTitle_<?php echo $entry['Entry']['id']?>"><?php echo $ttl?></h2>

            <!--allow editing of entry only if logged in-->
            <?php if (isset($_SESSION['logged_in'])):?>
                <div class="entryEditButton" id="editEntry_<?php echo $entry['Entry']['id']?>">Edit</div>
            <?php endif; ?>

            <div id="entryEntry_<?php echo $entry['Entry']['id']?>"><?php echo $e?></div>
            
            <!-- <p>
             <em><a name="bot" href="http://iam.solostyle.net/comment.php">comment</a></em>
            </p> -->
        </div><!-- end .main -->
        <div class="info">
            <p>Posted on <?php echo $date . ' at ' . $time?></p>
            <!--<p><a href="#">0 comments</a> so far</p>-->
            <?php if ($tags!=''):?>
                <p>Tagged with <?php echo $tags?></p>
            <?php endif; ?>
            <p><a href="<?php echo $l?>">Permalink</a></p>
						<p>Categorized under <?php echo $c?></p>

            <?php if (isset($_SESSION['logged_in'])):?>
                <p><a id="deleteEntry_<?php echo $entry['Entry']['id']?>">Delete</a></p>
            <?php endif; ?>
        </div><!-- end .info -->

    </div><!-- end .entry -->

<?php endforeach?>
<?php mysql_close(); ?>


</div><!-- end #blogEntries -->
</div><!-- end #right -->

<script type="text/javascript">
this.Iam.Admin.Load();
this.Iam.Archmenu.Load();
</script>
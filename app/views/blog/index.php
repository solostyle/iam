<?php session_start(); ?>
<?php foreach ($blog as $entry):?>

<?php 
    require_once (ROOT . DS . '235' . DS . 'presentfunc.php');
    $e = stripslashes($entry['Entry']['entry']);
    $e = nl2p_or_br($e);
    $ttl = stripslashes($entry['Entry']['title']);
    $l = make_url($entry['Entry']['id']);
    $date = parse_date($entry['Entry']['time']);
    $time = parse_time($entry['Entry']['time']);
    $assigned_tags = show_tags($entry['Entry']['id']);
    $tags_arr = tags_arr($entry['Entry']['id']);
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
            <?php if (isset($_SESSION['logged_in'])):?>
                <ul id="tagEntry_<?php echo $entry['Entry']['id']?>">

                <?php foreach ($tags_arr as $tag => $assigned): ?>

                    <li><input type="checkbox" id="tagEntry_<?php echo $entry['Entry']['id']?>_<?php echo $tag?>" name="tags_<?php echo $entry['Entry']['id']?>[]" value="<?php echo $tag?>" <?php echo ($assigned) ? 'checked="checked' : ""?> /><?php echo $tag?></li>

                <?php endforeach; ?>

                </ul>
            <?php elseif ($assigned_tags!=''):?>
                <div>Tagged with <?php echo $assigned_tags?></div>
            <?php endif; ?>

            <p><a href="<?php echo $l?>">Permalink</a></p>

            <?php if (isset($_SESSION['logged_in'])):?>
                <p><a id="deleteEntry_<?php echo $entry['Entry']['id']?>">Delete</a></p>
            <?php endif; ?>
        </div><!-- end .info -->

    </div><!-- end .entry -->

<?php endforeach?>
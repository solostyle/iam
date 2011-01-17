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
    $tags = show_tags($entry['Entry']['id']);
?>
    <div class="entry" id="entry_<?php echo $entry['Entry']['id']?>">
        <div class="main">
            <h2><?php echo $ttl?></h2>
            <?php echo $e?>
            <!-- <p>
             <em><a name="bot" href="http://iam.solostyle.net/comment.php">comment</a></em>
            </p> -->
        </div><!-- end .main -->
        <div class="info">
            <p>Posted on <?php echo $date . ' at ' . $time?></p>
            <p><a href="#">0 comments</a> so far</p>
            <?php if ($tags!=''):?>
                <p>Tagged with <?php echo $tags?></p>
            <?php endif; ?>
            <p><a href="<?php echo $l?>">Permalink</a></p>

            <?php if (isset($_SESSION['logged_in'])):?>
                <a id="deleteEntry_<?php echo $entry['Entry']['id']?>">Delete</a>
            <?php endif; ?>
        </div><!-- end .info -->

    </div><!-- end .entry -->

<?php endforeach?>
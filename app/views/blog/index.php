<div id="blogEntries">

<?php 
	if ($isAjax) {
		session_start();
	}
	if (isset($tag)) {
		echo '<h3>Entries tagged with '.$tag['tag_nm'].':</h3>';
	}
?>

<?php if (isset($totalPages) && $totalPages>1):?>
<ul class="article-pages">
<li>
<?php if ($currentPageNumber>1) echo $html->link('<< prev',$url.'/page/'.($currentPageNumber-1))?></li>
<?php for ($i = 1; $i <= $totalPages; $i++):?>
<li>
<?php if ($i == $currentPageNumber):?>
<?php echo $currentPageNumber?>
<?php else: ?>
<?php echo $html->link($i,$url.'/page/'.$i)?>
<?php endif?>
</li>
<?php endfor?>
<li>
<?php if ($currentPageNumber<$totalPages) echo $html->link('next >>',$url.'/page/'.($currentPageNumber+1))?></li>
</ul>
<?php endif?>


<?php foreach ($blog as $entry):?>

<?php 
    require_once (ROOT . DS . '235' . DS . 'presentfunc.php');
    $e = stripslashes($entry['Entry']['entry']);
    //$e = nl2p_or_br($e);
    $ttl = stripslashes($entry['Entry']['title']);
    $l = make_url($entry['Entry']['id']);
    $date = parse_date($entry['Entry']['time']);
    $time = parse_time($entry['Entry']['time']);
	$tags = array();
	$tagInfo = (count($entry['Tag']) > 0) ? $entry['Tag'] : '';
	if ($tagInfo) {
		foreach ($tagInfo as $tagInfo) {
			$tags[] = make_link($tagInfo['Tag']['tag_nm'], make_url('tag/' . $tagInfo['Tag']['tag_nm']));
		}
	}
	$cat = str_replace(" ", "_", $entry['Entry']['category']); // make it a kosher URL piece
	$c = make_link($entry['Entry']['category'], make_url('category/' . $cat));  // when saving new category, do it in the javascript
?>

    <article class="entry" id="entry_<?php echo $entry['Entry']['id']?>">
		<header class="article-header">
			<!--allow editing of title only if logged in-->
            <?php if (isset($_SESSION['logged_in'])):?>
                <div class="entryEditButton" id="editTitle_<?php echo $entry['Entry']['id']?>">Edit</div>
            <?php endif; ?>
            
            <h2 id="entryTitle_<?php echo $entry['Entry']['id']?>"><?php echo $ttl?></h2>
		</header>
		<footer class="article-footer">
			<ul>
				<li><?php echo $date . ' at ' . $time?></li>
					<!--<p><a href="#">0 comments</a> so far</p>-->
				<li><a href="<?php echo $l?>">Permalink</a></li>
			<?php if ($tags):?>
                <li>Tags: <?php foreach ($tags as $tag) echo $tag.' '?></li>
            <?php endif; ?>
            
				<li>
			<?php if (isset($_SESSION['logged_in'])):?>
                <div class="entryEditButton" id="editCategory_<?php echo $entry['Entry']['id']?>">Edit</div>
            <?php endif; ?> Category: <span id="entryCategory_<?php echo $entry['Entry']['id']?>"><?php echo $c?></span></li>

            <?php if (isset($_SESSION['logged_in'])):?>
                <li><a id="deleteEntry_<?php echo $entry['Entry']['id']?>">Delete</a></li>
            <?php endif; ?>
			</ul>
        </footer>
        <div class="article-content">
        
            <!--allow editing of entry only if logged in-->
            <?php if (isset($_SESSION['logged_in'])):?>
                <div class="entryEditButton" id="editEntry_<?php echo $entry['Entry']['id']?>">Edit</div>
            <?php endif; ?>

            <div id="entryEntry_<?php echo $entry['Entry']['id']?>"><?php echo $e?></div>
            
            <!-- <p>
             <em><a name="bot" href="http://iam.solostyle.net/comment.php">comment</a></em>
            </p> -->
        </div><!-- end .article-content -->
    </article><!-- end .entry -->

<?php endforeach?>


</div><!-- end #blogEntries -->

<script type="text/javascript">
Iam.Objects.Blog = <?php echo json_encode($blog); ?>;
</script>
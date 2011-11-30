<?php if (!isset($_SESSION['logged_in'])):?>
<p>Sorry, that function is limited to authenticated users.</p>
<?php else: ?>
<?php
select_db();
publish_feed("rss", 7);
publish_feed("atom", 7);
mysql_close();
?>

<?php endif; ?>
</div> <!-- end #right -->
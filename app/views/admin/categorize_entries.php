<?php if (!isset($_SESSION['logged_in'])):?>
<p>Sorry, that function is limited to authenticated users.</p>
<?php else: ?>
<?php
select_db();
// code for categorizing different entries
print 'testing';
mysql_close();
?>

<?php endif; ?>
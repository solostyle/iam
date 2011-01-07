<!-- <h1 id="addAnEntry">Add an Entry</h1>
 <div id="blogForm">
 <p><input type="text" value="title" id="blogWPTitle"/></p>
 <p><textarea rows="2" cols="25" id="blogWPEntry"></textarea></p>
 <p><input type="button" id="addEntry" value="add"/></p>
 </div>-->

<?php foreach ($blog as $entry):?>

<?php 
    require_once (ROOT . DS . '235' . DS . 'presentfunc.php');
    $e = stripslashes($entry['Entry']['entry']);
    $e = nl2p_or_br($e);
    $ttl = stripslashes($entry['Entry']['title']);
    $l = make_url($entry['Entry']['id']);
    $date = parse_date($entry['Entry']['time']);
    $time = parse_time($entry['Entry']['time']);
?>
    <div class="entry" id="entry_<?php echo $entry['Entry']['id']?>">
        <div class="main">
            <h2><?php echo $ttl?></h2>
            <?php echo $e?>
        </div>
        <div class="info">
            <p>Posted on <?php echo $date . ' at ' . $time?></p>
            <p>Tagged with </p>
            <p><a href="<?php echo $l?>">Permalink</a></p>
        </div>
        <a id="deleteEntry_<?php echo $entry['Entry']['id']?>">Delete</a>
    </div>

<?php endforeach?>
<!-- <h1 id="addAnEntry">Add an Entry</h1>
 <div id="blogForm">
 <p><input type="text" value="title" id="blogWPTitle"/></p>
 <p><textarea rows="2" cols="25" id="blogWPEntry"></textarea></p>
 <p><input type="button" id="addEntry" value="add"/></p>
 </div>-->

<?php foreach ($blog as $entry):?>

    <div class="entry" id="entry<?php echo $entry['Entry']['id']?>">
        <div class="main">
            <div class="title"><?php echo $entry['Entry']['title']?></div>
            <div class="text"><?php echo $entry['Entry']['entry']?></div>
        </div>
        <div class="info">
            <div class="time"><?php echo $entry['Entry']['time']?></div>
        </div>
        <a id="deleteEntry_<?php echo $entry['Entry']['id']?>">Delete</a>
    </div>

<?php endforeach?>
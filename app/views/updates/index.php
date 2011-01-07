     <h1 id="addAnUpdate">Add an Update</h1>
     <div id="updatesForm">
     <p><input type="text" value="name" id="updatesWPName"/></p>
     <p><textarea rows="2" cols="25" id="updatesWPUpdate"></textarea></p>
     <p><input type="button" id="addUpdate" value="add"/></p>
     </div><!-- end form div -->

     <div id="updates">

         <?php foreach ($updates as $u):?>

         <div class="update" id="update<?php echo $u['Update']['id']?>">
         <div class="id"><?php echo $u['Update']['id']?></div>
         <div class="name"><?php echo $u['Update']['name']?></div>
         <div class="time"><?php echo $u['Update']['time']?></div>
         <div class="text"><?php echo $u['Update']['update']?></div>
         <a id="deleteUpdate_<?php echo $u['Update']['id']?>">Delete</a>
         </div>

         <?php endforeach?>

     </div>
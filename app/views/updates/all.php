<?php foreach ($updates as $u):?>
     
<div class="update" id="update<?php echo $u['Update']['id']?>">
     <div class="id"><?php echo $u['Update']['id']?></div>
     <div class="name"><?php echo $u['Update']['name']?></div>
     <div class="time"><?php echo $u['Update']['time']?></div>
     <div class="text"><?php echo $u['Update']['update']?></div>
     <a id="deleteUpdate_<?php echo $u['Update']['id']?>">Delete</a>
</div>

<?php endforeach?>
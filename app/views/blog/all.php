<?php if ($includeForm):?>
 <h1 id="addAComment">Add a Comment</h1>
 <div id="commentsForm"
 <p><input type="text" value="name" id="commentsWPName"/></p>
 <p><textarea rows="2" cols="25" id="commentsWPComment">comment</textarea></p>
 <p><input type="button" id="addComment" value="add"/></p>
 </div><!-- end form div -->

 <div id="comments">
<?php endif?>

     <?php foreach ($comments as $c):?>

     <div class="comment" id="comment<?php echo $c['Comment']['id']?>">
     <div class="id"><?php echo $c['Comment']['id']?></div>
     <div class="name"><?php echo $c['Comment']['name']?></div>
     <div class="time"><?php echo $c['Comment']['time']?></div>
     <div class="text"><?php echo $c['Comment']['comment']?></div>
     <a id="deleteComment_<?php echo $c['Comment']['id']?>">Delete</a>
     </div>

     <?php endforeach?>

<?php if ($includeForm):?>
 </div>
<?php endif?>
<?php
    if (isset($_POST['addEntry'])) {
            select_db();
            $addEntry_fields = array('id','time','title','entry');
            $blog_id = create_id($_POST['title'], $_POST['year'], $_POST['month'], $_POST['date']);
            $addEntry_values = array($blog_id, $_POST['time'], mysql_real_escape_string($_POST['title']), mysql_real_escape_string($_POST['entry']));

echo '<pre>';
print_r ($addEntry_fields);
print_r ($addEntry_values);
echo '</pre>';

            insert_record('blog', $addEntry_fields, $addEntry_values);
            assign_category($blog_id, $_POST['category']);
            mysql_close();
    }
?>


<div id="right">
    <div id="adminEntry" class="entry addform">
        <?php if (!isset($_SESSION['logged_in'])):?>
                <p>Sorry, that function is limited to authenticated users.</p>
        <?php else: ?>
            <?php select_db();?>
            <h2>Add an Entry</h2>
            <!-- the title of the entry, would like this to be wider -->
            <div class="row">
                <span class="label" style="width:10%">Title</span>
                <span class="field" style="width:87%"><input type="text" id="title" value="" width="100%" /></span>
            </div>

            <!-- the category of the entry, radio buttons -->   
            <div class="row">
                <span class="label" style="width:10%">Category</span>
                <span class="field" style="width:87%">
                    <?php 
                    $cats = rtrv_categories();
                    foreach ($cats as $cat):?>
                        <input type="radio" name="category" id="category_<?php echo $cat?>" value="<?php echo $cat?>" /><?php echo $cat?>&nbsp;&nbsp;
                    <?php endforeach;?>
                </span>
            </div>

            <!-- the entry content -->
            <div class="row" style="clear:both">
                <span class="label" style="width:10%">Entry</span>
                <span class="field" style="width:87%"><textarea  id="entry" value=""></textarea></span>
            </div>

            <!-- The time values load with current data.
                When the page reloads, those values should not update.
                Only when the Now button is hit will they update. -->
            <?php $now  = my_mktime();?>
            <div class="row" style="clear:both">
                <span class="label" style="width:10%">Time</span><span class="field" style="width:87%">
                <input type="text" id="year" name="year" size="3" maxlength="4" value="<? echo strftime('%G',$now); ?>" />
                <input type="text" id="month" name="month" size="1" maxlength="2" value="<? echo strftime('%m',$now); ?>" />
                <input type="text" id="date" name="date" size="1" maxlength="2" value="<? echo strftime('%d',$now); ?>" />
                <input type="text" id="hour" name="hour" size="1" maxlength="2" value="<? echo strftime('%H',$now); ?>" />
                <input type="text" id="minute" name="minute" size="1" maxlength="2" value="<? echo strftime('%M',$now); ?>" />

                <input type="button" name="Change" value="Change" onmouseup="document.getElementById('time').value=document.getElementById('year').value + '.' + document.getElementById('month').value + '.' + document.getElementById('date').value + ' ' + document.getElementById('hour').value + ':' + document.getElementById('minute').value;" />
                <input type="text" readonly="readonly" id="time" size="20" value="<? echo strftime('%G.%m.%d %H:%M',$now); ?>" />
                </span>
            </div>

            <!-- image verification -->
            <?
            // get font characteristics
            $font = imageloadfont(ROOT.DS.'8x13iso.gdf');
            $chars = 5;
            $height = imagefontheight($font);
            $width = imagefontwidth($font) * $chars;
            $_SESSION['verify_string'] = gen_pw($chars);

            $image = imagecreate($width, $height);
            $fg_color = ImageColorAllocate($image, 125, 125, 125);
            $bg_color = ImageColorAllocate($image, 049, 049, 049);
            ImageFill($image, 0, 0, $bg_color);
            ImageString($image, $font, 0, 0,$_SESSION['verify_string'], $fg_color);
            /* output to browser*/
            ImagePNG($image, ROOT.DS.'verify.png');
            ImageDestroy($image);
            ?>
            <div class="row" style="clear:both">
                <p>Image verification</p>
                <span class="label"><img style="border:1px solid #333;padding:2px;" src="<?php echo make_url('verify.png');?>" /></span><span class="field"><input type="text" id="verify" name="verify" size="9" /></span>
            </div>
            <!-- end image verification -->

            <div class="row" style="clear:both">
                <span class="label"><input type="Reset" name="reset" value="Reset" /></span>
                <span class="field"><input type="submit" id="addEntry" value="Add Entry" /></span>
            </div>
            <?php mysql_close();?>
        <?php endif;?>
    </div> <!-- end addentry -->
</div><!-- end #right -->
<script type="text/javascript">
this.Iam.Admin.Load();
</script>
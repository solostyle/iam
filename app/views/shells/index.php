<div id="right">
    <?php if (isset($_SESSION['logged_in'])): ?>
    <div id="blogAddForm">

        <h2 id="addAnEntry">Add an Entry</h2>
        <div id="addForm">
            <div class="main">
                <p><input type="text" value="title" id="addFormTitle"/></p>
                <p><textarea id="addFormEntry"></textarea></p>
            </div><!-- end .main -->

            <div class="info">

                <!-- the category of the entry, radio buttons -->   
                <ul>
                    <?php 
                    select_db();
                    $cats = rtrv_categories();
                    mysql_close();
                    foreach ($cats as $cat):?>
                        <li><input type="radio" name="category" id="addFormCategory_<?php echo $cat?>" value="<?php echo $cat?>" /><?php echo $cat?></li>
                    <?php endforeach;?>
                </ul>


                <!-- the date and time, modifiable -->
                <?php $now  = my_mktime();?>
                <p>
                    <input type="text" id="year" name="year" size="3" maxlength="4" value="<? echo strftime('%G',$now); ?>" />
                    <input type="text" id="month" name="month" size="1" maxlength="2" value="<? echo strftime('%m',$now); ?>" />
                    <input type="text" id="date" name="date" size="1" maxlength="2" value="<? echo strftime('%d',$now); ?>" />
                    <input type="text" id="hour" name="hour" size="1" maxlength="2" value="<? echo strftime('%H',$now); ?>" />
                    <input type="text" id="minute" name="minute" size="1" maxlength="2" value="<? echo strftime('%M',$now); ?>" />
                </p>
                <p>
                    <input type="button" name="Change" value="Change" onmouseup="document.getElementById('addFormTime').value=document.getElementById('year').value + '.' + document.getElementById('month').value + '.' + document.getElementById('date').value + ' ' + document.getElementById('hour').value + ':' + document.getElementById('minute').value;" />
                </p>
                <p>
                    <input type="text" readonly="readonly" id="addFormTime" size="20" value="<? echo strftime('%G.%m.%d %H:%M',$now); ?>" />
                </p>

                <p><input type="button" id="addFormSubmit" value="add"/></p>
            </div><!-- end .info -->
        </div><!-- end #addForm -->

    </div><!-- end $blogAddForm -->
    <?php endif; ?>

    <div id="blogEntries">
        <?php 
            select_db();
            print implode('', array_map("make_entry", rtrv_entries('', 3)));
            mysql_close();
        ?>
    </div><!-- end #blogEntries -->
</div><!-- end #right -->

<script type="text/javascript">
this.Iam.Shell.LoadWebParts();
</script>
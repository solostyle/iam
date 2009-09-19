<div style="clear:both">
	<h2>Say something</h2>
	<p><em>Instructions</em></p>
	<ul><li>you must have a human brain</li>
		<li>* fields are required</li>
		<li>your Email will not be published but will be saved for contact</li>
	</ul>
	<hr />
</div>

<div id="commentform">
    <form name="commentform" action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
		<!-- The time values load with current data. -->
		<? $now  = my_mktime();
		?>
		<input style="border:none" type="hidden" readonly="readonly" id="time" name="time" size="20" value="<? echo strftime('%G.%m.%d %H:%M',$now); ?>" />

			<input type="hidden" id="year" name="year" size="5" maxlength="4" value="<? echo strftime('%G',$now); ?>" />
			<input type="hidden" id="month" name="month" size="3" maxlength="2" value="<? echo strftime('%m',$now); ?>" />
			<input type="hidden" id="date" name="date" size="3" maxlength="2" value="<? echo strftime('%d',$now); ?>" />
			<input type="hidden" id="hour" name="hour" size="3" maxlength="2" value="<? echo strftime('%H',$now); ?>" />
			<input type="hidden" id="minute" name="minute" size="3" maxlength="2" value="<? echo strftime('%M',$now); ?>" />

		<div class="row">
			<span class="label">You*</span><span class="field"><input type="text" id="username" name="name" value="<? echo $_SESSION['username']; ?>" tabindex="1" /></span>
		</div>
		<div class="row">
			<span class="label">Web</span><span class="field">http://&nbsp;<input type="text" size="15" name="website" tabindex="2" /></span>
		</div>
		<div class="row">
			<span class="label">Email</span><span class="field"><input type="text" size="4" name="alias" tabindex="3" />&nbsp;@&nbsp;<input type="text" size="4" name="domain" tabindex="4" />&nbsp;.&nbsp;<input type="text" size="1" name="ext" tabindex="5" /></span>
		</div>
		<div class="row">
			<span class="label">Speak*</span><span class="field"><textarea name="comment" cols="50" rows="2" value="" tabindex="6"></textarea></span>
		</div>

<!-- image verification -->
		<?
			// get font characteristics
			$font = imageloadfont('anonymous.gdf');
			$chars = 5;
			$height = imagefontheight($font);
			$width = imagefontwidth($font) * $chars;
			$_SESSION['verify_string'] = gen_pw($chars);

			$image = imagecreate($width, $height);
			$fg_color = ImageColorAllocate($image, 255, 255, 255);
			$bg_color = ImageColorAllocate($image, 049, 030, 094);
			ImageFill($image, 0, 0, $bg_color);
			ImageString($image, $font, 0, 0, $_SESSION['verify_string'], $fg_color);

			/* output to browser*/
			ImagePNG($image, "verify.png");
			ImageDestroy($image);
		?>
		<div class="row" style="clear:both">
			<p>Copy the blue box text on the left into the box on the right*</p>
			<span class="label"><img style="border:2px solid #C5E127" src="verify.png" /></span><span class="field"><input type="text" id="verify" name="verify" size="9" /></span>
		</div>
<!-- end image verification -->

		<div class="row" style="clear:both">
			<span class="label"><input type="Reset" name="reset" value="Start over" /></span><span class="field"><input type="submit" name="submit_cnt" value="Send" /></span>
		</div>
	</form><!-- end form -->
</div> <!-- end commentform div -->

<div style="clear:both">

<?
if ($blog_id) {
	print '
		<hr />
		<div>
		<h2>Now what?</h2>
		<p>Go back to the post that you wanted to comment on.';
	$blah = make_url($blog_id);
	print '
		Might it be <a href="'. $blah . '">here</a>?
		</p>
		</div>';
}
?>

</div>


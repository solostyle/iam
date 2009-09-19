<?php
	include '567/_top.php';

	print '<div id="center" class="col">';

	select_db($s, $u, $p, $db);
	
	if (isset($_SESSION['mbr_email'])) {

		// look up this dude in the login table and get his number
		$q = "select `mbr_nbr` from `member_login` where `email` = '" . $_SESSION['mbr_email'] . "'";
		$result = mysql_query($q);
		$row = mysql_fetch_array($result);
		$mbr_nbr = $row[0];

		//use this number to get all the other data
		//names
		$q = "select `first_name`, `last_name`, `relation` from `member_names` where `mbr_nbr` = $mbr_nbr";
		$result = mysql_query($q);
		print disp_fieldset_names($result);
	}

	else print "nothing here";
	
	function disp_fieldset_names($result) {
		$content = '
			<fieldset>
				<legend>Names</legend>';
		$cntr = 0;
		while ($row = mysql_fetch_array($result)) {
			$cntr++;
			$content .= disp_inp_sel($row[0],"relation" . $cntr);
			$content .= disp_inp_txt($row[1],"firstName" . $cntr);
			$content .= disp_inp_txt($row[2],"lastName" . $cntr);
		}

		$content .= '
			</fieldset>';
		return $content;
	}

	function disp_inp_sel($value,$desc) {
		$content = '
			<label for="' . $desc . '"> ' . $desc . '
			<select id="' . $desc . '">
				<option selected="selected">Adult</option>
				<option>Child</option>
				</select></label>';
		return $content;
	}

	function disp_inp_txt($value,$desc) {
		$content = '
			<label for="' . $desc . '"> ' . $desc . '
			<input id="' . $desc . '" type="text" value="' . $desc . '" />
			</label>';
		return $content;
	}


	print '</div>';
	mysql_close();
	include '567/_bot.php';
?>

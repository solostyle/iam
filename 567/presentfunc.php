<?

//----------------------------------------------------------------------------
// Presentation Layer --------------------------------------------------------
// ---------------------------------------------------------------------------


// Make the entries
// Show full entry
function make_entry($row) {
	$date = parse_date($row[2]);
	$time = parse_time($row[2]);
	$url = make_url($row[0]);

	$entry = stripslashes($row[4]);
	$entry = nl2p_or_br($entry);
	$title = stripslashes($row[3]);

	// display entry
	$content = "
		<div class=\"publishedentry\">
			<h2>$title</h2>
			<h3>$date @ $time</h3>
			$entry
			<div style=\"clear:both; padding-bottom: 0.25em;\"></div>
			<!-- <p>
				<em><a name=\"bot\" href=\"http://iam.solostyle.net/comment.php\">comment</a></em>
			</p> -->
		</div>";

	// display tags
	$content .= show_tags($row[0]);

	// display comment rows
	//$get_comments = "SELECT * FROM `comments` WHERE `post_tag`='$row[0]' ORDER BY `time`";
	//$comments = mysql_query($get_comments);
	//while ($comment_row = mysql_fetch_array($comments)) {
	//	$content .= show_comment($comment_row);
	//}
	//mysql_free_result($comments);

	return $content;
}


// Make a blog entry url for a page
// 9 mar 09: created
function make_url($blog_id) {
	return "http://iam.solostyle.net/entry.php?id=" . $blog_id;
}


// Show the tags for a blog entry
// 1 mar 09: created
function show_tags($blog_id) {
	$tags = get_tags($blog_id);
	$content = '<ul>';
	while ($tag = mysql_fetch_array($tags))
		$content .= '<li><a href="show_tag_entries($tag)">' . $tag[0] . '</a></li>';
	$content .= '</ul>';
	return $content;
}


// Make the comment
function make_comment($row) { //???, blog_id, name, website, email, comment, time
	$date = parse_date($row[6]);
	$time = parse_time($row[6]);
	$url = make_url($row[1]);


	$comment = stripslashes(strip_tags($row[5]));
	$comment = nl2p_or_br($comment);
	$website = $row[3];

	$content = "
			<div>
				<h3>$date @ $time by <a href=\"$website\">$row[2]</a></h3>
				<p>$comment</p>
				<div style=\"clear:both; padding-bottom: 0.25em;\"></div>
			</div>";
	return $content;
}


// Show entry preview
function show_preview($row) {
	$date = parse_date($row[2]);
	$time = parse_time($row[2]);
	$url = make_url($row[0]);

	$text = substr(strip_tags($row[4]), 0, 200) . "...<a href=\"$url\">full</a>";
	$text = stripslashes($text);
	$title = stripslashes($row[3]);
	$content = "
	<div>
		<div class=\"preview\">
			<h2>$title</h2>
			<h3>$date @ $time</h3>
			<p>$text</p>
		</div>
	</div>";
	return $content;
}

// Show the comment
function show_comment($row) {//???, blog_id, name, website, email, comment, time
	$date = parse_date($row[6]);
	$time = parse_time($row[6]);
	$url = make_url($row[1]);

	$comment = strip_tags($row[5]);
	$title = stripslashes($row[2]);

	$content = "
		<div class=\"comment\">
			<h2> <a href=\"$url\">$title</a></h2>
			<h3>$date @ $time by $row[2]</h3>
			<p>$comment</p>
			<div style=\"clear:both; padding-bottom: 0.25em;\"></div>
		</div>";
	return $content;
}


// Format the entry for editing
function list_entry_republish($row) {
	$date = parse_date($row[2]);
	$time = parse_time($row[2]);
	$url = make_url($row[0]);

	$entry = substr(strip_tags($row[4]), 0, 200) . "...<a href=\"$url\">full</a>";

	$content = "
		<div class=\"preview\">
			<h2><input type=\"checkbox\" name=\"republish[]\" value=\"${row[0]}\">&nbsp;$row[3]</h2>
			<h3>$date &nbsp; $time</h3>
			<p>$entry</p>
		</div>";
	return $content;
}


// Format the entry for tagging
// 11 feb 09
function list_entry_tag($row) {
	$date = parse_date($row[2]);
	$time = parse_time($row[2]);
	$url = make_url($row[0]);

	$entry = substr(strip_tags($row[4]), 0, 200) . '...<a href="' . $url . '">full</a>';
	$title = stripslashes($row[3]);

	$content = '
		<div class="preview">
			<h2><input type="radio" name="blog_id" value="' . $row[0] . '" />' . $title . '</h2>
			<h3>' . $date . ' &nbsp; ' . $time . '</h3>
			<p>' . $entry . '</p>
			<!-- now show the tags in checkbox form -->';
	$content .= tags_form($row[0]);
	$content .= '</div>';
	
	// allow write-ins
	$content .= '<p><input type="text" name="tag_wi" value="" /></p>';
	
	return $content;
}


// Format the entry for categorizing
// 9 mar 09
function list_entry_categorize($row) {
	$date = parse_date($row[2]);
	$time = parse_time($row[2]);
	$url = make_url($row[0]);

	$entry = substr(strip_tags($row[4]), 0, 200) . '...<a href="' . $url . '">full</a>';
	$title = stripslashes($row[3]);

	$content = '
		<div class="preview">
			<h2><input type="radio" name="blog_id" value="' . $row[0] . '" />' . $title . '</h2>
			<h3>' . $date . ' &nbsp; ' . $time . '</h3>
			<p>' . $entry . '</p>
			<!-- now show the categories in radio form -->';
	$content .= categories_form();
	$content .= '</div>';
	
	// allow write-ins
	$content .= '<p><input type="text" name="cat_wi" value="" /></p>';
	
	return $content;
}

// List months in a drop down menu
// 6 feb 09: created
function list_months($name_of_menu, $index_to_select) {
	$content = '<select name="' . $name_of_menu . '" size="1">';
	for($k=1;$k<=12;$k++) {
		$date = mktime(0, 0, 0, $k, 1, date("Y")); //doesn't matter what the year is
		$option = strftime('%B',$date);
		$opt = strftime('%m',$date);
		$content .= '<option value="' . $opt . '"';
		if ($k == $index_to_select) 
			$content .= ' selected="selected"';
		$content .= '>' . $option . '</option>';
	}
	$content .= '</select>';
	return $content;
}


// List the years in a drop down menu
// 7 feb 09: created
function list_years($name_of_menu, $index_to_select) {
	$content = '<select name="' . $name_of_menu . '" size="1">';
	$start_y = strftime("%Y", strtotime(blog_first_date()));
	$end_y = strftime("%Y", strtotime(blog_last_date()));
	for($k=$start_y;$k<=$end_y;$k++) { 
		$content .= '<option value="' . $k . '"';
		if ($k == $index_to_select) 
			$content .= ' selected="selected"';
		$content .= '>' . $k . '</option>';
	}
	$content .= '</select>';
	return $content;
}


// Display all the tags in a checkbox form for tagging
// 13 feb 09: display tags in columns, maybe a table? allow write-ins?
// 11 feb 09: created
// 15 mar 09: show a checked box when the tag association exists
function tags_form($blog_id) {
	$result = rtrv_tags();
	$content = '<p>';

	// create the array of tag names
	$tag_nms = array();
	while ($tag_nm = mysql_fetch_array($result)) {
		array_push($tag_nms, $tag_nm[0]);
	}

	// create the checkboxes	
	for ($i=0; $i<=count($tag_nms); $i++) {
		$content .= '<input type="checkbox" name="tag_nms[]" value="' . $tag_nms[$i] . '"';
		$query = "SELECT 1 FROM `blog_tag` WHERE `blog_id` = '" . $blog_id . "'";
		$query .= " AND `tag_nm` = '" . $tag_nms[$i] . "'";
		$bool = mysql_query($query);
		while ($row = mysql_fetch_array($bool))
			if ($row[0] = 1)
				$content .= ' checked="checked"';
		$content .= '>';
		$content .= $tag_nms[$i] . '&nbsp;&nbsp;'; 
	}

	$content .= '</p>';
	return $content;
}


// Display the tags in radio buttons to choose one 
// 5 mar 09: created
function list_tags() {
	$result = rtrv_tags();
	$content = '<p>';
	while ($tag_nm = mysql_fetch_array($result)) {
		$content .= '<input type="radio" name="tag" value="' . $tag_nm[0] . '">';
		$content .= $tag_nm[0] . '<br />'; 
	}
	$content .= '</p>';
	return $content;	
}



// Retrieve all the tags for displaying and selecting
// 11 feb 09: created
function rtrv_tags() {
	$query = "
		SELECT `tag_nm`
		FROM `tag`";
	$result = mysql_query($query);
	return $result;
}


// Display all the categories in a radio form for categorizing
// 9 mar 09: created
function categories_form() {
	$result = rtrv_categories();
	$content = '<p>';
	while ($cat_nm = mysql_fetch_array($result)) {
		$content .= '<input type="radio" name="cat_nm" value="' . $cat_nm[0] . '">';
		$content .= $cat_nm[0] . '&nbsp;&nbsp;'; 
	}
	$content .= '</p>';
	return $content;
}


// Display the categories in radio buttons to choose one 
// 9 mar 09: created
function list_categories() {
	$result = rtrv_categories();
	$content = '<p>';
	while ($cat_nm = mysql_fetch_array($result)) {
		$content .= '<input type="radio" name="cat" value="' . $cat_nm[0] . '">';
		$content .= $cat_nm[0] . '<br />'; 
	}
	$content .= '</p>';
	return $content;	
}



// Retrieve all the tags for displaying and selecting
// 9 mar 09: created
function rtrv_categories() {
	$query = "
		SELECT `category_nm`
		FROM `category`";
	$result = mysql_query($query);
	return $result;
}

//Convert blog_ids to urls for page links
//function DONT USE() {
//	//tag:iam.solostyle.net,2006-04-04:/location
//	$location = strrchr($tag,":");			//:/location
//	$location = substr($location,1);		///location
//	$location = str_replace("\\","",$location); 	//to get rid of escape characters!
//	$pos = strpos($tag,",");			//probably 24th
//	$url1 = substr($tag,0,$pos);			//tag:iam.solostyle.net
//	$url1 .= $location . ".php";						//tag:iam.solostyle.net/location.php
//	$url = str_replace("tag:","http://",$url1);	//http://iam.solostyle.net/location.php
//	return $url;
//}


function nl2p_or_br($text) {
  $text_with_p = "<p>" . str_replace("\r\n\r\n", "</p><p>", $text) . "</p>";
  $text_with_p_and_br = str_replace("\r\n", "<br />", $text_with_p);
  return $text_with_p_and_br;
}


// Create id based on POST data
// Can be used for both blog entries and comments
function create_id($title, $year, $month, $date) {
	$blogidtitle = parse_title($title);
	$blog_id = $year . "-" . $month . "-" . $date . ":" . $blogidtitle;
	return $blog_id;
}

// Parse Time
// used in retrieving values from database and displaying entries
// input is the fetched value from the database, timestamp(14) 2004-09-30 06:05:52
function parse_time($time) {
   $hr = substr($time, 11, 2);
   $mn = substr($time, 14, 2);
   return "$hr:$mn";
}

// Parse Date
// used in retrieving values from database and displaying entries
// input is the fetched value from the database, timestamp(14)
function parse_date($time) {
   $yr = substr($time, 0, 4);
   $mo = substr($time, 5, 2);
   $da = substr($time, 8, 2);
   $month = monthname($mo);

   return "$da $month $yr ";
}


// Parse Title to be inserted into url/blog_id
//Converts title to url/blog_id
function parse_title($title) {
	$special = array("!", "@", "$", "%", "^", "&", "*", ":", "'", ";", "<", ">", ",", ".", "?", "/", chr(34));
	$name = strtolower($title);
	if (substr($name, 0, 3) == "the") $name = substr($name, 3);
	$name = str_replace($special, "", $name);
	$name = str_replace(" ", "-", $name);
	return $name;
}


// takes a two digit month number and returns the month name
function monthname($num) {
	switch ($num) {
	case "01":
	   $month = "January";
	   break;
	case "02":
	   $month = "February";
	   break;
	case "03":
	   $month = "March";
	   break;
	case "04":
	   $month = "April";
	   break;
	case "05":
	   $month = "May";
	   break;
	case "06":
	   $month = "June";
	   break;
	case "07":
	   $month = "July";
	   break;
	case "08":				//so weird!!!
	   $month = "August";
	   break;
	case "09":
	   $month = "September";
	   break;
	case "10":
	   $month = "October";
	   break;
	case "11":
	   $month = "November";
	   break;
	case "12":
	   $month = "December";
	   break;
	}
	return $month;
}

?>

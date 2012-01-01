<?

//----------------------------------------------------------------------------
// Presentation Layer --------------------------------------------------------
// ---------------------------------------------------------------------------

// deprecated
// Make the entries
// Show full entry
function make_entry($row) {
	$date = parse_date($row[2]);
	$time = parse_time($row[2]);
	$url = make_url($row[0]);

	$entry = stripslashes($row[4]);
	$entry = nl2p_or_br($entry);
	$title = stripslashes($row[3]);

	$tags = show_tags($row[0]);

	// display entry
	$content = '
		<div class="entry">
                        <div class="main">
			     <h2>' . $title . '</h2>
			     ' . $entry . '
			     <!-- <p>
				     <em><a name=\"bot\" href=\"http://iam.solostyle.net/comment.php\">comment</a></em>
			     </p> -->
                        </div><!-- /end .main -->

			<div class="info">
				<p>Posted on ' . $date . ' at ' . $time . '. <a href="#">0 comments</a> so far.</p>

				<p>Tagged with ' . $tags . '.</p>
			</div><!-- /end .info -->
		</div><!-- /end .entry -->';

	// display comment rows
	//$get_comments = "SELECT * FROM `comments` WHERE `post_tag`='$row[0]' ORDER BY `time`";
	//$comments = mysql_query($get_comments);
	//while ($comment_row = mysql_fetch_array($comments)) {
	//	$content .= show_comment($comment_row);
	//}
	//mysql_free_result($comments);

	return $content;
}

// deprecated
// Display a page (temporary, need to have javascript take in a JSON and render markup
// renders a page
function display_page($inner_markup) {
  if (file_exists(ROOT . DS . 'inc' . DS . 'header.php')) {
    include (ROOT . DS . 'inc' . DS . 'header.php');
  }

  if (file_exists(ROOT . DS . 'left.php')) {
    include (ROOT . DS . 'left.php');
  }

  print $inner_markup;

  if (file_exists(ROOT . DS . 'inc' . DS . 'footer.php')) {
    include (ROOT . DS . 'inc' . DS . 'footer.php');
  }
}

// Make a blog entry url for a page
// 9 mar 09: created
// 5 jan 11: modified to use HOST and DS
function make_url($first, $rest=array()) {
  $url = 'http://' . HOST . DS . $first;
  foreach ($rest as $item) {
    $url .= DS . $item;
  }
  return $url;
}

// Make a link
// 5 jan 11: created
function make_link($text, $url) {
	return '<a href="' . $url . '">' . $text . '</a>';
}

// Make an HTML list of items in an array
// 6 jan 11: created
function make_list($arr, $list_class='', $ordered=false) {
  $content = ($ordered)? '<ol':'<ul';
  $content = ($list_class)? ' class="' . $list_class . '">' : '>';
  foreach($arr as $item) {
    $content .= make_list_item($item);
  }
  $content = ($ordered)? '</ol>':'</ul>';
  return $content;
}

// Make an HTML list item
// 6 jan 11: created
function make_list_item($item) {
  return '<li>' . $item . '</li>';
}

// deprecated
// Show the tags for a blog entry
// 1 mar 09: created
function show_tags($blog_id) {
    $tags = rtrv_tags($blog_id);
    
    if (count($tags)==0) {
        return '';
    } else {
        $content = '<ul class="tags">';
        foreach ($tags as $tag) {
            $content .= make_list_item(make_link($tag, make_url('tags', array($tag))));
        }
        $content .= '</ul>';
        return $content;
    }
}



// Creates the archive navigation menu
// 6 jan 11: created
function create_archive_nav_menu($arr) {

	// start the html
	$html = '<ul class="archlev1 archmenu_list_years" id="archmenu">';
	$years = array_keys($arr);

	foreach($years as $y) {
		if (isset($arr[$y]['display'])) {
			$hiddenClass = '';
			$toggleButtonText = '--';
			unset($arr[$y]['display']);
		} else {
			$hiddenClass = ' hidden';
			$toggleButtonText = '+';
		}
		
		if (isset($arr[$y]['highlight'])) {
			// TODO: use this
			unset($arr[$y]['highlight']);
		} else {
		}
		
		$html .= '<li>';
		$html .= make_link($y . ' (' . $arr[$y][0] . ')', make_url($y.'/'));
		$html .= '<span class="archmenu_ty archToggleButton" id="archmenu_ty_' . $y . '">' . $toggleButtonText . '</span>'; // handle clicks with JS
		$html .= '</li>';
		unset($arr[$y][0]);
		
		$months = array_keys($arr[$y]);
		$html .= '<ul class="archlev2 archmenu_list_months' . $hiddenClass . '" id="archmenu_y_' . $y . '">';
		
		foreach($months as $m) {
			if (isset($arr[$y][$m]['display'])) {
				$hiddenClass = '';
				$toggleButtonText = '--';
				unset($arr[$y][$m]['display']);
			} else {
				$hiddenClass = ' hidden';
				$toggleButtonText = '+';
			}
			
			if (isset($arr[$y][$m]['highlight'])) {
				// TODO: something
				unset($arr[$y][$m]['highlight']);
			} else {
			}
			
			$html .= '<li>';
			$html .= make_link(monthname($m) . ' (' . $arr[$y][$m][0] . ')', make_url($y.'/'.$m.'/'));
			$html .= '<span class="archmenu_tm archToggleButton" id="archmenu_ty_' . $y . '_tm_' . $m . '">' . $toggleButtonText . '</span>'; // handle clicks with JS
			$html .= '</li>';
			unset($arr[$y][$m][0]);

			$entries = $arr[$y][$m];
			$html .= '<ul class="archlev3 archmenu_list_titles' . $hiddenClass . '" id="archmenu_y_' . $y . '_m_' . $m . '">';
			foreach($entries as $id => $entry) {

				$html .= make_list_item(make_link($entry['title'], make_url($id)));
			}
			$html .= '</ul>';
		}
		$html .= '</ul>';
	}
	$html .= '</ul>';
	return $html;
}



// deprecated
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

//json object representing writings
//{writ:[{date: "",time: "",id: "",title: "",text: ""},{date: "",time: "",id: "",title: "",text: ""}]}

// Create and return a JSON representation of an array of writings
function json_writs($rows) {
	 $json_arr = array();

	 for ($i=0;$i<count($rows);$i++) {
	     $json_arr[$i] = json_writ($rows[$i]);
	 }

	 $json = implode(',',$json_arr);

	 $json = "{entries:[" . $json . "]}";

	 return $json;
}


// Create and return a JSON representation of one writing
function json_writ($row) {
	 $json_arr = array();

	 $json_arr[0] = 'id:"' . $row[0] . '"';
	 $json_arr[1] = 'date:"' . parse_date($row[2]) . '"';
	 $json_arr[2] = 'time:"' . parse_time($row[2]) . '"';
	 $json_arr[3] = 'title:"' .$row[3] . '"'; // stripslashes when rendering
	 $json_arr[4] = 'text:"' . $row[4] . '"'; // nl2p_or_br and stripslashes when rendering
	 $tags = implode(',',rtrv_tags($row[0]));
	 if ($tags) {
	    $json_arr[5] = 'tags:[' . $tags . ']';
	 }

	 $json = implode(',',$json_arr);

	 return $json;
}

// deprecated
// The renderer should decide whether to make something abbreviated or full
// Show entry preview
function show_preview($row) {
	$date = parse_date($row[2]);
	$time = parse_time($row[2]);
	$url = make_url($row[0]);

	$text = substrw(strip_tags($row[4])) . " ...<a href=\"$url\">full</a>";
	$text = stripslashes($text);
	$title = stripslashes($row[3]);
	$content = '
	<div class="entry preview">
		<div class="main">
			<h2>' . $title . '</h2>
			<h3>' . $date . ' @ ' . $time . '</h3>
			<p>' . $text . '</p>
		</div>
	</div>';
	return $content;
}


// deprecated
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


// Display all the tags in a checkbox form for tagging
// 13 feb 09: display tags in columns, maybe a table? allow write-ins?
// 11 feb 09: created
// 15 mar 09: show a checked box when the tag association exists
function tags_arr($blog_id) {
    $allTags = rtrv_all_tags();
    $tags_arr = array();

    // create the array of tag names
    $tag_nms = array();
    while ($tag = mysql_fetch_array($allTags)) {
        array_push($tag_nms, $tag[0]);
    }

    // find out if selected or not
    foreach ($tag_nms as $tag_nm) {
        $query = "SELECT 1 FROM `blog_tags` WHERE `blog_id` = '" . $blog_id . "' AND `tag_nm` = '" . $tag_nm . "'";
        $bool = mysql_query($query);

        if (mysql_num_rows($bool)>0) {
            $assigned = mysql_fetch_array($bool);
            $tags_arr[$tag_nm] = $assigned[0];
        } else {
            $tags_arr[$tag_nm] = "";
        }
    }

    return $tags_arr;
}


// Display the tags in radio buttons to choose one 
// 5 mar 09: created
function list_tags() {
	$result = rtrv_all_tags();
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
// 27 sep 09: renamed. need to find the things that use this function because they need to be changed now too
function rtrv_all_tags() {
	$query = "
		SELECT `tag_nm`
		FROM `tags`";
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
    $categories = array();
    $query = "
        SELECT `category_nm`
        FROM `categories`";
    $result = mysql_query($query);
    while($c = mysql_fetch_array($result)) {
        $categories[] = $c[0];
    }
    return $categories;
}

function nl2p_or_br($text) {
    $text_with_p = "<p>" . str_replace("\r\n\r\n", "</p><p>", $text) . "</p>";
    $text_with_p_and_br = str_replace("\r\n", "<br />", $text_with_p);
    // had to add the following two lines after i started using mysql_real_escape_string() on all inserts
    $text_with_p_and_br = str_replace("\n\n", "</p><p>", $text_with_p_and_br);

    $final_text = str_replace("\n", "<br />", $text_with_p_and_br);
    return $final_text;
}


// Create id based on POST data
// Can be used for both blog entries and comments
function create_id($title, $year, $month, $date) {
	$blogidtitle = parse_title($title);
	$blog_id = $year . "/" . $month . "/" . $date . "/" . $blogidtitle;
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


/**
* word-sensitive substring function
* @param text The text to cut
* @param len The maximum length of the cut string
* @returns string
**/
function substrw( $text, $len=200 ) {

    if( (strlen($text) > $len) ) {

        $whitespaceposition = strpos($text," ",$len)-1;

        if( $whitespaceposition > 0 )
            $text = substr($text, 0, ($whitespaceposition+1));

    }

    return $text;
} 

?>

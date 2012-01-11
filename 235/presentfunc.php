<?

//----------------------------------------------------------------------------
// Presentation Layer --------------------------------------------------------
// ---------------------------------------------------------------------------


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

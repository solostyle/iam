<?

$GLOBALS["s"] = "mysql.solostyle.net";
$GLOBALS["u"] = "solostyle";
$GLOBALS["p"] = 'qas??wed';
$GLOBALS["db"] = "iam";


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


// Display a page (temporary, need to have javascript take in a JSON and render markup
// renders a page
function display_page($inner_markup) {
	 header_markup();
	 left_markup();
	 print $inner_markup;
	 footer_markup();
}


// Write the header markup for a page
// 19 sep 09: created. but has a bug: cannot determine if you're signed in
// always shows the login/password form
function header_markup() {











$pagetitle='solostyle.net :: iam';
$cssfile='http://iam.solostyle.net/layout.css';
$extra_header = '<link rel="alternate" type="application/rss+xml" title="RSS" href="http://iam.solostyle.net/rss.xml" />
<link rel="service.feed" type="application/atom+xml" title="Atom" href="http://iam.solostyle.net/atom.xml" />';

$markup_head = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>' . $pagetitle . '</title>
<style type="text/css">
@import url("' . $cssfile . '");
@import url("http://iam.solostyle.net/format.css");
</style>
' . $extra_header . '</head>';

$markup_nav = 
'<body>

<div id="page">
	<h1 id="title"><a href="/">meditations</a></h1>

	<div><!-- /begin nav links -->';

		if (isset($_SESSION['user_id']) AND (substr($_SERVER['PHP_SELF'],-10) != 'signout.php'))
			$markup_nav .= '<ul>
				<li><a href="http://iam.solostyle.net/add.php">add entry</a></li>
				<li><a href="http://iam.solostyle.net/modify.php">modify entry</a></li>
				<li><a href="http://iam.solostyle.net/delete.php">delete entry</a></li>
				<li><a href="http://iam.solostyle.net/republish.php">republish entry</a></li>
				<li><a href="http://iam.solostyle.net/publishfeed.php">publish feeds</a></li>
				<li><a href="http://iam.solostyle.net/tag.php">tag</a></li>
				<li><a href="http://iam.solostyle.net/slct-tag.php">see tag</a></li>
				</ul><ul>
				<li><a href="http://iam.solostyle.net/change_pw.php">Change password</a></li>
				<li><a href="http://iam.solostyle.net/login_woe.php">Login woe?</a></li>
				<li><a href="http://iam.solostyle.net/signout.php">Sign out</a></li>
				</ul>';

		else {

//the craziness begins!
if(isset($_POST['signin'])) {

	select_db($GLOBALS["s"], $GLOBALS["u"], $GLOBALS["p"], $GLOBALS["db"]);

   // check to see if username and password have been entered
   if (!$_POST['username']) {
      echo "enter a username. \n";
   }
   else $u_login = $_POST['username'];
   if (!$_POST['password']) {
      echo "enter a password. \n";
   }
   else $p_login = $_POST['password'];

   if ($u_login && $p_login) {
      $q = "SELECT `user_id`, `username`, `last` FROM `users` WHERE `username`='$u_login' AND `password`= MD5('$p_login')";
      $result = mysql_query($q);

      if ($row=mysql_fetch_array($result)) { // a match was made
         // start session
         $_SESSION['user_id']=$row[0];
         $_SESSION['username']=$row[1];
         $_SESSION['last_last']=$row[2];
         // save the time logged in as LAST, and previous last as LAST LAST
		 $now  = my_mktime();
		 $now_f = strftime('%G.%m.%d %H:%M',$now);
		 $update_lasts = "UPDATE `users` SET `last` = '$now_f', `last_last` = '$row[2]' WHERE `username` = '$u_login'";
		 mysql_query($update_lasts);
	 ob_start();
         header("Location: http://" . $_SERVER['HTTP_HOST']); 
         ob_end_flush(); // delete buffer
         exit();
      }
      // no match was made
      echo 'user does not exist, or bad password';

   }
   else // one of the data tests failed
      echo 'technical problem. try again.';
}

			$markup_nav .= '
				<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<ul>
					<li>Name: <input type="text" size="8" name="username" tabindex="1" /> </li>
					<li>Pass: <input type="password" size="7" name="password" tabindex="2" /> </li>
					<li><input type="submit" name="signin" value="Sign in" tabindex="3" /> </li>
				</ul>
				</form>';
//end craziness!
			$markup_nav .= '<ul><li><a href="http://iam.solostyle.net/login_woe.php" tabindex="9">Login woe?</a></li></ul>';
		}



$markup_nav .= '
        </div><!-- /end nav links -->';

$markup_content = '
	<div id="content">';

print $markup_head . $markup_nav . $markup_content;











}


// display left pane
function left_markup() {














$markup_left = 

'<div id="left">

		<h3>archives</h3>';

select_db($GLOBALS["s"], $GLOBALS["u"], $GLOBALS["p"], $GLOBALS["db"]);
	
// display a form to allow the user to choose a range of entries
$markup_left .= '<div>
	<form method="post" action="http://' . $_SERVER['HTTP_HOST'] . '/archive.php">';
$markup_left .= '<p>From ';
$markup_left .= list_months("start_month",$_SESSION['arch_start_month']);
$markup_left .= '&nbsp;';
$markup_left .= list_years("start_year",$_SESSION['arch_start_year']);
$markup_left .= '</p>';
$markup_left .= '<p>until ';
$markup_left .= list_months("end_month",$_SESSION['arch_end_month']);
$markup_left .= '&nbsp;';
$markup_left .= list_years("end_year",$_SESSION['arch_end_year']);
$markup_left .= '</p>';

// allow user to preview or view full
				
$markup_left .= '<p><input type="radio" name="view_typ" value="preview"';
		if ( ($_SESSION['arch_view_typ']=='preview') | (!isset($_SESSION['arch_view_typ'])) )
			$markup_left .= ' checked="checked"';
$markup_left .= ' />Preview &nbsp;
<input type="radio" name="view_typ" value="full"';
		if ($_SESSION['arch_view_typ']=='full')
			$markup_left .= ' checked="checked"';
$markup_left .= ' />Full &nbsp; 
	<input type="submit" name="submit" value="Go" /></p>
	</form></div>';


mysql_close();
	
$markup_left .= '
</div><!-- /end #left -->';

print $markup_left;











}


// display footer
function footer_markup() {




	 print

'<!--	<div id="footer">
		<p>Subscribe to my lame blog: <a href="http://iam.solostyle.net/atom.xml">Atom</a> | <a href="http://iam.solostyle.net/rss.xml">RSS</a></p>
	</div>
-->

	   </div><!-- /end .content -->

</div><!-- /end #page -->

</body>
</html>
';





}


// Make a blog entry url for a page
// 9 mar 09: created
function make_url($blog_id) {
	return "http://iam.solostyle.net/" . $blog_id;
}


// Show the tags for a blog entry
// 1 mar 09: created
function show_tags($blog_id) {
	$tags = get_tags($blog_id);
	$content = '<ul>';
	while ($tag = mysql_fetch_array($tags))
		$content .= '<li><a href="http://iam.solostyle.net/tag/'.$tag[0].'/">'.$tag[0].'</a></li>';
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

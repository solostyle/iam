<?php

//----------------------------------------------------------------------------
// Business Database functions -----------------------------------------------
// ---------------------------------------------------------------------------


// Connect to the database 
function select_db() {
    mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Couldn't connect!<br />");
    mysql_select_db(DB_NAME);
}


// Retrieve an array of ids and titles
// returns an array of data rows whose id's begin with $blog_id
// 5 jan 11: created
function rtrv_titles($blog_id, $lim=0) {

   $rtn_arr = array();
   $regex = '^' . $blog_id;
   $query = "SELECT `id`, `title` FROM `blog` WHERE `id` REGEXP '$regex' ORDER BY `time` DESC";
  if ($lim) $query .= " LIMIT $lim";

   $result = mysql_query($query);
   while ($entry = mysql_fetch_array($result)) {
         array_push($rtn_arr, $entry);
   }
   mysql_free_result($result);

   return $rtn_arr;
}


// Return the dates of the first and last blog entries
// 7 feb 09: created
// 5 jan 11: combined two functions into one
function blog_first_and_last_dates() {
	$dates = array();

	$query = "SELECT `time` FROM `blog` ORDER BY `time` ASC LIMIT 1";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	array_push($dates, $row[0]);

	$query = "SELECT `time` FROM `blog` ORDER BY `time` DESC LIMIT 1";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	array_push($dates, $row[0]);

	return $dates;
}


//$h = haystack, $n = needle
function strstrb($h,$n){
    return array_shift(explode($n,$h,2));
}


//----------------------------------------------------------------------------
// One-time Utilities --------------------------------------------------------
// ---------------------------------------------------------------------------

// Modify my blog_id again!
// Old format: 2008-08-13:what-a-day
// New format: 2008/08/13/what-a-day
function update_blog_id_again() {
	$table = 'blog_category'; // need to do all that start with "blog"
	$query = "SELECT `blog_id` FROM `" . $table . "`";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		$id = str_replace(':', '/', $row[0]);
		$query = "update `" . $table . "` set `blog_id` = '" . $id . "' where `blog_id` = '" . $row[0] . "'";
		print $query . '<br />';
		mysql_query($query);
	}
}

// Modify my blog_id
// Old format: tag:iam.solostyle.net,2008-08-13:/2008/08/what-a-day
// New format: 2008-08-13:what-a-day
// This is the natural key
function update_blog_id() {
	$table = 'blog_tags';
	$query = "SELECT `blog_id` FROM `" . $table . "` WHERE `blog_id` not in ('2006-03-02:next-door')";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		$loc = strrchr($row[0],"/");
		$loc = substr($loc,1);
		$date = strrchr($row[0],",");
		$date = substr($date,1,10);
		$id = $date . ":" . $loc;
		$query = "update `" . $table . "` set `blog_id` = '" . $id . "' where `blog_id` = '" . $row[0] . "'";
		print $query . '<br />';
		mysql_query($query);
	}
}


// Update the database time for all entries!
// 7 mar 09: created
function update_entry_time() {
	$query = "SELECT `id` FROM `blog` WHERE `time` = '0000-00-00 00:00:00'";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		$datepos = strpos($row[0],",");
		$date = substr($row[0],$datepos+1,10);
		$hr = rand(0,24);
		if ($hr<10)
			$hr = "0" . $hr;
		$mn = rand(0,59);
		$sc = rand(0,59);
		if ($mn<10)
			$mn = "0" . $mn;
		if ($sc<10)
			$sc = "0" . $sc;
		$date .= " " . $hr . ":" . $mn . ":" . $sc;
		$query = "UPDATE `blog` SET `time` = '" . $date . "' WHERE `id` = '" . $row[0] . "'";
		//print $query . '<br />';
		mysql_query($query);
	}
}

?>
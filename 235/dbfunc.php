<?php

//----------------------------------------------------------------------------
// Business Database functions -----------------------------------------------
// ---------------------------------------------------------------------------


// Connect to the database 
function select_db($server, $usr, $pw, $dbname) {
   mysql_connect($server,$usr,$pw) or die("Couldn't connect!<br />");
   mysql_select_db($dbname);
}


// Retrieve an array of entries
// returns an array of entry data rows
// whose id's begin with $blog_id
// 6 feb 09: changed the input parameters because we have one table now
// 19 sep 09: use regular expressions, bring back multiple rows up to $lim
function rtrv_entries($blog_id, $lim=50) {

	 $rtn_arr = array();
	 $regex = '^' . $blog_id;
	 $query = "SELECT * FROM `blog` WHERE `id` REGEXP '$regex' LIMIT $lim";

	 $result = mysql_query($query);
	 while ($entry = mysql_fetch_array($result)) {
	       array_push($rtn_arr, $entry);
	 }
	 mysql_free_result($result);

	 return $rtn_arr;
}


// Retrieve an array of entries given an array of tags
// returns an array of entry data rows using OR logic
// results have any of the requested tags
// 20 sep 09: created, right now does not handle $method
function rtrv_entries_by_tag($tag_arr, $method, $lim=50) {

	$rtn_arr = array();
	$id_arr = array();


	// retrieve entries, without duplicates
	$tag_list = "'".implode("','",$tag_arr)."'";
	$query = "SELECT DISTINCT b.`blog_id` FROM `blog` a, `blog_tag` b
	       WHERE a.`id` = b.`blog_id` AND b.`tag_nm` in (".$tag_list.")";
	$query .= " LIMIT $lim";
	$result = mysql_query($query);
	while ($id = mysql_fetch_array($result)) {
	      array_push($id_arr, $id["blog_id"]);
	}
	mysql_free_result($result);
	

	// retrieve all the entries for these blog ids
	$id_list = "'".implode("','",$id_arr)."'";
//print "id array is " . var_dump($id_arr) . "<br /><br />";
	$query = "SELECT * FROM `blog` WHERE `id` in (".$id_list.")";
//print "id list is " . $id_list;
	$result = mysql_query($query);
	while ($entry = mysql_fetch_array($result)) {
	      array_push($rtn_arr, $entry);
	}
	mysql_free_result($result);

	return $rtn_arr;
}


// Display one or many entries
// takes in an array of entry data rows
// returns the markup
// 19 sep 09: created
function show_entries($arr) {
	$rtnMarkup = '';
	// or i could return a json that consists of:
	// array of entries
	// php files to include
	// css files, js files to include (maybe)
	// other metadata
	// then I need javascript that can create the markup on the client
	if (count($arr)>3) {
	   for ($i=0;$i<count($arr);$i++) {
	       $rtnMarkup .= show_preview($arr[$i]);
	   }
	}
	else {
	   for ($i=0;$i<count($arr);$i++) {
	       $rtnMarkup .= make_entry($arr[$i]);
	   }
	}

	return $rtnMarkup;
}


// Retrieve all tags for an entry
// returns array of tags
// 1 mar 09;
function get_tags($blog_id) {
	$result = mysql_query("SELECT `tag_nm` FROM `blog_tag` WHERE `blog_id` = '$blog_id'");
	return $result;
}


// Show (preview) the new entries since last sign in
// 6 feb 09: simplified to use one table only
function show_new($last_time, $user_id) {

	$time_limit = "WHERE (`time` > '$last_time')";

	$getentries = "SELECT * FROM `blog` $time_limit ORDER BY `time` DESC";
	$entrieslist = mysql_query($getentries);

	$content = ""; //content accumulator
	//$num_entries = mysql_num_rows($entrieslist);
	while ($entryarray = mysql_fetch_array($entrieslist)) {
		$content .= show_preview($entryarray);
	}
	mysql_free_result($entrieslist);
	return $content;
}


// Display desired # of most recent post titles or entries
// 6 feb 09: simplified to use only one table (blog)
function preview_recent($lim) {
	$content = "";	// content accumulator
	$getentries = "SELECT * FROM `blog` ORDER BY `time` DESC LIMIT $lim";
	$entrieslist = mysql_query($getentries);
	while ($entryarray = mysql_fetch_array($entrieslist)) {
		$content .= show_preview($entryarray);
	}
	mysql_free_result($entrieslist);
	return $content;
}


//Display entries to edit or view
//6 feb 09: this function should simply show whatever you want to see
//	based on the starting date and the ending date
function show_entries_by_time($start, $end, $content_to_show) {
	$entrieslist = get_entries_by_time($start, $end);
	$content = "";
	while ($entryarray = mysql_fetch_array($entrieslist)) {
		switch ($content_to_show) {
			case "preview": //used by weekly archive.php
				$content .= show_preview($entryarray);
				break;
			case "republish":
				$content .= list_entry_republish($entryarray);
				break;
			case "full":
				$content .= make_entry($entryarray);
				break;
			case "tag":
				$content .= list_entry_tag($entryarray);
				break;
			case "categorize":
				$content .= list_entry_categorize($entryarray);
				break;
		}
	}
	mysql_free_result($entrieslist);
	return $content;
}

// Retrieves a list of entries within a given range
// 7 feb 09: created
function get_entries_by_time($start, $end) {
	$getentries = "
		SELECT * FROM `blog` 
		WHERE `time` >= '" . $start . "' 
		AND `time` <= '" . $end . "' 
		ORDER BY `time` DESC;
	";
	$entrieslist = mysql_query($getentries);
	return $entrieslist;
}


// Return the date of the first blog entry
// 7 feb 09: created
function blog_first_date() {
	$query = "SELECT `time` FROM `blog` ORDER BY `time` ASC LIMIT 1";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row[0];
}


// Return the date of the last blog entry
// 7 feb 09: created
function blog_last_date() {
	$query = "SELECT `time` FROM `blog` ORDER BY `time` DESC LIMIT 1";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row[0];
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
	$table = 'blog_tag';
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


//----------------------------------------------------------------------------
// Basic Database Functions --------------------------------------------------
// ---------------------------------------------------------------------------


// Delete an entry
// 6 feb 09: simplified function
function delete_record($blog_id) {
	mysql_query("DELETE FROM `blog` WHERE `id` = '$blog_id'");
}


// Update an entry
// 6 feb 09: changed the input parameters because we have one table now
function update_record($fields,$values,$id) {
	$sqlstatement = "UPDATE `blog` SET `$fields[0]` = '$values[0]'";
	for ($i=1; $i<=count($fields)-1; $i++)
		$sqlstatement .= ", `$fields[$i]` = '$values[$i]'";
	$sqlstatement .= " WHERE `$fields[0]` = '$id'";
	mysql_query($sqlstatement);
}


// Insert a Record
// takes a tablename and two arrays
function insert_record($tablename,$fields,$values) {
   if (count($fields) != count($values)) {
      print("Error: length of arrays are not equal.");
      print count($fields);
      print count($values);
   }
   else {
      $sqlstatement = "INSERT INTO `$tablename` (`$fields[0]`";
      for ($i=1; $i<=count($fields)-1; $i++)
         $sqlstatement .= ",`$fields[$i]`";
      $sqlstatement .= ") Values ('$values[0]'";
      for ($j=1; $j<=count($values)-1; $j++)
         $sqlstatement .= ",'$values[$j]'";
      $sqlstatement .= ");";
print $sqlstatement;
      mysql_query($sqlstatement);
     }
}


// Associate an entry to a tag
// note: write a function that deletes tags so that only
//       the new ones are associated to the blog entry
//       Maybe have a wrapper function loop through tags
//       and call this function for each tag.
// 10 feb 09: created
function assign_tag($blog_id, $tag_nm) {
	$af = array('blog_id','tag_nm');
	$av = array($blog_id,$tag_nm);
	insert_record('blog_tag',$af,$av);
}


// Remove association of entry to tag
// 15 mar 09: created
function unassign_tags($blog_id) {
	mysql_query("DELETE FROM `blog_tag` WHERE `blog_id` = '$blog_id'");
}

// Associate an entry to a category
// This function will delete any existing category association
// so that only one category can be assigned to a blog entry
// 10 feb 09: created
// 9 mar 09: modified to delete any existing category before adding
function assign_category($blog_id, $category_nm) {
	$del_q = "DELETE FROM `blog_category` WHERE `blog_id` = '$blog_id'";
	mysql_query($del_q);
	
	$af = array('blog_id','category_nm');
	$av = array($blog_id,$category_nm);
	insert_record('blog_category',$af,$av);
}

?>

<?php

//----------------------------------------------------------------------------
// Business Database functions -----------------------------------------------
// ---------------------------------------------------------------------------


// Connect to the database 
function select_db() {
    mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Couldn't connect!<br />");
    mysql_select_db(DB_NAME);
}


// Retrieve an article
// returns the first match of articles that start with $article_name
// 27 sep 09: created
function rtrv_article($article_name) {

	 $rtn_arr = array();
	 $regex = '^' . $article_name;
	 $query = "SELECT * FROM `articles` WHERE `id` REGEXP '$regex' LIMIT $lim";

	 $result = mysql_query($query);
	 while ($entry = mysql_fetch_array($result)) {
	       array_push($rtn_arr, $entry);
	 }
	 mysql_free_result($result);

	 // there should only be one article, so return the first result
	 return $rtn_arr[0];
}


// Retrieve an array of entries
// returns an array of entry data rows
// whose id's begin with $blog_id
// 6 feb 09: changed the input parameters because we have one table now
// 19 sep 09: use regular expressions, bring back multiple rows up to $lim
function rtrv_entries($blog_id, $lim=0) {

    $rtn_arr = array();
    $where = ($blog_id)? " WHERE `id` REGEXP '^$blog_id'" : '';    
    $query = "SELECT * FROM `blog`" . $where . " ORDER BY `time` DESC";

    if ($lim) $query .= " LIMIT $lim";

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
function rtrv_entries_by_tag($tag_arr, $method, $lim=0) {

    $rtn_arr = array();
    $id_arr = array();

    // retrieve entries, without duplicates
    $tag_list = "'".implode("','",$tag_arr)."'";
    $query = "SELECT DISTINCT b.`blog_id` FROM `blog` a, `blog_tags` b
            WHERE a.`id` = b.`blog_id` AND b.`tag_nm` in (".$tag_list.")";
    $query .= ($lim)? " LIMIT $lim" : "";
    $result = mysql_query($query);
    while ($id = mysql_fetch_array($result)) {
        array_push($id_arr, $id["blog_id"]);
    }
    mysql_free_result($result);

    // retrieve all the entries for these blog ids
    $id_list = "'".implode("','",$id_arr)."'";
    //print "id array is " . var_dump($id_arr) . "<br /><br />";
    $query = "SELECT * FROM `blog` WHERE `id` in (".$id_list.") ORDER BY `time` DESC";
    //print "id list is " . $id_list;
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
function rtrv_ids_by_tag($tag_arr, $method='', $lim=0) {

    $id_arr = array();

    // retrieve entries, without duplicates
    $tag_list = "'".implode("','",$tag_arr)."'";
    $query = "SELECT DISTINCT b.`blog_id` FROM `blog` a, `blog_tags` b
            WHERE a.`id` = b.`blog_id` AND b.`tag_nm` in (".$tag_list.")";
    $query .= ($lim)? " LIMIT $lim" : "";
    $result = mysql_query($query);
    while ($id = mysql_fetch_array($result)) {
        array_push($id_arr, $id["blog_id"]);
    }
    mysql_free_result($result);

    return $id_arr;
}

// Retrieve an array of entries given a category
// returns an array of entry data rows using OR logic
// results have any of the requested tags
// 20 sep 09: created, right now does not handle $method
function rtrv_ids_by_category($cat, $lim=0) {

    $id_arr = array();

    // retrieve entries, without duplicates
    $query = "SELECT DISTINCT b.`blog_id` FROM `blog` a, `blog_categories` b
            WHERE a.`id` = b.`blog_id` AND b.`category_nm` = '".$cat."'";
    $query .= ($lim)? " LIMIT $lim" : "";
    $result = mysql_query($query);
    while ($id = mysql_fetch_array($result)) {
        array_push($id_arr, $id["blog_id"]);
    }
    mysql_free_result($result);

    return $id_arr;
}


// 06 feb 11: created
function get_category($blogid) {

    // retrieve entries, without duplicates
    $query = "SELECT `category_nm` FROM `blog_categories`
							WHERE `blog_id` = '".$blogid."'";
//print 'the query that is failing is ' . $query;
    $result = mysql_query($query);
    if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_array($result);
		}
    mysql_free_result($result);

    return $row[0];
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


// Retrieve all tags for an entry
// returns array of tags
// 1 mar 09;
function rtrv_tags($blog_id) {

	$tags_arr = array();

	$result = mysql_query("SELECT `tag_nm` FROM `blog_tags` WHERE `blog_id` = '$blog_id'");

	while ($tag = mysql_fetch_array($result))
	      array_push($tags_arr, $tag[0]);

	mysql_free_result($result);

	return $tags_arr;
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


// returns a multidimensional array of 
// year->count, year->months, month->count, month->id->title
// 6 jan 11: created
function create_archive_nav_array() {
	$start_and_end_dates = blog_first_and_last_dates();
	$start_date = $start_and_end_dates[0];
	$end_date = $start_and_end_dates[1];
	$start_year = strftime("%Y", strtotime($start_date));
	$end_year = strftime("%Y", strtotime($end_date));
	$titles_counts_array = array();

  	// for expand/collapse
	$now  = my_time();
	$now_year = date('Y', $now);
	$now_month= date('m', $now);

	// build the array
	for($y=$end_year;$y>=$start_year;$y--) {
		$num_rows_in_year = count(rtrv_titles($y));

		if ($num_rows_in_year) {

			$titles_counts_array[$y] = array();
			$titles_counts_array[$y][0] = $num_rows_in_year;

			for($m='12';$m>='1';$m--) {
				if ($m<='9') {
				  $m = '0' . $m;
				}
				$ids_titles = rtrv_titles($y . '/' . $m);
				$num_rows_in_month = count($ids_titles);

				if ($num_rows_in_month) {

					$titles_counts_array[$y][$m] = array();
					$titles_counts_array[$y][$m][0] = $num_rows_in_month;

					foreach($ids_titles as $id_title) {
						$id = $id_title[0];
						$title = $id_title[1];
						$titles_counts_array[$y][$m][$id] = array();
						$titles_counts_array[$y][$m][$id]['title'] = $title;
					}
				}
			}
		}
	}
	
	// add display tokens
	$titles_counts_array[$now_year]['display'] = "show";
	$titles_counts_array[$now_year][$now_month]['display'] = "show";

	return $titles_counts_array;
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
// 30 nov 11: when id changes, update `blog`, `blog_categories` and `blog_tags` tables.
// 25 dec 11: duh, didn't i realize I'd need to use this to update tables other than blog? so now there's
// 		a ocnditional just for if we update `blog` so that we update categories and tags
//		AND we pass in the actual tablename!
function update_record($tablename, $fields, $values, $id) {
	$query1 = "UPDATE `$tablename` SET `$fields[0]` = '$values[0]'";
	for ($i=1; $i<=count($fields)-1; $i++)
		$query1 .= ", `$fields[$i]` = '$values[$i]'";
	$query1 .= " WHERE `$fields[0]` = '$id'";

	$result = mysql_query($query1);
	
	if ($tablename == 'blog') {
		$query2 = "UPDATE `blog_categories` SET `blog_id` = '$values[0]'
					WHERE `blog_id` = '$id'";
		mysql_query($query2);
	
		// still need to update `blog_tags`, but we're not using the table yet
	}

	return ($result) ? true : false;
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
//print $sqlstatement;
      $result = mysql_query($sqlstatement);
     }

	return ($result) ? true : false;
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
	insert_record('blog_tags',$af,$av);
}


// Remove association of entry to tag
// 15 mar 09: created
function unassign_tags($blog_id) {
	mysql_query("DELETE FROM `blog_tags` WHERE `blog_id` = '$blog_id'");
}

// Associate an entry to a category
// This function will delete any existing category association
// so that only one category can be assigned to a blog entry
// 10 feb 09: created
// 9 mar 09: modified to delete any existing category before adding
function assign_category($blog_id, $category_nm) {
	$del_q = "DELETE FROM `blog_categories` WHERE `blog_id` = '$blog_id'";
	mysql_query($del_q);
	
	$af = array('blog_id','category_nm');
	$av = array($blog_id,$category_nm);
	insert_record('blog_categories',$af,$av);
}

?>
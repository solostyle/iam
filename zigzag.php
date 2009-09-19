<?php

include 'inc/dbclasses.inc';

class Redirect {

	// declare global variables
	private $redirect_page = 'zigzag.php';
	private $main_page = 'index.php';
	private $root;
	private $uri;
	public $uri_array;
	public $error_page;
	public $login_page = 'signin.php';
	private $exec_script;

	// Constructor
	function __construct() {
		$request_uri = $_SERVER['REQUEST_URI'];
		$this->uri = strip_tags($request_uri);
		$this->uri_array = explode("/",$this->uri);

		array_shift($this->uri_array);	// first one is empty anyway

		$this->exec_script = $_SERVER['SCRIPT_FILENAME'];
		$this->root = $_SERVER['DOCUMENT_ROOT'];
		$this->error_page = $this->root . '/error404.php';
		$this->login_page = $this->root . '/login.php';
	}

	//---------------------------------------------------------
	// Basic Redirects
	//---------------------------------------------------------
	public function basic_redirect() {

		// declare a $result variable for the file name to redirect to
		$result = 0;

		// security first
		if(strlen($this->uri)>50)
			$result = $this->error_page;

		// 1. when to go to index.php
		if (	($this->uri=="/".$this->redirect_page)	// requested the redirect page
				or
				(	(empty($this->uri_array))			// requested nothing
					or
					(	(count($this->uri_array)==1)		// requested something...
						and
						(empty($this->uri_array[0]))		// ...that is empty
					)
				)
			)
			$result = $this->main_page;

		// 2. if it's a "real" file, go there
		if(file_exists($this->root.$this->uri)				// if the file exists, and it is
			and ($this->root.$this->uri!=$this->exec_script)	// not the currently executing script
			and ($this->uri!="/"))					// and it is not empty
			$result = $this->root.$this->uri;

		return $result;
	} // basic redirect


	//--------------------------------------------------------
	// Advanced Redirects
	//--------------------------------------------------------

	//Check if anything in the Database matches the request
	// /news, /about, /biographies, /contact
	// /events, /events/event_name
	// /members/profile, /members/edit, /members
	// /admin/events, /admin/events/event_name, /admin/news

	public function advanced_redirect() {
		$result = $this->rtrv_displ_func($this->uri_array[0]);
		return $result;
	}

	public function rtrv_displ_func($prefix) {
		// call a retriever
		$select_str = "`displ_func`";
		$where_str = "`uri_prefix` = '$prefix'";
		$uri_displ_func_obj = new Uri_Displ_Func();
		$result = $uri_displ_func_obj->rtrv($select_str, $where_str);

		//returns a datatable. need to save off the first thing in the first row

		if (empty($result[0][0])) return "displ_error_page";
		else return $result[0][0];
		//maybe insert null row with error page function?
		//return $result[0][0] || "displ_error_page";
	}

// members	check_db_members
// news 	displ_news
// events 	check_db_events
// admin 	check_db_admin
// about 	displ_about
// bios 	displ_bios
// contact	displ_contact
// <>		displ_error_page

	// Maybe at this point, if they are a member,
	// create a member object immediately.
	// That way I can access it to display the info.
	public function check_db_members() {
		// check session to see if logged in
		if (isset($_SESSION['mbr_nbr'])){
			// continue
		}
		else {
			header("Location :" . $this->login_page);
			exit();
		}

		//there could be a trailing slash
		//if so, treat it the same as if there is no trailing slash
		//if there's something after the view/ or edit/
		//throw error and suggest the view/ or edit/ page.

		//if array is empty
		//display the main members page
		//has links to new items since last login
		if (	empty($this->uri_array[0])	// trailing slash with nothing after it
			or
			count($this->uri_array) == 0	// no trailing slash
		)
			$result = "displ_member";
		else $result = rtrv_mbr_displ_func($this->uri_array[0]);

		if (empty($result)) return "displ_error_page";
		else return $result;
		// return $result || "displ_error_page";
	}


	private function rtrv_mbr_displ_func($option) {
		// call a retriever
		$select_str = "`displ_func`";
		$where_str = "`uri_option` = '$option'";
		//$uri_mbr_displ_func_obj = new Uri_
		$result = Uri_Mbr_Displ_Func::rtrv($select_str, $where_str);

		if (empty($result)) return "displ_error_page";
		else return $result;
		//maybe insert null row with error page function?
		// return $result || "displ_error_page";
	}


//view	displ_mbr_view
//edit	displ_mbr_edit
	private function displ_mbr_view() {
		//use the login information to display
		//use global variables
		//1. retrieve information from table
		//2. format display divs--maybe just return an object and have javascript interpret it
		//3. return the page--no, just return the object
		rtrv_member_info();
		$content = format_member_view();
		return $content;
		include("add.php");
	}


	private function displ_mbr_edit() {
		include("edit.php");
	}


	private function rtrv_member_info() {
		if (isset($_SESSION['mbr_email'])) {
			$mbr_email = $_SESSION['mbr_email'];
		}
		else
			exit();
		// retrieve names
		// retrieve phone number
		// retrieve address
		// retrieve email
		// retrieve member type, status, payment (amt, date)
		mmbr("rtrv",$mbr_email);
	}


} // class Redirect


$rdr = new Redirect;
//echo 'prefix: ' . $rdr->uri_array[0] . '<br />';
$basic_redirect_page = $rdr->basic_redirect();
//echo 'bas rdr: ' . $basic_redirect_page . '<br />';

if($basic_redirect_page)
	include($basic_redirect_page);
else {
	$advanced_redirect_page = $rdr->advanced_redirect();
	//echo 'adv rdr: ' . $advanced_redirect_page;
	$_SESSION['mbr_nbr'] = 1;
	$rdr->$advanced_redirect_page();
}

?>


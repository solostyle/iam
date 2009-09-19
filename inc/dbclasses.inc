<?php

//include 'inc/vars.inc';

// GLOBAL FUNCTIONS


$server = "mysql.solostyle.net";
$usr = "solostyle";
$pw = "qas??wed";

// Connect to the database 
function db_connect($dbname) {
	global $server, $usr, $pw;
	mysql_connect($server,$usr,$pw) or die("Couldn't connect!<br />");
	mysql_select_db($dbname);
}

// Table classes
// One per table
class Default_Table {
	protected $tablename;
	protected $dbname;
	protected $fieldlist;
	protected $data_array;
	protected $numrows;
	protected $errors;

	protected function __set($name, $value) {
		$this->$name = $value;
	}

	protected function __get($name) {
		return $this->$name;
	}
	
	public function __isset($name) {
		return isset($this->$name);
    	}
	    
	public function __unset($name) {
		unset($this->$name);
    	}

	protected function __construct() {
		$this->tablename = 'default';
		$this->dbname = 'default';
		$this->fieldlist = array('column1','column2','column3');
		$this->fieldlist['column1'] = array('pkey' => 'y');
	} // constructor

	public function rtrv($select_str, $where_str) {
		$this->data_array = array();
		$this->numrows = 0;
		
		global $dbconnect, $query;
		$dbconnect = db_connect($this->dbname);// or trigger_error("SQL",E_USER_ERROR);
		if (empty($where_str)) $where_str = NULL;
		else $where_str = "WHERE $where_str";

		$query = "SELECT count(*) FROM $this->tablename $where_str";
		$result = mysql_query($query) or trigger_error("SQL",E_USER_ERROR);
		$query_data = mysql_fetch_row($result);
		$this->numrows = $query_data[0];

		if ($this->numrows <= 0) return;
		else {
			$query = "SELECT $select_str FROM $this->tablename $where_str";
			$result = mysql_query($query) or trigger_error("SQL",E_USER_ERROR);
			while ($row = mysql_fetch_array($result))
				$this->data_array[] = $row;

			mysql_free_result($result);
			
			return $this->data_array;
		}
	} // retrieve

	protected function add($fieldarray) {
		$this->errors = array();

		global $dbc, $q;
		$dbc = db_connect($this->dbname) or trigger_error("SQL",E_USER_ERROR);

		$fieldlist = $this->fieldlist;
		foreach ($fieldarray as $field => $fieldvalue) {
			if (!in_array($field, $fieldlist)) unset($fieldarray[$field]);
		}

		$q = "INSERT INTO $this->tablename SET ";
		foreach($fieldarray as $item => $value) {
			$q .= "$item='$value', ";
		}

		$q = rtrim($q, ', ');

		$result = @mysql_query($q, $dbc);
		if (mysql_errno() <> 0) 
			if (mysql_errno() == 1062)
				$this->errors[] = "Error: Duplicate record";
			else trigger_error("SQL",E_USER_ERROR);

		return;
	} // add record

	protected function updt($fieldarray) {
		$this->error = array();

		global $dbc, $q;
		$dbc = db_connect($this->dbname) or trigger_error("SQL",E_USER_ERROR);

		$fieldlist = $this->fieldlist;
		foreach ($fieldarray as $field => $fieldvalue) {
			if (!in_array($field, $fieldlist)) unset($fieldarray[$field]);
		}

		$where = NULL;
		$update = NULL;
		foreach ($fieldarray as $item => $value) {
			if (isset($fieldlist[$item]['pkey']))
				$where .= "$item='$value' AND ";
			else $update .= "$item='$value', ";
		}

		$where = rtrim($where, " AND ");
		$update = rtrim($update, ", ");

		$q = "UPDATE $this->tablename SET $update WHERE $where";
		$result = mysql_query($q, $dbc) or trigger_error("SQL",E_USER_ERROR);

		return;
	} // update record

	protected function del($fieldarray) {

		$this->errors = array();
		global $dbconnect, $query;
		$dbconnect = db_connect($this->dbname) or trigger_error("SQL", E_USER_ERROR);
		$fieldlist = $this->fieldlist;
		$where  = NULL;
	  
		foreach ($fieldarray as $item => $value) {
			if (isset($fieldlist[$item]['pkey']))
				$where .= "$item='$value' AND ";
		}

		$where = rtrim($where, " AND ");

		$query = "DELETE FROM $this->tablename WHERE $where";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL",E_USER_ERROR);

		return;
	} // delete record

} // end class


class Member_Login extends Default_Table {
	function __construct() {
		$this->tablename       = 'member_login';
		$this->dbname          = 'bkm_website';
		$this->fieldlist       = array('mbr_nbr', 'email', 'password','last_login','current_login','hk_updt_ts');
		
		$this->fieldlist['mbr_nbr'] = array('pkey' => 'y');
	} // constructor
	
} // end class


class Uri_Displ_Func extends Default_Table {
	function __construct() {
		$this->tablename       = 'uri_displ_func';
		$this->dbname          = 'bkm_website';
		$this->fieldlist       = array('uri_prefix', 'displ_func', 'status');
		
		$this->fieldlist['uri_prefix'] = array('pkey' => 'y');
	} // constructor
	
} // end class


class Uri_Mbr_Displ_Func extends Default_Table {
	function __construct() {
		$this->tablename       = 'uri_mbr_displ_func';
		$this->dbname          = 'bkm_website';
		$this->fieldlist       = array('uri_opt', 'displ_func', 'status');
		
		$this->fieldlist['uri_opt'] = array('pkey' => 'y');
	} // constructor
	
} // end class


class Member_Names extends Default_Table {
	function __construct() {
		$this->tablename       = 'member_names';
		$this->dbname          = 'bkm_website';
		$this->fieldlist       = array('mbr_nbr', 'first_name', 'last_name', 'relation', 'hk_updt_ts');
		
		$this->fieldlist['mbr_nbr'] = array('pkey' => 'y');
		$this->fieldlist['first_name'] = array('pkey' => 'y');
		$this->fieldlist['last_name'] = array('pkey' => 'y');
		
	} // constructor
	
} // end class


class Member_Contact extends Default_Table {
	function __construct() {
		$this->tablename       = 'member_contact';
		$this->dbname          = 'bkm_website';
		$this->fieldlist       = array('mbr_nbr', 'street_address', 'city', 'state', 'zip', 'phone', 'hk_updt_ts');
		
		$this->fieldlist['mbr_nbr'] = array('pkey' => 'y');
		
	} // constructor
	
} // end class


class Membership_Payment extends Default_Table {
	function __construct() {
		$this->tablename       = 'membership_payment';
		$this->dbname          = 'bkm_website';
		$this->fieldlist       = array('mbr_nbr', 'seq', 'paid_amt', 'paid_date', 'paid_method', 'receipt_nbr', 'hk_updt_ts');
		
		$this->fieldlist['mbr_nbr'] = array('pkey' => 'y');
		$this->fieldlist['seq'] = array('pkey' => 'y');
		
	} // constructor
	
} // end class


class Membership_Details extends Default_Table {
	function __construct() {
		$this->tablename       = 'membership_details';
		$this->dbname          = 'bkm_website';
		$this->fieldlist       = array('mbr_nbr', 'mbr_type', 'mbr_status', 'hk_updt_ts');
		
		$this->fieldlist['mbr_nbr'] = array('pkey' => 'y');
		
	} // constructor
	
} // end class

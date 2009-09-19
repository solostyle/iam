<?php

class Default_Table {
	var $tablename;
	var $dbname;
	var $fieldlist;
	var $data_array;
	var $numrows;
	var $errors;

//	function Default_Table() {
	function __construct() {
		$this->tablename = 'default';
		$this->dbname = 'default';
		$this->fieldlist = array('column1','column2','column3');
		$this->fieldlist['column1'] = array('pkey' => 'y');
	} // constructor

	function getData($select, $where) {
		$this->data_array = array();
		$this->numrows = 0;
		
		global $dbconnect, $query;
		$dbconnect = db_connect($this->dbname) or trigger_error("SQL",E_USER_ERROR);
		if (empty($where)) $where_str = NULL;
		else $where_str = "WHERE $where";

		$query = "SELECT count(*) FROM $this->tablename $where_str";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL",E_USER_ERROR);
		$query_data = mysql_fetch_row($result);
		$this->numrows = $query_data[0];

		if ($this->numrows <= 0) return;
		else {
			$query = "SELECT $select_str FROM $this->tablename $where_str";
			$result = mysql_query($query, $dbconnect) or trigger_error("SQL",E_USER_ERROR);
			while ($row = mysql_fetch_assoc($result))
				$this->data_array[] = $row;

			mysql_free_result($result);

			return $this->data_array;
		}
	} // getData

	function insertRecord($fieldarray) {
		$this->errors = array();

		global $dbc, $q;
		$dbc = db_connect($this->dbname) or trigger_error("SQL",E_USER_ERROR);

		$fieldlist = $this->fieldlist;
		foreach ($fieldarray as $field => $fieldvalue) {
			if (!in_array($field, $fieldlist))
				unset ($fieldarray[$field]);
		}

		$q = "INSERT INTO $this->tablename SET ";
		foreach($fieldarray as $item => $value) 
			$q .= "$item='$value', ";

		$q = rtrim($q, ', ');

		$result = @mysql_query($q, $dbc);
		if (mysql_errno() <> 0) 
			if (mysql_errno() == 1062)
				$this->errors[] = "Error: Duplicate record";
			else trigger_error("SQL",E_USER_ERROR);

		return;
	} // insertRecord

	function updateRecord($fieldarray) {
		$this->error = array();

		global $dbc, $q;
		$dbc = db_connect($this->dbname) or trigger_error("SQL",E_USER_ERROR);

		$fieldlist = $this->fieldlist;
		foreach ($fieldarray as $field => $fieldvalue)
			if (!in_array($field, $fieldlist)
				unset($fieldarray[$field]);

		$where = NULL;
		$update = NULL;
		foreach ($fieldarray as $item => $value)
			if (isset($fieldlist[$item]['pkey']))
				$where .= "$item='$value' AND ";
			else $update .= "$item='$value', ";

		$where = rtrim($where, " AND ");
		$update = rtrim($update, ", ");

		$q = "UPDATE $this->tablename SET $update WHERE $where";
		$result = mysql_query($q, $dbc) or trigger_error("SQL",E_USER_ERROR);

		return;
	} // updateRecord

	function deleteRecord ($fieldarray) {

		$this->errors = array();
		global $dbconnect, $query;
		$dbconnect = db_connect($this->dbname) or trigger_error("SQL", E_USER_ERROR);
		$fieldlist = $this->fieldlist;
		$where  = NULL;
	  
		foreach ($fieldarray as $item => $value)
			if (isset($fieldlist[$item]['pkey']))
				$where .= "$item='$value' AND ";

		$where = rtrim($where, " AND ");

		$query = "DELETE FROM $this->tablename WHERE $where";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL",E_USER_ERROR);

		return;
	} // deleteRecord
}// Default Table class

// example class for a datatable
class Member_Login extends Default_Table {
	function Member_Login() {
		$this->tablename       = 'member_login';
		$this->dbname          = 'bkm_website';
		$this->fieldlist       = array('mbr_nbr', 'email', 'password','last_login','current_login','hk_updt_ts');
		
		$this->fieldlist['mbr_nbr'] = array('pkey' => 'y');
	} // constructor
	
} // end class


// The maintain class for the Member object
// Takes in a Member object and extracts information
// to create the MMember object. The values in this
// maintain object can be used to update the database
// directly.
// Should the constructor take in a Member object?
class MMember {

	var $login;
	var $details;
	var $contact;
	var $names;
	var $payments;
	
	//create a member object when someone logs in
	//by retrieving all information using their
	//email address
	function __construct(Member $mbrObj) {
	
		//store login stuff here
		$this->login = $mbrObj->login;
		//doesn't $login need hk_updt_ts?
		
		$this->details = $mbrObj->details;
		$this->contact = $mbrObj->contact;
		$this->names = $mbrObj->names;
		//might need to unpack names and payments
		$this->payments = $mbrObj->payments;
	}

} // MMember class
	
	
//object class for a member
class Member {

	//define constructor to create a member object given pkey


	//define set and get methods for each member attribute


}


$dbo = new Member_Login;

$where = "`email` = '1style@gmail.com'";

$data = $dbo->getData($where);

// don't yet have this function getErrors
//$errors = $dbo->getErrors();

if (!empty($errors)) {
	// deal with error messages
} // if

foreach ($data as $row) {
	foreach ($row as $field => $value) {
		print 'field: ' . $field . ' value: ' . $value;
	}
}// foreach


//We have insert, update, and delete functions.
//But how should I organize the data to be inserted, etc?
//I could create an object that contains the $fieldarray

$fieldarray = $dbo->insertRecord($fieldarray);
$errors = $dbo->getErrors();

$fieldarray = $dbo->updateRecord($fieldarray);
$errors = $dbo->getErrors();

$fieldarray = $dbo->deleteRecord($fieldarray);
$errors = $dbo->getErrors();

//Like so:

$mbrObj = new Member(;
$login_info = $mbrObj->getLogin();



?>


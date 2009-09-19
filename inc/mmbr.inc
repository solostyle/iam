<?php

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

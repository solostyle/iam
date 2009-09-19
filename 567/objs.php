<?php

class Name {
	private $first, $last;
	protected function __set($name, $value) {
		echo "setting $name to $value <br />";
		$this->$name = $value;
	}
	protected function __get($name) {
		echo "getting $name <br />";
		return $this->$name;
	}
}

class Payment {
	private $amount, $date, $method, $receipt_nbr;
	protected function __set($name, $value) {
		echo "setting $name to $value <br />";
		$this->$name = $value;
	}
	protected function __get($name) {
		echo "getting $name <br />";
		return $this->$name;
	}
}

class Member {
    /**  Location for overloaded data.  */
	private $func, $email, $password,
		$last_login, $current_login,
		$street, $city, $state, $zip,
		$phone, $type, $status;
	// each of these is an array of objects
	// restrict them such that only name and payment objects can be inserted
	private $names = array();
	private $payments = array();

	public function addName($nameObj) {
		array_push($this->names, $nameObj);
	}
	public function addPayment($paymentObj) {
		array_push($this->payments, $paymentObj);
	}
	protected function __set($name, $value) {
		echo "setting $name to $value <br />";
		$this->$name = $value;
	}
	protected function __get($name) {
		echo "getting $name <br />";
		return $this->$name;
	}

	public function __isset($name) {
		echo "Is '$name' set?\n";
		return isset($this->$name);
    	}
    
	public function __unset($name) {
		echo "Unsetting '$name'\n";
		unset($this->$name);
    	}
}

$mbr = new Member;
$mbr->email = '1style@gmail.com';
$mbr->password = md5('remember');
$mbr->func = 'none';
echo "<br /><br />";

var_dump(isset($mbr->func));
unset($mbr->func);
var_dump(isset($mbr->func));
echo "<br />";

echo $mbr->email;
echo $mbr->password;
echo $mbr->func;
echo "<br /><br />";

$name = new Name;
$name->first = 'mark';
$name->last = 'rzeznik';

$name2 = new Name;
$name2->first = 'merk';
$name2->last = 'rzaznik';

$mbr->addName($name);
$mbr->addName($name2);
echo $mbr->names[0]->first;
echo $mbr->names[0]->last;
echo $mbr->names[1]->first;
echo $mbr->names[1]->last;

$payment1 = new Payment;
$payment1->amount = "$50";
$payment1->method = "tickets2events";
$payment1->date = "4/13/09";

$mbr->addPayment($payment1);
echo $mbr->payments[0]->amount;
echo $mbr->payments[0]->method;
echo $mbr->payments[0]->receipt_nbr;
echo $mbr->payments[0]->date;

?>

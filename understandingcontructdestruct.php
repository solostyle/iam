<?php
class MyDestructableClass {
   function __construct() {
       print "In constructor\n";
       $this->name = "MyDestructableClass";
   }

   function __destruct() {
       print "Destroying " . $this->name . "\n";
   }
}

$obj = new MyDestructableClass();

class foo {
    protected $mam = 'mamam';
    function __construct() {
        echo 'foo ';
    }
}
class bar extends foo {
    function __construct() {
        parent:: __construct(); // use parent method to call parent class
        echo 'bar ';
    }
}
class foobar extends bar {
    function __construct() {
        parent:: __construct();
        echo 'foobar';
    }
}
$show = new foobar();
?>
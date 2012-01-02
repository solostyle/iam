<?php
class Model extends SQLQuery {
	protected $_model;

	function __construct() {
		
		global $inflect;

		$this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		$this->_limit = PAGINATE_LIMIT;
		$this->_model = get_class($this);
		$this->_table = strtolower($inflect->pluralize($this->_model));
		$this->_backupTable = 'deleted_'.$this->_table; // added by archana
		if (!isset($this->abstract)) {
			$this->_describe();
		}
	}

	function __destruct() {
	}
}

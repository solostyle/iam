<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `member` (
	`memberid` int(11) NOT NULL auto_increment,
	`mbr_nbr` INT NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL, PRIMARY KEY  (`memberid`)) ENGINE=MyISAM;
*/

/**
* <b>Member</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0e / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=Member&attributeList=array+%28%0A++0+%3D%3E+%27mbr_nbr%27%2C%0A++1+%3D%3E+%27email%27%2C%0A++2+%3D%3E+%27password%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27INT%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
include_once('class.pog_base.php');
class Member extends POG_Base
{
	public $memberId = '';

	/**
	 * @var INT
	 */
	public $mbr_nbr;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $email;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $password;
	
	public $pog_attribute_type = array(
		"memberId" => array('db_attributes' => array("NUMERIC", "INT")),
		"mbr_nbr" => array('db_attributes' => array("NUMERIC", "INT")),
		"email" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"password" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		);
	public $pog_query;
	
	
	/**
	* Getter for some private attributes
	* @return mixed $attribute
	*/
	public function __get($attribute)
	{
		if (isset($this->{"_".$attribute}))
		{
			return $this->{"_".$attribute};
		}
		else
		{
			return false;
		}
	}
	
	function Member($mbr_nbr='', $email='', $password='')
	{
		$this->mbr_nbr = $mbr_nbr;
		$this->email = $email;
		$this->password = $password;
	}
	
	
	/**
	* Gets object from database
	* @param integer $memberId 
	* @return object $Member
	*/
	function Get($memberId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `member` where `memberid`='".intval($memberId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->memberId = $row['memberid'];
			$this->mbr_nbr = $this->Unescape($row['mbr_nbr']);
			$this->email = $this->Unescape($row['email']);
			$this->password = $this->Unescape($row['password']);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $memberList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `member` ";
		$memberList = Array();
		if (sizeof($fcv_array) > 0)
		{
			$this->pog_query .= " where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$this->pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
					{
						$this->pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						if ($GLOBALS['configuration']['db_encoding'] == 1)
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
							$this->pog_query .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
						}
						else
						{
							$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
							$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
					else
					{
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
					}
				}
			}
		}
		if ($sortBy != '')
		{
			if (isset($this->pog_attribute_type[$sortBy]['db_attributes']) && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'SET')
			{
				if ($GLOBALS['configuration']['db_encoding'] == 1)
				{
					$sortBy = "BASE64_DECODE($sortBy) ";
				}
				else
				{
					$sortBy = "$sortBy ";
				}
			}
			else
			{
				$sortBy = "$sortBy ";
			}
		}
		else
		{
			$sortBy = "memberid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$member = new $thisObjectName();
			$member->memberId = $row['memberid'];
			$member->mbr_nbr = $this->Unescape($row['mbr_nbr']);
			$member->email = $this->Unescape($row['email']);
			$member->password = $this->Unescape($row['password']);
			$memberList[] = $member;
		}
		return $memberList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $memberId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `memberid` from `member` where `memberid`='".$this->memberId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `member` set 
			`mbr_nbr`='".$this->Escape($this->mbr_nbr)."', 
			`email`='".$this->Escape($this->email)."', 
			`password`='".$this->Escape($this->password)."' where `memberid`='".$this->memberId."'";
		}
		else
		{
			$this->pog_query = "insert into `member` (`mbr_nbr`, `email`, `password` ) values (
			'".$this->Escape($this->mbr_nbr)."', 
			'".$this->Escape($this->email)."', 
			'".$this->Escape($this->password)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->memberId == "")
		{
			$this->memberId = $insertId;
		}
		return $this->memberId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $memberId
	*/
	function SaveNew()
	{
		$this->memberId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `member` where `memberid`='".$this->memberId."'";
		return Database::NonQuery($this->pog_query, $connection);
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array)
	{
		if (sizeof($fcv_array) > 0)
		{
			$connection = Database::Connect();
			$pog_query = "delete from `member` where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) !== 1)
					{
						$pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$this->Escape($fcv_array[$i][2])."'";
					}
					else
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
					}
				}
			}
			return Database::NonQuery($pog_query, $connection);
		}
	}
}
?>

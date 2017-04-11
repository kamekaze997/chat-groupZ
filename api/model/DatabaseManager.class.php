<?php
require_once('config.php');
class DatabaseManager{

	/**
	 * Administrator for the database
	 * @var [Object]
	 */
	private $link;


	function __construct($settings){

		$this->link = $this->connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD);
		$this->link = $this->selectDB($this->link);//force to create the db if not found
		$this->close($this->link);
	}

	public function getLink(){ return $this->link; }

	public function executeSQL($sqlString)
	{
		if(!$sqlString or $sqlString==="") throw new Exception("Empty query.", 1);
		
		$sqlString = strtoupper($sqlString);
		$this->softLink = $this->connect(TESTING_USER,TESTING_PASSWORD);
		$this->softLink = $this->selectDB($this->softLink,false);

		$sqlString = str_replace(";","",$sqlString);
		$sqlString = strtoupper($sqlString);
		if($this->createTemporalTables())
		{
			$sqlString = $this->fakeTheQueryWithTemporal($sqlString);
			$cursor = mysqli_query($this->softLink, $sqlString);
			if($cursor)
			{
				if($this->isSelect($sqlString)) $this->fetchToHTML($cursor);
				else if($this->isShow($sqlString)) $this->fetchToHTML($cursor);
				else $this->output($this->getSuccessMessage());

				$this->close($this->softLink);

				return true;
			}
			else{
				throw new Exception("SQL Error: ".mysqli_error($this->softLink),1);
				return false;
			}
		}

	}

	public function connect()
	{
		// Connect to MySQL
		$link = mysqli_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD);
		if (!$link) {
		    throw new Exception('Could not connect: ' . mysqli_error($link), 1);
		}

		return $link;
	}

	private function selectDB($link)
	{
		// Make my_db the current database
		$db_selected = mysqli_select_db($link,MYSQL_DATABASE);
		if (!$db_selected) 
		{
			return $link;
		}
		else if(!$db_selected)
		{
			throw new Exception('could not select db',1);
		}
		//else if() reviso si tiene tablas para la bd que queremos probar

	}

	public function close($link)
	{
		mysqli_close($link);
	}

}
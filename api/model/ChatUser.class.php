<?php

class ChatUser{

	private $id;
	private $timelines; //Array
	private $username; 
	private $email;
	private $password;

	public function getId() { return $this->id; }
	
	public function getEmail() { return $this->email; }
	public function setEmail($email) { $this->email = $email; return $this; }

	public function getPassword() { return $this->password; }
	public function setPassword($password) { $this->password = $password; return $this; }

	public function getUsername() { return $this->username; }
	public function setUsername($username) { $this->username = $username; return $this; }

	public function getTimelines() { return $this->timelines; }
	public function addTimeline($timeline)
	{
		array_push($this->timelines, $timeline);
		return $this;
	}
	public function removeTimeline($timeline)
	{
		$aux = array();
		foreach ($this->timeline as $tm) {
			if($tm !== $timeline){
				array_push($aux, $tm);
			}
		}
		return $this;
	}
	
	public function select($userId)
	{
		$link = mysqli_connect("localhost", "alesanchezr", "");
		if($link!=false)
		{
			$db_selected = mysqli_select_db($link,"c9");
			if($db_selected!=false)
			{
				$queryString = "SELECT id,username FROM USER WHERE USER.id = ".$userId;
				$result = mysqli_query($link,$queryString);
				if(mysqli_num_rows($result)==1)
				{
					$row = mysqli_fetch_object($result);
					$this->id = $row->id;
					$this->username = $row->username;
					
					return $this;
				}
			}
			
			mysqli_close($link);
		}
		
		return null;
	}
	
    /**
     * We need to override the toArray function because we want private and
     * protected properties to be accessed.
     */
    public function toArray($size='small')
    {
        $resultArray = array(
			'id' => $this->id,
			'username' => $this->username,
			'email' => $this->email
        	);
        	
        if($size=='big')
        {
        	$resultArray["timelines"] = $this->timelines->toArray($size);
        }
        
        return $resultArray;
    }
}
<?php

class ChatTimeline{

	/**
	 * All the members in the chat.
	 * @var array(ChatUser)
	 */
	protected $members;

	/**
	 * A A copy of all the messages in the timeline
	 * @var array(ChatMessage)
	 */
	protected $messages; 
	protected $name; 
	protected $id; 

	public function getId() { return $this->id; }
	
	public function getAllMembers() { return $this->members; }
	public function addMember($user)
	{
		array_push($this->members, $user);
		return $this;
	}
	public function removeMember($user)
	{
		$aux = array();
		foreach ($this->members as $u) {
			if($u !== $user){
				array_push($aux, $a);
			}
		}
		return $this;
	}

	public function getAllMessages() { return $this->messages; }
	public function addMessage($message)
	{
		array_push($this->messages, $message);
		return $this;
	}
	public function removeMessage($message)
	{
		$aux = array();
		foreach ($this->messages as $m) {
			if($m !== $message){
				array_push($aux, $m);
			}
		}
		return $this;
	}

	public function __construct()
	{
		$this->members = array();
		$this->messages = array();
	}
	
	private function select($chatTimelineId){
		$link = mysqli_connect("localhost", "alesanchezr", "");
		if($link!=false)
		{
			$db_selected = mysqli_select_db($link,"c9");
			if($db_selected!=false)
			{
				$queryString = "SELECT id,name FROM CHAT_TIMELINE WHERE id = ".$chatTimelineId;
				$result = mysqli_query($link,$queryString);
				if(mysqli_num_rows($result)==1)
				{
					$row = mysqli_fetch_object($result);
					$this->id = $row->id;
					$this->name = $row->name;
					
					return $this;
				}
			}
			
			mysqli_close($link);
		}
		
		return null;
	}
	
	public function select($timelineId)
	{
		$link = mysqli_connect("localhost", "alesanchezr", "");
		if($link!=false)
		{
			$db_selected = mysqli_select_db($link,"c9");
			if($db_selected!=false)
			{
				$queryString = "SELECT id,name FROM CHAT_TIMELINE WHERE id = ".$timelineId;
				$result = mysqli_query($link,$queryString);
				if(mysqli_num_rows($result)==1)
				{
					$row = mysqli_fetch_object($result);
					$this->id = $row->id;
					$this->name = $row->name;
					
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
     * 
     * @param size 
     * The size of the returning array:
     *	- small: a small array with minimum information about the timeline
     *  - big: a bigger timeline array, includes the list of messages
     */
    public function toArray($size='small')
    {
        $resultArray = array(
			'id' => $this->id,
			'name' => $this->name
        	);
        	
        if($size=='big')
        {
        	$resultArray["messages"] = $this->messages;
        }
        
        return $resultArray;
    }
}
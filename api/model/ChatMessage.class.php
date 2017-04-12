<?php

class ChatMessage{

	private $timestamp;
	
	private $content;
	
	/**
	 * @data-type ChatUser
	 **/
	private $author;
	
	private $timeline;

	public function __construct(){
		//$this->timestamp = new DateTime();
	}

	public function getContent() {
	    return $this->content;
	}
	public function setContent($content) {
	    $this->content = $content;
	    return $this;
	}

	public function getAuthor() {
	    return $this->author;
	}
	public function setAuthor($author) {
	    $this->author = $author;
	}

	public function getTimeline() {
	    return $this->timeline;
	}
	public function setTimeline($value) {
		if(is_a($value,'ChatTimeline')) $this->timeline = $value;
	}

	public function getTimestamp() {
	    return $this->timestamp;
	}
	
	public function insert(){
		$link = mysqli_connect("localhost", "kamekaze997", "");
		if($link!=false)
		{
			$db_selected = mysqli_select_db($link,"c9");
			if($db_selected!=false)
			{
				$queryString = "INSERT INTO MESSAGE (user_id,content,chat_timeline_id) VALUES ('".$this->author->getId()."','".$this->content."','".$this->timeline->getId()."')";
				$result = mysqli_query($link,$queryString);
				if($result)
				{
					$this->id = mysqli_insert_id($link);
					return $this;
				}
			}
			
			mysqli_close($link);
		}
		
		return false;
	}
	
    /**
     * We need to override the toArray function because we want private and
     * protected properties to be accessed.
     * 
     * @param size 
     * The size of the returning array:
     *	- small: a small array with minimum information about the message
     */
    public function toArray($size='small'){
        return array(
        	"timestamp" => $this->timestamp,
        	"content" => $this->content,
        	"author" => $this->author->toArray($size)
        	);
    }
    
	
	public function select($messageId)
	{
		$link = mysqli_connect("localhost", "kamekaze997", "");
		if($link!=false)
		{
			$db_selected = mysqli_select_db($link,"c9");
			if($db_selected!=false)
			{
				$queryString = "SELECT id,content,user_id,chat_timeline_id,creation_date FROM MESSAGE WHERE MESSAGE.id = ".$messageId;
				$result = mysqli_query($link,$queryString);
				if(mysqli_num_rows($result)==1)
				{
					$row = mysqli_fetch_object($result);
					$this->id = $row->id;
					$this->timestamp = $row->creation_date;
					$this->content = $row->content;

					$auxUser = new ChatUser();
					$this->author = $auxUser->select($row->user_id);
					$auxTimeline = new ChatTimeline();
					$this->timeline = $auxTimeline->select($row->chat_timeline_id);
					
					return $this;
				}
				else
				{
					die('Message not found');
				}
			}
			
			mysqli_close($link);
		}
		
		return null;
	}
}
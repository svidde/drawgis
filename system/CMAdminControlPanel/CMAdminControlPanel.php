<?php


class CMAdminControlPanel extends CObject implements IHasSQL {
	
	public function __construct() {
		parent::__construct();
		
	}
	
	public static function SQL($key=null) {
		$queries = array(
		'select * from user' => 'SELECT * FROM User ORDER BY id DESC;',
		);
		if(!isset($queries[$key])) {
			throw new Exception("No such SQL query, key '$key' was not found.");
		}
		return $queries[$key];
	}
	public function getAllAdmin()
	{	
		return $this->db->executeQuery(self::SQL('get * from user2group'));	
	} 
	
	public function ifAdmin()
	{
		
	}
	
	
	
	public function delete($email)
	{
		if ( $email != "root@something.se " )
		{
			if ( $this->db->deleteUser($email) )
			{
				$this->addMessage('success', "Successfully deleted user with email: $email.");
			}
		}
	}
	
	 public function readAll() 
	 {
		 try
		 {
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $this->db->executeSelectQueryAndFetchAll(self::SQL('select * from user'));
		 } 
		 catch(Exception $e) 
		 {
			return array();
		 } 
	 }
	 
}
		
		

<?php

class CMGuestbook extends CObject implements IHasSQL, IModule  {

	
	
	public function __construct() {
		parent::__construct();
	}
  
	
	public static function SQL($key=null) {
		$queries = array(
		'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, author TEXT, title TEXT, created DATETIME default (datetime('now')));",
		'insert into guestbook'   => 'INSERT INTO Guestbook (entry,author,title) VALUES (?,?,?);',
		'insert into sguestbook'   => 'INSERT INTO Guestbook (entry), VALUES (?);',
		'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
		'delete from guestbook'   => 'DELETE FROM Guestbook;',
		);
		if(!isset($queries[$key])) {
			throw new Exception("No such SQL query, key '$key' was not found.");
		}
		return $queries[$key];
	}
  
	
  public function manage($action=null) {
    switch($action) {
      case 'install': 
        try {
          $this->db->executeQuery(self::SQL('create table guestbook'));
          return array('success', 'Successfully created the database tables (or left them untouched if they already existed).');
        } catch(Exception$e) {
          die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
        }
      break;
      
      default:
        throw new Exception('Unsupported action for this module.');
      break;
    }
  }
  
  
  public function init() 
  {
  	  try 
	  {
		$this->db->executeQuery(self::SQL('create table guestbook'));
		#$this->session->addMessage('notice', 'Successfully created the database tables (or left them untouched if they already existed).');
	  } 
	  catch( Exception $e ) 
	  {
		die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
	  }
  }

  public function add($entry, $author=null, $title=null) 
  {
  	  if ( $entry == "" OR $author == "" OR $title == "" )
  	  {
		  
	  }
	  else
	  {
	  	 if ( $author == null && $title == null )
		  {
			  $this->db->executeQuery(self::SQL('insert into sguestbook'), array($entry));
			  #$this->session->addMessage('success', 'Successfully inserted new message.');
			
			  if($this->db->rowCount() != 1) 
			  {
				die('Failed to insert new guestbook item into database.');
			  }  
		  }
		  else
		  {
			$this->db->executeQuery(self::SQL('insert into guestbook'), array($entry, $author, $title));
			  #$this->session->addMessage('success', 'Successfully inserted new message.');
			
			  if($this->db->rowCount() != 1) 
			  {
				die('Failed to insert new guestbook item into database.');
			  }    
		  } 
	  }
  }

  
  public function deleteAll() 
  {
  	  $this->db->executeQuery(self::SQL('delete from guestbook'));
  	  #$this->session->addMessage('info', 'Removed all messages from the database table.');
 
  }
  
  public function readAll() 
  {
  	 try
	 {
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $this->db->executeSelectQueryAndFetchAll(self::SQL('select * from guestbook'));
	 } 
	 catch(Exception $e) 
	 {
		return array();
	 } 
  }
  
}

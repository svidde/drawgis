<?php

/**
* En klass som hanterar forumet
**/


class CMForum extends CObject implements IHasSQL,  IModule   {


	 
	public function __construct(  ) {
		 parent::__construct();
	}
  
  
	public static function SQL($key=null) {
  	  $order_order  = isset($args['order-order']) ? $args['order-order'] : 'DESC';
  	  $order_by     = isset($args['order-by'])    ? $args['order-by'] : 'id';
  	  $queries = array(
  	  	  'drop table forum'          => "DROP TABLE IF EXISTS Forum;",
  	  	  'create table forum'        => "CREATE TABLE IF NOT EXISTS Forum (id INTEGER PRIMARY KEY, status INTEGER, message TEXT, author TEXT, header TEXT, created DATETIME default (datetime('now') ) );",
  	  	  'insert forum'              => 'INSERT INTO Forum (status,message,author, header) VALUES (?,?,?,?);',
  	  	  'insert comment'            => 'INSERT INTO Forum (status,message,author) VALUES (?,?,?);',
  	  	  'select * by id' 	      => 'SELECT * FROM Forum WHERE id=? ORDER BY id DESC;',
  	  	  'select * by status'        => 'SELECT * FROM Forum WHERE status=? ORDER BY id DESC;',
  	  	  'select *' 		      => 'SELECT * FROM Forum ORDER BY id DESC;',
  	  	  'select max id'	      => 'SELECT max(id) FROM images;',
  	  	  'select * by status 0'      => 'SELECT * FROM Forum WHERE status=0 ORDER BY id DESC;',
  	  	  'delete by id'	      => 'DELETE FROM Forum WHERE id=? OR status=?;',
  	  	  //'select * by id'            => 'SELECT c.*, u.acronym as owner FROM Forum AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=? AND deleted IS NULL;',
  	  	  //'select * by status'        => 'SELECT c.*, u.acronym as owner FROM Forum AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.status=? AND deleted IS NULL;',
  	  	  //'select * by type'          => "SELECT c.*, u.acronym as owner FROM Forum AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? AND deleted IS NULL ORDER BY {$order_by} {$order_order};",
  	  	  //'select *'                  => 'SELECT c.*, u.acronym as owner FROM Forum AS c INNER JOIN User as u ON c.idUser=u.id WHERE deleted IS NULL;',
  	 
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
          $this->db->executeQuery(self::SQL('drop table forum'));
          $this->db->executeQuery(self::SQL('create table forum'));
          
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
  	
  	
  
  public function addForum($message, $author, $title) 
  {
  	  if ( $message == "" OR $author == "" )
  	  {
		  echo $message .  $author ;
		  
	  }
	  else
	  {
		  $this->db->executeQuery(self::SQL('insert forum'), array( 0, $message, $author, $title));
		  $this->session->addMessage('success', 'Successfully inserted new message.');
			
		  if($this->db->rowCount() != 1) 
		  {
			die('Failed to insert new guestbook item into database.');
		  }   
	  }
	  
  }
  public function addComment($message, $author, $id) 
  {
  	  if ( $message == "" OR $author == "" OR $id == "" )
  	  {
		 
	  }
	  else
	  {
		  $this->db->executeQuery(self::SQL('insert comment'), array( $id, $message, $author));
		  $this->session->addMessage('success', 'Successfully inserted new message.');
			
		  if($this->db->rowCount() != 1) 
		  {
			die('Failed to insert new guestbook item into database.');
		  }   
	  }
	  
  }
  
  public function delete($id)
  {
  	  $this->db->executeQuery(self::SQL('delete by id'), array( $id ));
  	  $this->session->addMessage('success', 'Successfully removed.');
		
  }
  	  
  
  
  
  public function readAll($id=null) 
  {
  	 try
	 {
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ( $id === null )
		{
			return $this->db->executeSelectQueryAndFetchAll(self::SQL('select *'));
		}
		else if ( $id == 0 )
		{
			return $this->db->executeSelectQueryAndFetchAll(self::SQL('select * by status 0'));
		} 
		else
		{
			return $this->db->executeSelectQueryAndFetchAll(self::SQL('select * by id'), array($id));
		}
			
	 } 
	 catch(Exception $e) 
	 {
		return array();
	 } 
  }
  public function readAllByStatus( $id ) 
  {
  	 try
	 {
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $this->db->executeSelectQueryAndFetchAll(self::SQL('select * by status'), array($id) );
		
	 } 
	 catch(Exception $e) 
	 {
		return array();
	 } 
  }
  
  
  
  public function getHighestId()
  {
  	$this->db->executeSelectQueryAndFetchAll(self::SQL('select max id'));  
  }
  
  public function loadById($id) {
    $res = $this->db->executeSelectQueryAndFetchAll(self::SQL('select * by id'), array($id));
    if(empty($res)) {
      $this->addMessage('error', "Failed to load content with id '$id'.");
      return false;
    } else {
      $this->data = $res[0];
    }
    return true;
  }
  

  	
}
  	

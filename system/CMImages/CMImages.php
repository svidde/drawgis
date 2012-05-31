<?php

class CMImages extends CObject implements IHasSQL, IModule  {

	
	
	public function __construct() {
		parent::__construct();
	}
  
	
	public static function SQL($key=null) {
		$queries = array(
		'create table images'  => "CREATE TABLE IF NOT EXISTS images (id INTEGER PRIMARY KEY, filename TEXT, title TEXT, photographer TEXT );",
		'insert into images'   => 'INSERT INTO images (filename,title,photographer) VALUES (?,?,?);',
		'select * from images' => 'SELECT * FROM images ORDER BY id DESC;',
		'drop tabel images' => 'DROP TABLE IF EXISTS images;',
		'select * from images by id' => 'SELECT * FROM images where id=? ORDER BY id DESC;',
		'get max id'		=> 'SELECT min(id) from images;',
		'remove by id'		=> 'DELETE FROM images WHERE id=?;',
		);
		if(!isset($queries[$key])) {
			throw new Exception("No such SQL query, key '$key' was not found.");
		}
		return $queries[$key];
	}
  
	
  public function manage($action=null) {
  	  $filenamestart =  "EmilJensen_";
  	  $filenameend = ".jpg";
  	  $JohanDahlroth = "JohanDahlroth";
  	  $PeterWestrup = "PeterWestrup";
  	  
    switch($action) {
      case 'install': 
        try {
          $this->db->executeQuery( self::SQL('drop tabel images') );	
          $this->db->executeQuery( self::SQL('create table images') );
         
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
  
  private function createImgUrl( $urlOrController )
  {  
  	$method = null;
  	$arguments=null;
  	return CDrawgis::getInstance()->request->createUrl( $urlOrController, $method, $arguments);
  }
  
  public function init() 
  {
  }
  
  public function add($filename, $title=null, $photographer=null) 
  {
  	  if ( $filename == "" OR $title == "" OR $photographer == "" )
  	  {
		  
	  }
	  else
	  {
		$this->db->executeQuery(self::SQL('insert into images'), array($filename, $title, $photographer));
		$this->session->addMessage('success', 'Successfully inserted new message.');
			
		if($this->db->rowCount() != 1) 
		{
			die('Failed to insert new guestbook item into database.');
		}    
	  } 
  }
  public function remove( $id )
  {
  	 $this->db->executeQuery(self::SQL('remove by id'), array($id));
		 
  }
  public function getNameFromId( $id )
  {
  	$names = $this->db->executeSelectQueryAndFetchAll(self::SQL('select * from images by id'), array($id));
  	$nameToReturn = "";
  	foreach ( $names as $val )
  	{
  		$nameToReturn = $val['filename'];	
  	}
  	return $nameToReturn;
  }

  public function getOne()
  {
  	  $all = $this->readAll();
  	  $count = 0;
  	  foreach($all as $val )
  	  {
  	  	  $count++;
  	  }
  	  $value = rand(1, $count);
	  return $this->db->executeSelectQueryAndFetchAll(self::SQL('select * from images by id'), array($value));
  }
  	  
  
  public function readAll() 
  {
  	 try
	 {
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $this->db->executeSelectQueryAndFetchAll(self::SQL('select * from images'));
	 } 
	 catch(Exception $e) 
	 {
		return array();
	 } 
  }
  
}

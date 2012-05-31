<?php

class CMContent extends CObject implements IHasSQL, ArrayAccess, IModule  {

  public $data;

  public function __construct($id=null) {
    parent::__construct();
    if($id) {
      $this->loadById($id);
    } else {
      $this->data = array();
    }
  }
  
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->data[$offset]); }
  public function offsetUnset($offset) { unset($this->data[$offset]); }
  public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }

  public static function SQL($key=null) {
  	  $order_order  = isset($args['order-order']) ? $args['order-order'] : 'ASC';
  	  $order_by     = isset($args['order-by'])    ? $args['order-by'] : 'id';
    $queries = array(
      'drop table content'        => "DROP TABLE IF EXISTS Content;",
      'create table content'      => "CREATE TABLE IF NOT EXISTS Content (id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, title TEXT, data TEXT, filter TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
      'insert content'            => 'INSERT INTO Content (key,type,title,data,filter,idUser) VALUES (?,?,?,?,?,?);',
      'select * by id'            => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=? AND deleted IS NULL;',
      'select * by key'           => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.key=? AND deleted IS NULL;',
      'select * by type'          => "SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? AND deleted IS NULL ORDER BY {$order_by} {$order_order};",
      'select *'                  => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE deleted IS NULL;',
      'update content'            => "UPDATE Content SET key=?, type=?, title=?, data=?, filter=?, updated=datetime('now') WHERE id=?;",
      'update content as deleted' => "UPDATE Content SET deleted=datetime('now') WHERE id=?;",
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
          $this->db->executeQuery(self::SQL('drop table content'));
          $this->db->executeQuery(self::SQL('create table content'));
          $this->db->executeQuery(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', "This is a demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
          $this->db->executeQuery(self::SQL('insert content'), array('hello-world-again', 'post', 'Hello World Again', "This is another demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
          $this->db->executeQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 'Hello World Once More', "This is one more demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
          $this->db->executeQuery(self::SQL('insert content'), array('home', 'page', 'Home page', "This is a demo page, this could be your personal home-page.\n\nLydia is a PHP-based MVC-inspired Content management Framework, watch the making of Lydia at: http://dbwebb.se/lydia/tutorial.", 'plain', $this->user['id']));
          $this->db->executeQuery(self::SQL('insert content'), array('about', 'page', 'About page', "This is a demo page, this could be your personal about-page.\n\nLydia is used as a tool to educate in MVC frameworks.", 'plain', $this->user['id']));
          $this->db->executeQuery(self::SQL('insert content'), array('download', 'page', 'Download page', "This is a demo page, this could be your personal download-page.\n\nYou can download your own copy of lydia from https://github.com/mosbth/lydia.", 'plain', $this->user['id']));
          $this->db->executeQuery(self::SQL('insert content'), array('bbcode', 'page', 'Page with BBCode', "This is a demo page with some BBCode-formatting.\n\n[b]Text in bold[/b] and [i]text in italic[/i] and [url=http://dbwebb.se]a link to dbwebb.se[/url]. You can also include images using bbcode, such as the lydia logo: [img]http://dbwebb.se/lydia/current/themes/core/logo_80x80.png[/img]", 'bbcode', $this->user['id']));
          $this->db->executeQuery(self::SQL('insert content'), array('htmlpurify', 'page', 'Page with HTMLPurifier', "This is a demo page with some HTML code intended to run through <a href='http://htmlpurifier.org/'>HTMLPurify</a>. Edit the source and insert HTML code and see if it works.\n\n<b>Text in bold</b> and <i>text in italic</i> and <a href='http://dbwebb.se'>a link to dbwebb.se</a>. JavaScript, like this: <javascript>alert('hej');</javascript> should however be removed.", 'htmlpurify', $this->user['id']));
          return array('success', 'Successfully created the database tables and created a default "Hello World" blog post, owned by you.');
        } catch(Exception$e) {
          die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
        }
      break;
      
      default:
        throw new Exception('Unsupported action for this module.');
      break;
    }
  }
  
  
  public function init() {
    try {
      $this->db->executeQuery(self::SQL('drop table content'));
      $this->db->executeQuery(self::SQL('create table content'));
      $this->db->executeQuery(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', "This is a demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
      $this->db->executeQuery(self::SQL('insert content'), array('hello-world-again', 'post', 'Hello World Again', "This is another demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
      $this->db->executeQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 'Hello World Once More', "This is one more demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
      $this->db->executeQuery(self::SQL('insert content'), array('home', 'page', 'Home page', "This is a demo page, this could be your personal home-page.\n\nLydia is a PHP-based MVC-inspired Content management Framework, watch the making of Lydia at: http://dbwebb.se/lydia/tutorial.", 'plain', $this->user['id']));
      $this->db->executeQuery(self::SQL('insert content'), array('about', 'page', 'About page', "This is a demo page, this could be your personal about-page.\n\nLydia is used as a tool to educate in MVC frameworks.", 'plain', $this->user['id']));
      $this->db->executeQuery(self::SQL('insert content'), array('download', 'page', 'Download page', "This is a demo page, this could be your personal download-page.\n\nYou can download your own copy of lydia from https://github.com/mosbth/lydia.", 'plain', $this->user['id']));
      $this->db->executeQuery(self::SQL('insert content'), array('bbcode', 'page', 'Page with BBCode', "This is a demo page with some BBCode-formatting.\n\n[b]Text in bold[/b] and [i]text in italic[/i] and [url=http://dbwebb.se]a link to dbwebb.se[/url]. You can also include images using bbcode, such as the lydia logo: [img]http://dbwebb.se/lydia/current/themes/core/logo_80x80.png[/img]", 'bbcode', $this->user['id']));
      $this->db->executeQuery(self::SQL('insert content'), array('htmlpurify', 'page', 'Page with HTMLPurifier', "This is a demo page with some HTML code intended to run through <a href='http://htmlpurifier.org/'>HTMLPurify</a>. Edit the source and insert HTML code and see if it works.\n\n<b>Text in bold</b> and <i>text in italic</i> and <a href='http://dbwebb.se'>a link to dbwebb.se</a>. JavaScript, like this: <javascript>alert('hej');</javascript> should however be removed.", 'htmlpurify', $this->user['id']));
      $this->addMessage('success', 'Successfully created the database tables and created a default "Hello World" blog post, owned by you.');
     } catch( Exception $e ) {
      die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
    }
  }
  
  public function save() {
    $msg = null;
    if($this['id']) {
      $this->db->executeQuery(self::SQL('update content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this['id']));
      $msg = 'update';
    } else {
      $this->db->executeQuery(self::SQL('insert content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this->user['id']));
      $this['id'] = $this->db->lastInsId();
      $msg = 'created';
    }
    $rowcount = $this->db->rowCount();
    if($rowcount) {
      $this->addMessage('success', "Successfully {$msg} content '" . htmlEnt($this['key']) . "'.");
    } else {
      $this->addMessage('error', "Failed to {$msg} content '" . htmlEnt($this['key']) . "'.");
    }
    return $rowcount === 1;
  }
  
  public function add(){
  	  
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
  
  
   public function listAll($args=null) {    
    try {
      if(isset($args) && isset($args['type'])) {
        return $this->db->executeSelectQueryAndFetchAll(self::SQL('select * by type', $args), array($args['type']));
      } else {
        return $this->db->executeSelectQueryAndFetchAll(self::SQL('select *', $args));
      }
    } catch(Exception $e) {
      echo $e;
      return null;
    }
  }
  
  public function delete()
  {
    if($this['id']) {
      $this->db->executeQuery(self::SQL('update content as deleted'), array($this['id']));
    }
    $rowcount = $this->db->rowCount();
    if($rowcount) {
      $this->addMessage('success', "Successfully set content '" . htmlEnt($this['key']) . "' as deleted.");
    } else {
      $this->addMessage('error', "Failed to set content '" . htmlEnt($this['key']) . "' as deleted.");
    }
    return $rowcount === 1;
  }
  
  public static function filter($data, $filter) {
    switch($filter) {
      case 'htmlpurify': $data = nl2br(CHTMLPurifier::purify($data)); break;
      case 'bbcode': $data = nl2br(bbcode2html(htmlEnt($data))); break;    
      case 'php': $data = nl2br(makeClickable(eval('?>'.$data))); break;
      case 'html': $data = nl2br(makeClickable($data)); break;
      case 'plain': $data = strip_tags($data); break;
      default: $data = nl2br(CHTMLPurifier::purify($data)); break;
    }
    return $data;
  }
  
  public function getFilteredData() {
    return $this->filter($this['data'], $this['filter']);
  }
  
  
  
}

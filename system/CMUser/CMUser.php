<?php


class CMUser extends CObject implements IHasSQL, ArrayAccess, IModule  {
	
	
	public $profile = array();

	
	public function __construct($dr=null) {
		parent::__construct($dr);
		$profile = $this->session->GetAuthenticatedUser();
		$this->profile = is_null($profile) ? array() : $profile;
		$this['isAuthenticated'] = is_null($profile) ? false : true;
	}
	
	public function offsetSet($offset, $value) { if (is_null($offset)) { $this->profile[] = $value; } else { $this->profile[$offset] = $value; }}
	public function offsetExists($offset) { return isset($this->profile[$offset]); }
	public function offsetUnset($offset) { unset($this->profile[$offset]); }
	public function offsetGet($offset) { return isset($this->profile[$offset]) ? $this->profile[$offset] : null; }

  
	
	public static function SQL($key=null) {
    $queries = array(
      'drop table user'         => "DROP TABLE IF EXISTS User;",
      'drop table group'        => "DROP TABLE IF EXISTS Groups;",
      'drop table user2group'   => "DROP TABLE IF EXISTS User2Groups;",
      'create table user'       => "CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, email TEXT, algorithm TEXT, salt TEXT, password TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
      'create table group'      => "CREATE TABLE IF NOT EXISTS Groups (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
      'create table user2group' => "CREATE TABLE IF NOT EXISTS User2Groups (idUser INTEGER, idGroups INTEGER, created DATETIME default (datetime('now')), PRIMARY KEY(idUser, idGroups));",
      'insert into user'        => 'INSERT INTO User (acronym,name,email,algorithm,salt,password) VALUES (?,?,?,?,?,?);',
      'insert into group'       => 'INSERT INTO Groups (acronym,name) VALUES (?,?);',
      'insert into user2group'  => 'INSERT INTO User2Groups (idUser,idGroups) VALUES (?,?);',
      'check user password'     => 'SELECT * FROM User WHERE (acronym=? OR email=?);',
      'get group memberships'   => 'SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;',
      'update profile'          => "UPDATE User SET name=?, email=?, updated=datetime('now') WHERE id=?;",
      'update password'         => "UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now') WHERE id=?;",
      'get * from user2group'   => "SELECT * FROM User2Group WHERE idGroups=1;",
      'get * from user' 	=> "SELECT * FROM USER;",
      );
    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }
  
   /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   *
   * @param string $action what to do.
   */
  public function manage($action=null) {
    switch($action) {
      case 'install': 
        try {
          $this->db->executeQuery(self::SQL('drop table user2group'));
          $this->db->executeQuery(self::SQL('drop table group'));
          $this->db->executeQuery(self::SQL('drop table user'));
          $this->db->executeQuery(self::SQL('create table user'));
          $this->db->executeQuery(self::SQL('create table group'));
          $this->db->executeQuery(self::SQL('create table user2group'));
          $password = $this->createPassword('root');
          $this->db->executeQuery(self::SQL('insert into user'), array('root', 'The Administrator', 'root@something.se', $password['algorithm'], $password['salt'], $password['password']));
          $idRootUser = $this->db->lastInsId();
          $password = $this->createPassword('doe');
          $this->db->executeQuery(self::SQL('insert into user'), array('doe', 'John/Jane Doe', 'doe@something.se', $password['algorithm'], $password['salt'], $password['password']));
          $idDoeUser = $this->db->lastInsId();
          $this->db->executeQuery(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
          $idAdminGroup = $this->db->lastInsId();
          $this->db->executeQuery(self::SQL('insert into group'), array('user', 'The User Group'));
          $idUserGroup = $this->db->lastInsId();
          $this->db->executeQuery(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
          $this->db->executeQuery(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
          $this->db->executeQuery(self::SQL('insert into user2group'), array($idDoeUser, $idUserGroup));
          return array('success', 'Successfully created the database tables and created a default admin user as root:root and an ordinary user as doe:doe.');
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
		$this->db->executeQuery(self::SQL('drop table user2group'));
		$this->db->executeQuery(self::SQL('drop table group'));
		$this->db->executeQuery(self::SQL('drop table user'));
		$this->db->executeQuery(self::SQL('create table user'));
		$this->db->executeQuery(self::SQL('create table group'));
		$this->db->executeQuery(self::SQL('create table user2group'));
		$password = $this->createPassword('root');
		$this->db->executeQuery(self::SQL('insert into user'), array('root', 'The Administrator', 'root@dbwebb.se', $password['algorithm'], $password['salt'], $password['password']));
		$idRootUser = $this->db->lastInsId();
		$password = $this->createPassword('doe');
		$this->db->executeQuery(self::SQL('insert into user'), array('doe', 'John/Jane Doe', 'doe@dbwebb.se', $password['algorithm'], $password['salt'], $password['password']));
		$idDoeUser = $this->db->lastInsId();
		$this->db->executeQuery(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
		$idAdminGroup = $this->db->lastInsId();
		$this->db->executeQuery(self::SQL('insert into group'), array('user', 'The User Group'));
		$idUserGroup = $this->db->lastInsId();
		$this->db->executeQuery(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
		$this->db->executeQuery(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
		$this->db->executeQuery(self::SQL('insert into user2group'), array($idDoeUser, $idUserGroup));
		$this->addMessage('success', 'Successfully created the database tables and created a default admin user as root:root and an ordinary user as doe:doe.');
    } catch(Exception$e) {
      die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
    }
  }
  	
  	public function login($akronymOrEmail, $password) {
  		$user = $this->db->executeSelectQueryAndFetchAll(self::SQL('check user password'), array($akronymOrEmail, $akronymOrEmail));
  		$user = (isset($user[0])) ? $user[0] : null;
  		if(!$user) {
  			return false;
  		} else if(!$this->checkPassword($password, $user['algorithm'], $user['salt'], $user['password'])) {
  			return false;
  		}
  		unset($user['algorithm']);
  		unset($user['salt']);
  		unset($user['password']);
  		if($user) {
  			$user['isAuthenticated'] = true;
  			$user['groups'] = $this->db->executeSelectQueryAndFetchAll(self::SQL('get group memberships'), array($user['id']));
  			foreach($user['groups'] as $val) {
  				if($val['id'] == 1) {
  					$user['hasRoleAdmin'] = true;
  				}
  				if($val['id'] == 2) {
  					$user['hasRoleUser'] = true;
  				}
  			}
  			$this->profile = $user;
  			$this->session->setAuthenticatedUser($this->profile);
  		}
  		return ($user != null);
  	}
	
	public function logout(){
		$this->session->unsetAuthenticatedUser();
		$this->profile = array();
		$this->addMessage('success', "You have logged out.");
	}
	
	
	public function save()  {
		$this->db->executeQuery(self::SQL('update profile'), array($this['name'], $this['email'], $this['id']));
		$this->session->setAuthenticatedUser($this->profile);
		return $this->db->rowCount() === 1;
	}
	
	
	public function changePassword($password) {
		$this->db->executeQuery(self::SQL('update password'), array($password, $this['id']));
		return $this->db->rowCount() === 1;
	}
		
	
	 
	 
	public function createPassword($plain, $algorithm=null) {
	  	$password = array(
	  	  	'algorithm'=>($algorithm ? $algoritm : CDrawgis::getInstance()->config['hashing_algorithm']),
	  	  	'salt'=>null
	  	  	);
	  	switch($password['algorithm']) {
	  		case 'sha1salt': 
	  			$password['salt'] = sha1(microtime()); 
	  			$password['password'] = sha1($password['salt'].$plain); 
	  			break;
	  		case 'md5salt': 
	  			$password['salt'] = md5(microtime()); 
	  			$password['password'] = md5($password['salt'].$plain); 
	  			break;
	  		case 'sha1': 
	  			$password['password'] = sha1($plain); 
	  			break;
	  		case 'md5': 
	  			$password['password'] = md5($plain); 
	  			break;
	  		case 'plain': 
	  			$password['password'] = $plain;
	  			break;
	  		default: 
	  			throw new Exception('Unknown hashing algorithm');
	  	}
	  	return $password;
	}
	  
	 
	public function checkPassword($plain, $algorithm, $salt, $password) {
	 	switch($algorithm) {
	 	case 'sha1salt': 
	 		return $password === sha1($salt.$plain); 
	 		break;
	 	case 'md5salt': 
	 		return $password === md5($salt.$plain); 
	 		break;
	 	case 'sha1': 
	 		return $password === sha1($plain); 
	 		break;
	 	case 'md5': 
	 		return $password === md5($plain);
	 		break;
	 	case 'plain': 
	 		return $password === $plain;
	 		break;
	 	default: 
	 		throw new Exception('Unknown hashing algorithm');
	 	}
	}
	public function emailExist($email)
	{
		$users = $this->db->executeSelectQueryAndFetchAll( self::SQL('get * from user') );
		$found = false;
		foreach ( $users as $val )
		{
			if ( $email == $val['email'] )
			{
				$found = true;
			}
		}
		return $found;
	}
	
	
	public function create($acronym, $password, $name, $email) {
		$exist = $this->emailExist($email);
		if ( $exist == false )
		{
			$pwd = $this->createPassword($password);
			$this->db->executeQuery(self::SQL('insert into user'), array($acronym, $name, $email, $pwd['algorithm'], $pwd['salt'], $pwd['password']));
			if($this->db->rowCount() == 0) {
				$this->addMessage('error', "Failed to create user.");
				return false;
			}	
			return true;
		}
		else
		{
			$this->addMessage('error', "Email exist");
			return false;
		}
	}
	
		
}
  

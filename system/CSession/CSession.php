<?php

class CSession {

	
	private $key;
	private $data = array();
	private $flash = null;


	
	public function __construct($key) {
    $this->key = $key;
  }


	
	public function __set($key, $value) {
    $this->data[$key] = $value;
  }


	
	public function __get($key) {
    return isset($this->data[$key]) ? $this->data[$key] : null;
  }


  public function setFlash($key, $value) { $this->data['flash'][$key] = $value; }
  public function getFlash($key) { return isset($this->flash[$key]) ? $this->flash[$key] : null; }


  
  public function addMessage($type, $message) {
    $this->data['flash']['messages'][] = array('type' => $type, 'message' => $message);
  }

  public function getMessages() {
    return isset($this->flash['messages']) ? $this->flash['messages'] : null;
  }


 
  public function storeInSession() {
    $_SESSION[$this->key] = $this->data;
  }


  
  public function populateFromSession() {
    if(isset($_SESSION[$this->key])) {
      $this->data = $_SESSION[$this->key];
      if(isset($this->data['flash'])) {
        $this->flash = $this->data['flash'];
        unset($this->data['flash']);
      }
    }
  }

  
  public function setAuthenticatedUser($profile) { $this->data['authenticated_user'] = $profile; }
  public function unsetAuthenticatedUser() { unset($this->data['authenticated_user']); }
  public function getAuthenticatedUser() { return $this->authenticated_user; }

  

}

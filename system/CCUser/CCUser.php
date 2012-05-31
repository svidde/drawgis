<?php

class CCUser extends CObject implements IController {
	
	
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->views->setTitle('User Controller');
		$this->views->addInclude( __DIR__ . '/default.tpl.php', array(
			'is_authenticated'=>$this->user['isAuthenticated'], 
			'user'=>$this->user,
			));
	}
	
	
	public function profile() {
		$form = new CFormUserProfile($this, $this->user);
		$form->check();

		$this->views->setTitle('User Profile');
                $this->views->addInclude(__DIR__ . '/profile.tpl.php', array(
                		'is_authenticated'=>$this->user['isAuthenticated'], 
                		'user'=>$this->user,
                		'profile_form'=>$form->getHTML(),
                ));
	}
			    	    
	
	public function login() {
		$form = new CFormUserLogin($this);
		if($form->check() === false) {
			$this->addMessage('notice', 'Some fields did not validate and the form could not be processed.');
			$this->redirectToController('login');
		}
		 $this->views->setTitle('Login');
		  $this->views->addInclude(__DIR__ . '/login.tpl.php', array(
		 		'login_form' => $form,
		 		'allow_create_user' => CDrawgis::getInstance()->config['create_new_users'],
		 		'create_user_url' => $this->createUrl(null, 'create'),
                ));
        }
	
	public function doLogin($form) {
		if($this->user->login($form['acronym']['value'], $form['password']['value'])) {
			$this->addMessage('success', "Welcome {$this->user['name']}.");
			$this->redirectToController('profile');
		} else {
			$this->addMessage('notice', "Failed to login, user does not exist or password does not match.");
			$this->redirectToController('login');      
		}
	}
	
	public function logout() {
		$this->user->logout();
		$this->login();
	}
	
	
	public function init() {
		$this->user->init();
		$this->redirectToController();
	}
	
	
	
	public function doChangePassword($form) {
		if($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
			$this->addMessage('error', 'Password does not match or is empty.');
		} else {
			$ret = $this->user->ChangePassword($form['password']['value']);
			$this->addMessage($ret, 'Saved new password.', 'Failed updating password.');
		}
		$this->redirectToController('profile');
	}

	public function doProfileSave($form) {
		$this->user['name'] = $form['name']['value'];
		$this->user['email'] = $form['email']['value'];
		$ret = $this->user->save();
		$this->addMessage($ret, 'Saved profile.', 'Failed saving profile.');
		$this->redirectToController('profile');
	}
	
	
	public function create() {
		$form = new CFormUserCreate($this);
		if($form->check() === false) {
			$this->addMessage('notice', 'You must fill in all values.');
			$this->redirectToController('Create');
		}
		$this->views->setTitle('Create user');
		$this->views->addInclude(__DIR__ . '/create.tpl.php', array('form' => $form->getHTML()));     
	}
	
	
	public function doCreate($form) {    
		if($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
			$this->addMessage('error', 'Password does not match or is empty.');
			$this->redirectToController('create');
		} else if( $this->user->create( $form['acronym']['value'], 
			   			$form['password']['value'],
			   			$form['name']['value'],
			   			$form['email']['value']
                           )) {
                $this->addMessage('success', "Welcome {$this->user['name']}. Your have successfully created a new account.");
                $this->user->login($form['acronym']['value'], $form['password']['value']);
                $this->redirectToController('profile');
                	   } else {
                	   	   $this->addMessage('notice', "Failed to create an account.");
                	   	   $this->redirectToController('create');
                	   }
        }
  
	
}
	
	
	
	
	
	
	

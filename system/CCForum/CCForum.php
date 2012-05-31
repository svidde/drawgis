<?php

class CCForum extends CObject implements IController {
	
	private $forumModel;
	
	 public function __construct() {
	parent::__construct();
	$this->forumModel = new CMForum();
  }


  public function index() 
  {
	$this->views->setTitle( "Forum" );
	$this->views->addInclude(__DIR__ . '/index.tpl.php', array(
		'entries' => $this->forumModel->readAll(0),
		'formAction'=>$this->request->createUrl('', 'handler')
    ));
  }
  public function comment()
  {
  	  $this->views->setTitle( "Forum" );
  	  $id  = $_SESSION['id'];
  	  $this->views->addInclude(__DIR__ . '/comment.tpl.php', array(
		'forum' => $this->forumModel->readAll( $id ),
		'entries' => $this->forumModel->readAllByStatus( $id ),
		'formAction'=>$this->request->createUrl('', 'handler')
    ));
  }
  
  public function handler()  {
	if( isset($_POST['doAddForum']) ) 
	{
	      $this->forumModel->addForum($_POST['message'], $_POST['author'] , $_POST['title']);
	      $this->redirectTo($this->request->createUrl($this->request->controller));	
	
	}
	else if(isset($_POST['doAddForumComment'])) 
	{
		$_SESSION['id'] = $_POST['id'];
		$this->redirectTo($this->request->createUrl($this->request->controller, 'comment'));
	}   
	else if(isset($_POST['doDelForumComment'])) 
	{
		$this->forumModel->delete($_POST['id']);
		$this->redirectTo($this->request->createUrl($this->request->controller));
	} 
	else if(isset($_POST['doAddComment'])) 
	{
		 $this->forumModel->addComment($_POST['message'], $_POST['author'], $_POST['id'] );
		 $this->redirectTo($this->request->createUrl($this->request->controller, 'comment'));	
	} 
    
  }
}

<?php

class CCGuestbook extends CObject implements IController {
	

	private $guestbookModel;

  public function __construct() {
	parent::__construct();
	$this->guestbookModel = new CMGuestbook();
  }


  public function index() 
  {
	$this->views->setTitle( "Guestbook" );
	$this->views->addInclude(__DIR__ . '/index.tpl.php', array(
		'entries'=>$this->guestbookModel->readAll(), 
		'formAction'=>$this->request->createUrl('', 'handler')
    ));
  }
  
  
  public function handler()  {
	if( isset($_POST['doAdd']) ) 
	{
	      $this->guestbookModel->add($_POST['newEntry']);
	}
	else if(isset($_POST['doClear'])) 
	{
		$this->guestbookModel->deleteAll();
	}   
	else if(isset($_POST['doCreate'])) 
	{
		$this->guestbookModel->init();
	}  
     $this->redirectTo($this->request->createUrl($this->request->controller));
  }
  
  
  
}

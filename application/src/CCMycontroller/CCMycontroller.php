<?php

class CCMycontroller extends CObject implements IController {

	
	private $guestbookModel;
	private $contentModel;
	
	
  public function __construct() { 
  parent::__construct(); 
  $this->guestbookModel = new CMGuestbook();
  $this->contentModel = new CMContent();
  }
  

  public function index() {
    $content = new CMContent(5);
    $this->views->setTitle('About me'.htmlEnt($content['title']))
                ->addInclude(__DIR__ . '/page.tpl.php', array(
                  'content' => $content,
                ));
  }
  /**
   * The blog.
   */
  public function blog() {
    $this->views->setTitle('My blog')
                ->addInclude(__DIR__ . '/blog.tpl.php', array(
                  'contents' => $this->contentModel->listAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC')),
                  'formAction'=>$this->request->createUrl('', 'handler')
                 ));
  }
  
  


  public function guestbook() {
  	$this->views->setTitle( "Guestbook" );
	$this->views->addInclude(__DIR__ . '/guestbook.tpl.php', array(
		'entries'=>$this->guestbookModel->readAll(), 
		'formAction'=>$this->request->createUrl('', 'handler')
    ));
  }
  
  
  
  public function handler()  {
	if( isset($_POST['doAdd']) ) 
	{
	      $this->guestbookModel->add($_POST['newEntry'], $_POST['authour'], $_POST['title']);
	      $this->redirectTo($this->request->createUrl($this->request->controller, 'guestbook'));
	}
	else if( isset($_POST['doCreate']) ) 
	{
	      $this->guestbookModel->init();
	      $this->redirectTo($this->request->createUrl($this->request->controllers));
	}
    
  }

 
  
}



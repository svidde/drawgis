<?php


/**
* Admin Control Panel to manage admin stuff.
* 
* @package LydiaCore
*/

class CCAdminControlPanel extends CObject implements IController {

	private $guestbookModel;
	private $contentModel;
	private $imageModel;
	private $adminPanelModel;
	private $forumModel;
	private $userModel;

  public function __construct() {
  	parent::__construct();
	$this->adminPanelModel = new CMAdminControlPanel();
	$this->guestbookModel = new CMGuestbook();
  	$this->contentModel = new CMContent();
  	$this->imageModel = new CMImages();
  	$this->forumModel = new CMForum();
  	$this->userModel = new CMUser();
  }


  public function index() {
  	$this->views->setTitle( "Administrative" );
	$this->views->addInclude(__DIR__ . '/index.tpl.php', array(
		'entries'=> $this->adminPanelModel->readAll(), 
		'formAction'=>$this->request->createUrl('', 'handler')
    ));
  }
  public function remove()
   {
  	$this->adminPanelModel = new CMAdminControlPanel();
  	$this->views->setTitle( "Administrative" );
	$this->views->addInclude(__DIR__ . '/remove.tpl.php', array(
		'entries'=> $this->adminPanelModel->readAll(), 
		'formAction'=>$this->request->createUrl('', 'handler')
    ));
   }
   public function editNews()
   {
  	$this->views->setTitle( "Administrative" );
	$this->views->addInclude(__DIR__ . '/editIndex.tpl.php', array(
		'contents' => $this->contentModel->listAll(),
                ));
   }
   
   public function createPage() {
    $this->edit();
    }
    
    public function edit($id=null) {
    $content = new CMContent($id);
    $form = new CFormContent($content);
    $status = $form->check();
    if($status === false) {
      $this->addMessage('notice', 'The form could not be processed.');
      $this->redirectToController('edit', $id);
    } else if($status === true) {
      $this->redirectToController('edit', $content['id']);
    }
    
    $title = isset($id) ? 'Edit' : 'Create';
    $this->views->setTitle("$title content: $id")
                ->addInclude(__DIR__ . '/edit.tpl.php', array(
                  'user'=>$this->userModel, 
                  'content'=>$content, 
                  'form'=>$form,
		 ));
  }
  
  public function editImage()
   {
   	$this->views->setTitle('Bilder');
   	$this->views->addInclude(__DIR__ . '/editImage.tpl.php', array(
		'formAction'=>$this->request->createUrl('', 'handler'),
                'images' => $this->imageModel->readAll(),
    ));
   }
  
  public function handler()  {
	if( isset($_POST['doDel']) ) 
	{
	      $this->adminPanelModel->delete($_POST['email']);
	} 
	else if( isset($_POST['doRemoveImage']) ) 
	{
	      $this->removeImg( $_POST['id']  );
	} 
	else if ( isset($_POST['doUploadImg'] ) )
	{
	       $this->doAddImage( $_FILES, $_POST['title'], $_POST['photographer'] );
	}
     $this->redirectTo($this->request->createUrl($this->request->controller));
  }
  
  
  public function doAddImage($_FILES, $title, $photographer )
   {
   	  
   	if ((($_FILES["file"]["type"] == "image/gif")
   		|| ($_FILES["file"]["type"] == "image/jpeg")
   		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg"))
		&& ($_FILES["file"]["size"] < 7778350))
	{
		if ($_FILES["file"]["error"] > 0)
		{
			$this->addMessage('notice', "Return Code: " . $_FILES["file"]["error"] );
		}
		else
		{
			/*echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			echo "Type: " . $_FILES["file"]["type"] . "<br />";
			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";*/
	
			if (file_exists("upload/" . $_FILES["file"]["name"]))
			{
				$this->addMessage('notice', $_FILES["file"]["name"] . " already exists. " );
			}
			else
			{
				$path = DRAWGIS_SITE_PATH . '/src/img';
				move_uploaded_file($_FILES["file"]["tmp_name"],
					"$path/" . $_FILES["file"]["name"]);
				$this->addMessage('success', "Stored in: " . "upload/" . $_FILES["file"]["name"]);
				$this->imageModel->add($_FILES["file"]["name"], $title, $photographer);
			}
		}
	}
	else
	{
		echo "Invalid file";
	}
	$this->redirectTo($this->request->createUrl($this->request->controller, 'editImage'));
	   
   }
   
   private function removeImg( $id )
    {
    	     $file = $this->imageModel->getNameFromId( $id );
    	     $path = DRAWGIS_SITE_PATH . '/src/img/';
    	     $files = $this->readDirectory($path);
    	     
    	     if(isset( $file ) && in_array( $file, $files))
    	     {
    	     	     $filename = $path . $file;
    	     	     unlink($filename);
    	     	     $files = $this->readDirectory($path);
    	     	     $res = "Filen raderades."; 
    	     	     $this->imageModel->remove( $id );
    	     }
    	     else
    	     {
    	     	     $res = "Filen finns ej och kunde inte raderas.";    
    	     }	
    	     $this->redirectTo($this->request->createUrl($this->request->controller, 'editImage'));
    }
  
    private function readDirectory($aPath) {
  $list = Array();
  if(is_dir($aPath)) {
    if ($dh = opendir($aPath)) {
      while (($file = readdir($dh)) !== false) {
        if(is_file("$aPath/$file") && $file != '.htaccess') {
          $list[$file] = "$file";
        }
      }
      closedir($dh);
    }
  }
  sort($list, SORT_STRING);
  return $list;
}
  
  
} 

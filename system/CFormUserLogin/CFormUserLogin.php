<?php

class CFormUserLogin extends CForm {
	
  public function __construct($object) {
    parent::__construct();
    $this->addElement(new CFormElementText('acronym'))
    	 ->addElement(new CFormElementPassword('password'))
    	 ->addElement(new CFormElementSubmit('login', array('callback'=>array($object, 'doLogin'))));
 
    $this->setValidation('acronym', array('not_empty'))
         ->setValidation('password', array('not_empty'));
  }
  
}

<?php

class CFormUserCreate extends CForm {

	
  public function __construct($object) {
    parent::__construct();
    $this->addElement(new CFormElementText('acronym', array('required'=>true)))
         ->addElement(new CFormElementPassword('password', array('required'=>true)))
         ->addElement(new CFormElementPassword('password1', array('required'=>true, 'label'=>'Password again:')))
         ->addElement(new CFormElementText('name', array('required'=>true)))
         ->addElement(new CFormElementText('email', array('required'=>true)))
         ->addElement(new CFormElementSubmit('create', array('callback'=>array($object, 'doCreate'))));
         
    $this->setValidation('acronym', array('not_empty'))
         ->setValidation('password', array('not_empty'))
         ->setValidation('password1', array('not_empty'))
         ->setValidation('name', array('not_empty'))
         ->setValidation('email', array('not_empty'));
  }
  
}

<?php

class CMModules extends CObject {

  private $drawgisCoreModules = array('CDrawgis', 'CDatabase', 'CRequest', 'CViewContainer', 'CSession', 'CObject');
  private $drawgisCMFModules = array('CForm', 'CCPage', 'CCBlog', 'CMUser', 'CCUser', 'CMContent', 'CCContent', 'CFormUserLogin', 'CFormUserProfile', 'CFormUserCreate', 'CFormContent', 'CHTMLPurifier');

  
 
  public function __construct() { parent::__construct(); }


  public function availableControllers() {	
    $controllers = array();
    foreach($this->config['controllers'] as $key => $val) {
      if($val['enabled']) {
        $rc = new ReflectionClass($val['class']);
        $controllers[$key] = array();
        $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach($methods as $method) {
          if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
            $methodName = mb_strtolower($method->name);
            $controllers[$key][] = $methodName;
          }
        }
        sort($controllers[$key], SORT_LOCALE_STRING);
      }
    }
    ksort($controllers, SORT_LOCALE_STRING);
    return $controllers;
  }


  public function readAndAnalyse() {
    $src = DRAWGIS_INSTALL_PATH.'/system';
    if(!$dir = dir($src)) throw new Exception('Could not open the directory.');
    $modules = array();
    while (($module = $dir->read()) !== false) {
      if(is_dir("$src/$module")) {
        if(class_exists($module)) {
          $rc = new ReflectionClass($module);
          $modules[$module]['name']          = $rc->name;
          $modules[$module]['interface']     = $rc->getInterfaceNames();
          $modules[$module]['isController']  = $rc->implementsInterface('IController');
          $modules[$module]['isModel']       = preg_match('/^CM[A-Z]/', $rc->name);
          $modules[$module]['hasSQL']        = $rc->implementsInterface('IHasSQL');
          $modules[$module]['isManageable']  = $rc->implementsInterface('IModule');
          $modules[$module]['isDrawgisCore']   = in_array($rc->name, array('CDrawgis', 'CDatabase', 'CRequest', 'CViewContainer', 'CSession', 'CObject'));
          $modules[$module]['isDrawgisCMF']    = in_array($rc->name, array('CForm', 'CCPage', 'CCBlog', 'CMUser', 'CCUser', 'CMContent', 'CCContent', 'CFormUserLogin', 'CFormUserProfile', 'CFormUserCreate', 'CFormContent', 'CHTMLPurifier'));
        }
      }
    }
    $dir->close();
    ksort($modules, SORT_LOCALE_STRING);
    return $modules;
  }
  
  
  
  public function install() {
    $allModules = $this->readAndAnalyse();
    uksort($allModules, function($a, $b) {
        return ($a == 'CMUser' ? -1 : ($b == 'CMUser' ? 1 : 0));
      }
    );
    $installed = array();
    foreach($allModules as $module) {
      if($module['isManageable']) {
        $classname = $module['name'];
        $rc = new ReflectionClass($classname);
        $obj = $rc->newInstance();
        $method = $rc->getMethod('Manage');
        $installed[$classname]['name']    = $classname;
        $installed[$classname]['result']  = $method->invoke($obj, 'install');
      }
    }
    //ksort($installed, SORT_LOCALE_STRING);
    return $installed;
  }
  
  
  
    /**
   * Get info and details about a module.
   *
   * @param $module string with the module name.
   * @returns array with information on the module.
   */
  private function getDetailsOfModule($module) {
    $details = array();
    if(class_exists($module)) {
      $rc = new ReflectionClass($module);
      $details['name']          = $rc->name;
      $details['filename']      = $rc->getFileName();
      $details['doccomment']    = $rc->getDocComment();
      $details['interface']     = $rc->getInterfaceNames();
      $details['isController']  = $rc->implementsInterface('IController');
      $details['isModel']       = preg_match('/^CM[A-Z]/', $rc->name);
      $details['hasSQL']        = $rc->implementsInterface('IHasSQL');
      $details['isManageable']  = $rc->implementsInterface('IModule');
      $details['isDrawgisCore']   = in_array($rc->name, $this->drawgisCoreModules);
      $details['isDrawgisCMF']    = in_array($rc->name, $this->drawgisCMFModules);
      $details['publicMethods']     = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
      $details['protectedMethods']  = $rc->getMethods(ReflectionMethod::IS_PROTECTED);
      $details['privateMethods']    = $rc->getMethods(ReflectionMethod::IS_PRIVATE);
      $details['staticMethods']     = $rc->getMethods(ReflectionMethod::IS_STATIC);
    }
    return $details;
  }
  
  
    /**
   * Get info and details about the methods of a module.
   *
   * @param $module string with the module name.
   * @returns array with information on the methods.
   */
  private function getDetailsOfModuleMethods($module) {
    $methods = array();
    if(class_exists($module)) {
      $rc = new ReflectionClass($module);
      $classMethods = $rc->getMethods();
      foreach($classMethods as $val) {
        $methodName = $val->name;
        $rm = $rc->getMethod($methodName);
        $methods[$methodName]['name']          = $rm->getName();
        $methods[$methodName]['doccomment']    = $rm->getDocComment();
        $methods[$methodName]['startline']     = $rm->getStartLine();
        $methods[$methodName]['endline']       = $rm->getEndLine();
        $methods[$methodName]['isPublic']      = $rm->isPublic();
        $methods[$methodName]['isProtected']   = $rm->isProtected();
        $methods[$methodName]['isPrivate']     = $rm->isPrivate();
        $methods[$methodName]['isStatic']      = $rm->isStatic();
      }
    }
    ksort($methods, SORT_LOCALE_STRING);
    return $methods;
  }

    /**
   * Get info and details about a module.
   *
   * @param $module string with the module name.
   * @returns array with information on the module.
   */
  public function readAndAnalyseModule($module) {
    $details = $this->getDetailsOfModule($module);
    $details['methods'] = $this->getDetailsOfModuleMethods($module);
    return $details;
  }
  
}

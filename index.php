<?php
//
// PHASE: BOOTSTRAP
//
define('DRAWGIS_INSTALL_PATH', dirname(__FILE__));
define('DRAWGIS_SITE_PATH', DRAWGIS_INSTALL_PATH . '/application');

require(DRAWGIS_INSTALL_PATH.'/system/bootstrap.php');



$dr = CDrawgis::getInstance();

//
// PHASE: FRONTCONTROLLER ROUTE
//
$dr->frontControllerRoute();


//
// PHASE: THEME ENGINE RENDER
//
$dr->themeEngineRender();


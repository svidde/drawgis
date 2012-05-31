<?php

/**
* 
* configuration
*
**/

 #error reporting
error_reporting(-1);
ini_set('display_errors', 1);


 #define session name
$dr->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);


 #define server timezone
$dr->config['timezone'] = 'Europe/Stockholm';


 #define internal character encoding
$dr->config['character_encoding'] = 'UTF-8';


 #define language
$dr->config['language'] = 'en';


 #set a base url
$dr->config['base_url'] = null;


 #Set database(s)'
$dr->config['database'][0]['dsn'] = 'sqlite:' . DRAWGIS_SITE_PATH . '/data/.ht.sqlite';


# session key
$dr->config['session_key']  = 'drawgis';


$dr->config['hashing_algorithm'] = 'sha1salt';


$dr->config['create_new_users'] = true;


 #Set what to show as debug or developer information in the get_debug() theme helper.
$dr->config['debug']['drawgis'] = false;
$dr->config['debug']['db-num-queries'] = true;
$dr->config['debug']['db-queries'] = true;



/**
* What type of urls should be used?
* 
* default      = 0      => index.php/controller/method/arg1/arg2/arg3
* clean        = 1      => controller/method/arg1/arg2/arg3
* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
*/
$dr->config['url_type'] = 1;



/**
* Define the controllers, their classname and enable/disable them.
*
* The array-key is matched against the url, for example: 
* the url 'developer/dump' would instantiate the controller with the key "developer", that is 
* CCDeveloper and call the method "dump" in that class. This process is managed in:
* $dr->FrontControllerRoute();
* which is called in the frontcontroller phase from index.php.
*/
$dr->config['controllers'] = array(
  'index'     => array('enabled' => true,'class' => 'CCIndex'),
  'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
  'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
  'user'      => array('enabled' => true,'class' => 'CCUser'),
  'acp'       => array('enabled' => true,'class' => 'CCAdminControlPanel'),
  'content'   => array('enabled' => true,'class' => 'CCContent'),
  'page'      => array('enabled' => true,'class' => 'CCPage'),
  'blog'      => array('enabled' => true,'class' => 'CCBlog'),
  'theme'     => array('enabled' => true,'class' => 'CCTheme'),
  'module'    => array('enabled' => true,'class' => 'CCModules'),
  'my'        => array('enabled' => true,'class' => 'CCMycontroller'),
  'forum'     => array('enabled' => true,'class' => 'CCForum'),
 );


$dr->config['routing'] = array(
  'home' => array('enabled' => true, 'url' => 'index/index'),
);

$dr->config['menus'] = array(
  'navbar' => array(
    'home'      => array('label'=>'Home', 'url'=>''),
    'modules'   => array('label'=>'Modules', 'url'=>'module'),
    'content'   => array('label'=>'Content', 'url'=>'content'),
    'guestbook' => array('label'=>'Guestbook', 'url'=>'guestbook'),
    'blog'      => array('label'=>'Blog', 'url'=>'blog'),
  ),
  'my-navbar' => array(
    'home'      => array('label'=>'About Me', 'url'=>'my'),
    'blog'      => array('label'=>'My Blog', 'url'=>'my/blog'),
    'guestbook' => array('label'=>'Guestbook', 'url'=>'my/guestbook'),
    'module' => array('label'=>'Module', 'url'=>'index/index'),
    'forum' => array('label'=>'Forum', 'url'=>'forum/index'),
  ),
);




$dr->config['theme'] = array(
	'path' => 'application/themes/myOwnTheme',
	'parent' => 'themes/grid',
	'stylesheet' 	=> 'style.css',
	'template_file'   => 'default.tpl.php',   // Default template file, else use default.tpl.php
  // A list of valid theme regions
  'regions' => array('navbar','flash','featured-first','featured-middle','featured-last',
    'primary','sidebar','triptych-first','triptych-middle','triptych-last',
    'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
    'footer',
  ),
  'menu_to_region' => array('my-navbar'=>'navbar'),
  'data' => array (
  	'header' => '<h1>Drawgis</h1>',
	'footer' => '<p>Ett arbete av Sanna Widell</h1>',
	'slogan' => 'En hyllning till Sigward',
	'favicon' => ('logo_80x80.png'),
	'logo' => ('logo_80x80.png'),
	'logo_width'   => 80,
	'logo_height'  => 80,
));




  

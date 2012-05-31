<?php
/**
 * Bootstrapping, setting up and loading the core.
 *
 * @package LydiaCore
 */

/**
 * Enable auto-load of class declarations.
 */
function autoload($aClassName) {
  $classFile = "{$aClassName}/{$aClassName}.php";
  $path = DRAWGIS_INSTALL_PATH . '/system/';
  $file1 = DRAWGIS_SITE_PATH . '/system/' .$classFile;
  $file2 = $path . $classFile;
  $file3 = $path . 'Controller/' . $classFile;
  $file4 = $path . 'Model/' . $classFile;
  $file5 = $path . 'Class/' . $classFile;
  $file6 = $path . 'Interface/' . $classFile;
  $file7 = DRAWGIS_INSTALL_PATH . '/application/src/' . $classFile;
  
 # echo $file1."<br/>".$file2."<br/>".$file3."<br/>".$file4."<br/>".$file5."<br/>".$file6."<br/><br/>";
  
  if(is_file($file1)) 
  {
    require_once($file1);
  } 
  else if(is_file($file2)) 
  {
    require_once($file2);
  }
  else if(is_file($file3)) 
  {
    require_once($file3);
  }
  else if(is_file($file4)) 
  {
    require_once($file4);
  }
  else if(is_file($file5)) 
  {
    require_once($file5);
  }
  else if(is_file($file6)) 
  {
    require_once($file6);
  }
  else if(is_file($file7)) 
  {
    require_once($file7);
  }
}

spl_autoload_register('autoload');



function htmlent($str, $flags = ENT_COMPAT) {
	return htmlentities($str, $flags, CDrawgis::getInstance()->config['character_encoding']);
}

function exception_handler( $e )  {
	echo "Lydia: Uncaught exception: <p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString(), "</pre>";
}
set_exception_handler('exception_handler');




function getIncludeContents($filename, $vars=array()) {
  if (is_file($filename)) {
    ob_start();
    extract($vars);
    include $filename;
    return ob_get_clean();
  }
  return false;
}


function makeClickable($text) {
  return preg_replace_callback(
    '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', 
    create_function(
      '$matches',
      'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
    ),
    $text
  );
 
  
}


function bbcode2html($text) {
  $search = array( 
    '/\[b\](.*?)\[\/b\]/is', 
    '/\[i\](.*?)\[\/i\]/is', 
    '/\[u\](.*?)\[\/u\]/is', 
    '/\[img\](https?.*?)\[\/img\]/is', 
    '/\[url\](https?.*?)\[\/url\]/is', 
    '/\[url=(https?.*?)\](.*?)\[\/url\]/is' 
    );   
  $replace = array( 
    '<strong>$1</strong>', 
    '<em>$1</em>', 
    '<u>$1</u>', 
    '<img src="$1" />', 
    '<a href="$1">$1</a>', 
    '<a href="$1">$2</a>' 
    );     
  return preg_replace($search, $replace, $text);
}

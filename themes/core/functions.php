<?php


function get_debug() {
	$dr = CDrawgis::getInstance();
	$html = null;
	
	if(isset( $dr->config['debug']['db-num-queries']) && $dr->config['debug']['db-num-queries'] && isset($dr->db) ) {
		$html .= "<p>Database made " . $dr->db->getNumQueries() . " queries.</p>";
	}    
	if(isset( $dr->config['debug']['db-queries']) && $dr->config['debug']['db-queries'] && isset($dr->db) ) {
		$html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $dr->db->getQueries()) . "</pre>";
	}    
	if(isset( $dr->config['debug']['drawgis']) && $dr->config['debug']['drawgis'] ) {
		$html .= "<hr><h3>Debuginformation</h3><p>The content of CLydia:</p><pre>" . htmlent(print_r($dr, true)) . "</pre>";
	}    
  return $html;
}


#Create a url by prepending the base_url.
function base_url($url) {
  return CDrawgis::getInstance()->request->base_url . trim($url, '/');
}


function current_url() {
  return CDrawgis::getInstance()->request->current_url;
}

function theme_url($url) {
  $dr = CDrawgis::getInstance();
  return "{$dr->request->base_url}themes/{$dr->config['theme']['name']}/{$url}";
}


function render_views() {
  return CDrawgis::getInstance()->views->render();
}



#Get messages stored in flash-session.
function get_messages_from_session() {
  $messages = CDrawgis::getInstance()->session->getMessages();
  $html = null;
  if(!empty($messages)) {
    foreach($messages as $val) {
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='$class'>{$val['message']}</div>\n";
    }
  }
  return $html;
}


function create_url($urlOrController=null, $method=null, $arguments=null) {
	return CDrawgis::getInstance()->request->createUrl( $urlOrController, $method, $arguments);
}



function login_menu() {
	$dr = CDrawgis::getInstance();
	
	if($dr->user['isAuthenticated']) {
		$items = "<a href='" . create_url('user/profile') . "'><img class='gravatar' src='" . get_gravatar(20) . "' alt=''> " . $dr->user['acronym'] . "</a> ";
		if($dr->user['hasRoleAdministrator']) {
			$items .= "<a href='" . create_url('acp') . "'>acp</a> ";
		}
		$items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
	} else {
		$items = "<a href='" . create_url('user/login') . "'>login</a> ";
	}
	return "<nav>$items</nav>";
}



/**
* Get a gravatar based on the user's email.
*/
function get_gravatar($size=null) {
  return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim( CDrawgis::getInstance()->user['email']))) . '.jpg?' . ($size ? "s=$size" : null);
}


function esc($str) {
  return htmlEnt($str);
}

function filter_data($data, $filter) {
  return CMContent::filter($data, $filter);
}



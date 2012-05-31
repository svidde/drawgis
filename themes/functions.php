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
function base_url($url=null) {
  return CDrawgis::getInstance()->request->base_url . trim($url, '/');
}


function current_url() {
  return CDrawgis::getInstance()->request->current_url;
}

function theme_url($url) {
  return create_url( CDrawgis::getInstance()->themeUrl . "/{$url}");
}

function theme_parent_url($url) {
  return create_url( CDrawgis::getInstance()->themeParentUrl . "/{$url}");
}


function img_url($url) {
  return create_url( CDrawgis::getInstance()->themeImgUrl . "/{$url}");
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
		if($dr->user['hasRoleAdmin']) {
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



function hasAdmRole()
{
	$dr = CDrawgis::getInstance();
	if($dr->user['isAuthenticated'])
	{
		if($dr->user['hasRoleAdmin']) 
		{
			return true;
		}
	}
	else
	{
		return false;
	}
}

function isAuthenticated()
{
	$dr = CDrawgis::getInstance();
	if($dr->user['isAuthenticated'])
	{
		return true;
	}
	else
	{
		return false;
	}
}
function getNameOfUser()
{
	$dr = CDrawgis::getInstance();
	if($dr->user['isAuthenticated'])
	{
		return $dr->user['acronym'];
	}
}


function esc($str) {
  return htmlEnt($str);
}

function filter_data($data, $filter) {
  return CMContent::filter($data, $filter);
}

function render_views($region='default') {
  return CDrawgis::getInstance()->views->render($region);
}

function region_has_content($region='default' /*...*/)
{
	return CDrawgis::getInstance()->views->regionHasView( func_get_args() );
}

function get_tools() {
  global $ly;
  return <<<EOD
<p>Tools: 
<a href="http://validator.w3.org/check/referer">html5</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">css3</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css21">css21</a>
<a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">unicorn</a>
<a href="http://validator.w3.org/checklink?uri={$ly->request->current_url}">links</a>
<a href="http://qa-dev.w3.org/i18n-checker/index?async=false&amp;docAddr={$ly->request->current_url}">i18n</a>
<!-- <a href="link?">http-header</a> -->
<a href="http://csslint.net/">css-lint</a>
<a href="http://jslint.com/">js-lint</a>
<a href="http://jsperf.com/">js-perf</a>
<a href="http://www.workwithcolor.com/hsl-color-schemer-01.htm">colors</a>
<a href="http://dbwebb.se/style">style</a>
</p>

<p>Docs:
<a href="http://www.w3.org/2009/cheatsheet">cheatsheet</a>
<a href="http://dev.w3.org/html5/spec/spec.html">html5</a>
<a href="http://www.w3.org/TR/CSS2">css2</a>
<a href="http://www.w3.org/Style/CSS/current-work#CSS3">css3</a>
<a href="http://php.net/manual/en/index.php">php</a>
<a href="http://www.sqlite.org/lang.html">sqlite</a>
<a href="http://www.blueprintcss.org/">blueprint</a>
</p>
EOD;
}


<?php
session_start();
require_once('./includes/config.php');
require_once('./includes/skins.php');
require_once('./includes/classes.php');
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");

if(isset($_GET['a']) && isset($action[$_GET['a']])) {
	$page_name = $action[$_GET['a']];
} else {
	$page_name = 'welcome';
}

if(!isAjax()) {
	$TMPL['token_id'] = generateToken();
}

// Extra class for the content [main and sidebar]
$TMPL['content_class'] = ' content-'.$page_name;

$resultSettings = $db->query(getSettings());

// Verify whether the user imported the database or not
if($resultSettings) {
	$settings = $resultSettings->fetch_assoc();
} else {
	echo "Error: ".$db->error;
}

require_once(getLanguage(null, (!empty($_GET['lang']) ? $_GET['lang'] : $_COOKIE['lang']), null));
require_once('info.php');

// Attempt to set a custom default timezone
if($settings['time'] == 0) {
	date_default_timezone_set($settings['timezone']);
}

require_once("./sources/{$page_name}.php");

// Store the theme path and theme name into the CONF and TMPL
$TMPL['theme_path'] = $CONF['theme_path'];
$TMPL['theme_name'] = $CONF['theme_name'] = $settings['theme'];
$TMPL['theme_url'] = $CONF['theme_url'] = $CONF['theme_path'].'/'.$CONF['theme_name'];

if(isset($_SESSION['username']) && isset($_SESSION['password']) || isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
	$loggedIn = new loggedIn();
	$loggedIn->db = $db;
	$loggedIn->url = $CONF['url'];
	$loggedIn->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$loggedIn->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	$verify = $loggedIn->verify();
	
	// If the user is a moderator
	if($verify['user_group'] == 1) {
		$_SESSION['usernameAdmin'] = $loggedIn->username;
		$_SESSION['passwordAdmin'] = $loggedIn->password;
	}
}

$plugins = loadPlugins($db);

// Load the head plugins
foreach($plugins as $plugin) {
	if(array_intersect(array("8"), str_split($plugin['type']))) {
		$TMPL['styles'] .= "\n<link href=\"".$CONF['url']."/plugins/".$plugin['name']."/".$plugin['name'].".css\" rel=\"stylesheet\" type=\"text/css\">";
	}
}

foreach($plugins as $plugin) {
	if(array_intersect(array("9"), str_split($plugin['type']))) {
		$TMPL['scripts'] .= "\n<script type=\"text/javascript\" src=\"".$CONF['url']."/plugins/".$plugin['name']."/".$plugin['name'].".js\"></script>";
	}
}

$TMPL['site_url'] = $CONF['url'];

if(isAjax()) {
	echo json_encode(array('content' => PageMain(), 'title' => $TMPL['title']));
	mysqli_close($db);
	return;
}

$TMPL['content'] = PageMain();

if(!empty($verify['username'])) {
	$TMPL['menu'] = menu($verify);
	$TMPL['url_logo'] = permalink($CONF['url'].'/index.php?a=feed');
} else {
	$TMPL['menu'] = menu(false);
	$TMPL['url_logo'] = permalink($CONF['url'].'/index.php?a=welcome');
}

$TMPL['url'] = $CONF['url'];
$TMPL['footer'] = $settings['title'];
$TMPL['footer_url'] = permalink($CONF['url'].'/index.php?a=info&b=');
$TMPL['year'] = date('Y');
$TMPL['info_urls'] = info_urls();
$TMPL['powered_by'] = 'Powered by <a href="'.$url.'" target="_blank">'.$name.'</a>.';
$TMPL['language'] = getLanguage($CONF['url'], null, 1);
$TMPL['tracking_code'] = $settings['tracking_code'];
$TMPL['search_users_url'] = permalink($CONF['url'].'/index.php?a=search&q=');
$TMPL['search_tags_url'] = permalink($CONF['url'].'/index.php?a=search&tag=');
$TMPL['search_groups_url'] = permalink($CONF['url'].'/index.php?a=search&groups=');
$TMPL['search_pages_url'] = permalink($CONF['url'].'/index.php?a=search&pages=');
$LNG['search_for_people'] = "Search...";

$skin = new skin('wrapper');

echo $skin->make();

mysqli_close($db);
?>
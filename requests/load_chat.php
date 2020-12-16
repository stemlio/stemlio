<?php
include("../includes/config.php");
session_start();
if($_POST['token_id'] != $_SESSION['token_id']) {
	return false;
}
include("../includes/classes.php");
require_once(getLanguage(null, (!empty($_GET['lang']) ? $_GET['lang'] : $_COOKIE['lang']), 2));
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");

$resultSettings = $db->query(getSettings()); 
$settings = $resultSettings->fetch_assoc();

// Attempt to set a custom default timezone
if($settings['time'] == 0) {
	date_default_timezone_set($settings['timezone']);
}

// The theme complete url
$CONF['theme_url'] = $CONF['theme_path'].'/'.$settings['theme'];

if(isset($_SESSION['username']) && isset($_SESSION['password']) || isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
	$loggedIn = new loggedIn();
	$loggedIn->db = $db;
	$loggedIn->url = $CONF['url'];
	$loggedIn->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$loggedIn->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	$verify = $loggedIn->verify();
	
	// Verify if it's logged in, then send the username to the class property to determine if any buttons is shown
	if($verify['username']) {
		$feed = new feed();
		$feed->db = $db;
		$feed->url = $CONF['url'];
		$feed->username = $verify['username'];
		$feed->id = $verify['idu'];
		$feed->title = $settings['title'];
		$feed->email = $CONF['email'];
		$feed->m_per_page = $settings['mperpage'];
		$feed->censor = $settings['censor'];
		$feed->smiles = $settings['smiles'];
		$feed->time = $settings['time'];
		$feed->online_time = $settings['conline'];
		$feed->updateStatus($verify['offline']);
		$feed->plugins = loadPlugins($db);
		
		// Type 1: Check for new messages.
		if($_POST['type'] == 1) {
			echo $feed->checkChat($_POST['uid']);
		} elseif($_POST['type'] == 2) {
			$friends_chat = $friends_messages = array();
			$friends_chat = $feed->onlineUsers();
			if(!empty($_POST['friends'])) {
				$friends_messages = $feed->checkChat(explode(',', $_POST['friends']));
			}
			echo json_encode(array_merge($friends_chat, $friends_messages));
		} else {
			echo $feed->getChatMessages($_POST['uid'], $_POST['cid'], $_POST['start'], null, $_POST['for']);
		}
	}
}
mysqli_close($db);
?>
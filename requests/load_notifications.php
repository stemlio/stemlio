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

$feed = new feed();
$feed->db = $db;
$feed->url = $CONF['url'];

if(isset($_SESSION['username']) && isset($_SESSION['password']) || isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
	$loggedIn = new loggedIn();
	$loggedIn->db = $db;
	$loggedIn->url = $CONF['url'];
	$loggedIn->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$loggedIn->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	$verify = $loggedIn->verify();
	
	if($verify['username']) {
		$feed = new feed();
		$feed->db = $db;
		$feed->url = $CONF['url'];
		$feed->username = $verify['username'];
		$feed->id = $verify['idu'];
		$feed->per_page = $settings['perpage'];
		$feed->time = $settings['time'];
		$feed->censor = $settings['censor'];
		$feed->smiles = $settings['smiles'];
		
		// Allowed types
		if($_POST['filter'] == 'likes') {
			$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, $_POST['start'], 1, null, null, null, null, null, null, null, null);
		} elseif($_POST['filter'] == 'comments') {
			$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, $_POST['start'], null, 1, null, null, null, null, null, null, null);
		} elseif($_POST['filter'] == 'shared') {
			$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, $_POST['start'], null, null, 1, null, null, null, null, null, null);
		} elseif($_POST['filter'] == 'friendships') {
			$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, $_POST['start'], null, null, null, 1, null, null, null, null, null);
		} elseif($_POST['filter'] == 'chats') {
			$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, $_POST['start'], null, null, null, null, 1, null, null, null, null);
		} elseif($_POST['filter'] == 'birthdays') {
			$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, $_POST['start'], null, null, null, null, null, 1, null, null, null);
		} elseif($_POST['filter'] == 'groups') {
			$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, $_POST['start'], null, null, null, null, null, null, 1, null, null);
		} elseif($_POST['filter'] == 'pokes') {
			$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, $_POST['start'], null, null, null, null, null, null, null, 1, null);
		} elseif($_POST['filter'] == 'pages') {
			$x = $feed->checkNewNotifications($settings['nperpage'], 2, 2, $_POST['start'], null, null, null, null, null, null, null, null, 1);
		}
		echo $x;
	}
}
mysqli_close($db);
?>
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

if(!empty($_POST['id']) && !empty($_POST['start']) && !empty($_POST['cid'])) {
	if(isset($_SESSION['username']) && isset($_SESSION['password']) || isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
		$loggedIn = new loggedIn();
		$loggedIn->db = $db;
		$loggedIn->url = $CONF['url'];
		$loggedIn->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
		$loggedIn->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
		
		$verify = $loggedIn->verify();
	}
	$feed = new feed();
	$feed->db = $db;
	$feed->url = $CONF['url'];
	$feed->id = $verify['idu'];
	$feed->censor = $settings['censor'];
	$feed->smiles = $settings['smiles'];
	$feed->time = $settings['time'];
	// Verify if it's logged in, then send the username to the class property to determine if any buttons is shown
	if($verify['username']) {
		$feed->username = $verify['username'];
	}
	if($_POST['start'] == 50) {
		$feed->c_per_page = 50;
	} else {
		$feed->c_per_page = $settings['cperpage'];
	}
	$message = $feed->getMessageOwner($_POST['id']);
	echo $feed->getComments($_POST['id'], $_POST['cid'], $_POST['start'], ($feed->id == $message['uid'] ? 1 : 0));
}
mysqli_close($db);
?>
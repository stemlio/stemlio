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
	
	$feed->user = $verify;
	$feed->username = $verify['username'];
	$feed->id = $verify['idu'];
}

$feed->per_page = $settings['perpage'];
$feed->censor = $settings['censor'];
$feed->smiles = $settings['smiles'];
$feed->per_page = $settings['sperpage'];
$feed->time = $settings['time'];
$feed->c_start = 0;
$feed->profile = $_POST['profile'];
$feed->profile_data = $feed->profileData($_POST['profile']);

$result = $feed->getLikes((ctype_digit($_POST['start']) ? $_POST['start'] : 0), 1, $_POST['id'], $_POST['extra']);

echo $result;

mysqli_close($db);
?>
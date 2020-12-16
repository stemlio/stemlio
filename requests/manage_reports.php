<?php
include("../includes/config.php");
session_start();
if($_POST['token_id'] != $_SESSION['token_id']) {
	return false;
}
include("../includes/classes.php");
include(getLanguage(null, (!empty($_GET['lang']) ? $_GET['lang'] : $_COOKIE['lang']), 2));
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

if(isset($_SESSION['usernameAdmin']) && isset($_SESSION['passwordAdmin'])) {
	$loggedInAdmin = new loggedInAdmin();
	$loggedInAdmin->db = $db;
	$loggedInAdmin->url = $CONF['url'];
	$loggedInAdmin->username = $_SESSION['usernameAdmin'];
	$loggedInAdmin->password = $_SESSION['passwordAdmin'];
	$loggedIn = $loggedInAdmin->verify();

	if($loggedIn['username']) {
		$manageReports = new manageReports();
		$manageReports->db = $db;
		$manageReports->url = $CONF['url'];
		$manageReports->per_page = $settings['uperpage'];
		$manageReports->plugins = loadPlugins($db);
		
		if(isset($_POST['start'])) {
			echo $manageReports->getReports($_POST['start']);
		} elseif(isset($_POST['kind'])) {
			echo $manageReports->manageReport($_POST['id'], $_POST['type'], $_POST['post'], $_POST['kind']);
		}
	}
}
mysqli_close($db);
?>
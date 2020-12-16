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

// Remove any extra white spaces, new lines
$_POST['comment'] = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $_POST['comment']);

if(!empty($_POST['id']) && !empty($_POST['comment'])) {
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
			$feed->title = $settings['title'];
			$feed->email = $CONF['email'];
			$feed->id = $verify['idu'];
			$feed->username = $verify['username'];
			$feed->user_email = $verify['email'];
			$feed->censor = $settings['censor'];
			$feed->smiles = $settings['smiles'];
			$feed->time = $settings['time'];
			$feed->email_comment = $settings['email_comment'];

			$rand = rand();
			// If the message is not too long
			if(strlen($_POST['comment']) < $settings['message']) {
				$result = $feed->addComment($_POST['id'], $_POST['comment']);
				if($result) {
					echo $feed->getLastComment();
				} else {
					echo '<div class="message-reply-container" id="post_comment_'.$rand.'"><div class="message-reported">'.$LNG['comment_error'].' <a onclick="deleteNotification(1, \''.$rand.'\')" title="'.$LNG['close'].'"><div class="delete_btn"></div></a></div></div>';
				}
			} else {
				echo '<div class="message-reply-container" id="post_comment_'.$rand.'"><div class="message-reported">'.sprintf($LNG['comment_too_long'], $settings['message']).' <a onclick="deleteNotification(1, \''.$rand.'\')" title="'.$LNG['close'].'"><div class="delete_btn"></div></a></div></div>';
			}
		}
	}
}
mysqli_close($db);
?>
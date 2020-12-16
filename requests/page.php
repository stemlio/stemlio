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

// The theme complete url
$CONF['theme_url'] = $CONF['theme_path'].'/'.$settings['theme'];

if(isset($_POST['page']) && isset($_POST['user']) || isset($_POST['live']) && isset($_POST['value']) || isset($_POST['type'])) {
	$feed = new feed();
	$feed->db = $db;
	$feed->url = $CONF['url'];
	$feed->per_page = $settings['sperpage'];
	if($_POST['type'] == 1) {
		echo $feed->getPages($_POST['page'], $_POST['user']);
		return;
	} elseif($_POST['type'] == 2) {
		if($_POST['profile']) {
			$feed->profile_data = $feed->profileData(null, $_POST['profile']);
			
			// Check for permissions
			$friendship = $feed->verifyFriendship($feed->id, $feed->profile_data['idu']);
			if(!$feed->profile_data['public'] && !isset($_SESSION['usernameAdmin']) && !isset($_SESSION['passwordAdmin'])) {	
				if($feed->profile_data['private'] == 2 && $friendship['status'] !== '1' || $feed->profile_data['private'] == 1 || $feed->getBlocked($feed->profile_data['idu'], 2)) {
					return false;
				}
			}
			
			echo $feed->getPages($_POST['page'], null, $feed->profile_data['idu']);
		} else {
			$feed->per_page = $settings['uperpage'];
			echo $feed->getPages($_POST['page'], null);
		}
		return;
	} elseif(isset($_POST['live'])) {
		$feed->per_page = 4;
		if(empty($settings['pages'])) {
			echo '<div class="search-content"><div class="search-results"><div class="notification-inner"><strong>'.$LNG['view_all_results'].'</strong> <a onclick="manageResults(0)" title="'.$LNG['close_results'].'"><div class="delete_btn"></div></a></div><div class="message-inner">'.$LNG['no_results'].'</div>';
		} else {
			echo $feed->getPages(0, substr($_POST['value'], 1), 1);
		}
		return;
	} else {
		if(isset($_SESSION['username']) && isset($_SESSION['password']) || isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
			$loggedIn = new loggedIn();
			$loggedIn->db = $db;
			$loggedIn->url = $CONF['url'];
			$loggedIn->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
			$loggedIn->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
			
			$verify = $loggedIn->verify();

			if($verify['username']) {
				$feed->title = $settings['title'];
				$feed->username = $verify['username'];
				$feed->id = $verify['idu'];
				$feed->profile = $_POST['profile'];
				$feed->email = $CONF['email'];
				$feed->profile_data = $feed->profileData(null, $_POST['id']);
				$feed->email_page_invite = $settings['email_page_invite'];
				$feed->s_per_page = $settings['sperpage'];
		
				$feed->page_data = $feed->pageData(null, $_POST['page']);

				if(!$feed->page_data['id']) {
					return false;
				}
				
				$feed->invitePage(1, $_POST['user']);
			}
		}
	}
}
mysqli_close($db);
?>
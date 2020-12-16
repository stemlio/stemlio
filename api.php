<?php
header('Content-Type: text/plain; charset=utf-8;');
session_start();
require_once('./includes/config.php');
require_once('./includes/classes.php');
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");
$apiVersion = 1.3;

// Allowed $_GET['t'] values 
$types = array('u', 'm');

echo '{"apiVersion":"'.$apiVersion.'", "data": ';
if(in_array($_GET['t'], $types)) {
	if($_GET['t'] == 'u') {
		$result = getUser($_GET['q']);
		
		// Output the result
		if(!empty($result['id'])) {
			$row['image'] = $CONF['url'].'/thumb.php?src='.$row['image'].'&t=a&w=112&h=112';
			$row['cover'] = $CONF['url'].'/thumb.php?src='.$row['cover'].'&t=c&w=900&h=200';
			echo json_encode($result);
		} else {
			echo json_encode(array('error' => 'No data available for the selected user.'));
		}
	} elseif($_GET['t'] == 'm') {
		$user = getUser($_GET['q']);
		$result = $db->query(sprintf("SELECT `id`, `uid` as `by`, `message`, `type`, `time`, `likes` FROM `messages` WHERE `uid` = '%s' AND `public` = '1' ORDER BY `id` DESC LIMIT 0, 20", $db->real_escape_string($user['id'])));
		
		$rows = array();
		// Store the result
		while($row = $result->fetch_assoc()) {
			$rows[]	= $row; 
		}
		
		// Output the result
		if(!empty($rows)) {
			echo json_encode($rows);
		} else {
			echo json_encode(array('error' => 'No messages available'));
		}
	}
} else {
	echo json_encode(array('error' => 'You need to specify a valid \'t\' parameter'));
}
echo '}';

function getUser($username) {
	global $db;
	$sql = $db->query(sprintf("SELECT `idu` as `id`, `username`, `first_name`, `last_name`, `website`, `country`, `location` as `city`, `address`, `image`, `cover`, `verified`, `private` FROM `users` WHERE `username` = '%s'", $db->real_escape_string($username)));
	$user = $sql->fetch_assoc();
	// Return if the user is public
	if(!$user['private']) {
		unset($user['private']);
		return $user;
	}
}
?>
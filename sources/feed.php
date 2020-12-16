<?php
function PageMain() {
	global $TMPL, $LNG, $CONF, $db, $loggedIn, $settings, $plugins;
		
	if(isset($_SESSION['username']) && isset($_SESSION['password']) || isset($_COOKIE['username']) && isset($_COOKIE['password'])) {	
		$verify = $loggedIn->verify();
		
		if(empty($verify['username'])) {
			// If fake cookies are set, or they are set wrong, delete everything and redirect to home-page
			$loggedIn->logOut();
			header("Location: ".$CONF['url']."/index.php?a=welcome");
		} else {
			// Start displaying the Feed
			
			$feed = new feed();
			$feed->db = $db;
			$feed->url = $CONF['url'];
			$feed->user = $verify;
			$feed->id = $verify['idu'];
			$feed->username = $verify['username'];
			$feed->per_page = $settings['perpage'];
			$feed->time = $settings['time'];
			$feed->censor = $settings['censor'];
			$feed->smiles = $settings['smiles'];
			$feed->c_per_page = $settings['cperpage'];
			$feed->c_start = 0;
			$feed->l_per_post = $settings['lperpost'];
			$feed->online_time = $settings['conline'];
			$feed->registration_date = $settings['date'];
			$feed->s_per_page = 5;
			$feed->friendsArray = $feed->getFriends($verify['idu']);
			$feed->friendsCount = $feed->countFriends($feed->id, 1);
			$feed->updateStatus($verify['offline']);
			$feed->pages_limit = $settings['pages_limit'];
			$feed->groups_limit = $settings['groups_limit'];
			$feed->plugins = $plugins;
			
			$TMPL_old = $TMPL; $TMPL = array();
			$skin = new skin('shared/rows'); $rows = '';
			
			if(empty($_GET['filter'])) {
				$_GET['filter'] = '';
			}
			if(empty($_GET['tag'])) {
				$_GET['tag'] = '';
			}
			
			list($timeline, $message) = $feed->getFeed(0, $_GET['filter']);
			
			$TMPL['messages'] = $timeline;
			
			if($_SESSION['message'] == 'welcome') {
				$TMPL['messages'] .= $feed->showWelcome('welcome_feed');
				$_SESSION['message'] = '';
			}

			$rows = $skin->make();
			
			$skin = new skin('feed/sidebar'); $sidebar = '';
			
			$TMPL['editprofile'] = $feed->fetchProfileWidget($verify['username'], realName($verify['username'], $verify['first_name'], $verify['last_name']), $verify['image']);
			// Load the sidebar plugins
			foreach($plugins as $plugin) {
				if(array_intersect(array("2"), str_split($plugin['type']))) {
					$data = $feed->user; $data['site_url'] = $CONF['url']; $data['site_title'] = $settings['title']; $data['site_email'] = $CONF['email']; unset($data['password']); unset($data['salted']);
					$TMPL['plugins'] .= plugin($plugin['name'], $data, 2);
				}
			}
			if($settings['pages']) {
				$TMPL['pages'] = $feed->sidebarPages();
			}
			if($settings['groups']) {
				$TMPL['groups'] = $feed->sidebarGroups();
			}
			#$TMPL['events'] = $feed->sidebarTypes($_GET['filter']);
			#$TMPL['dates'] = $feed->sidebarDates($_GET['filter']);
			$TMPL['birthdays'] = $feed->sidebarBirthdays();
			$TMPL['friends'] = $feed->sidebarFriends(0, 0);
			$TMPL['friendsactivity'] = $feed->sidebarFriendsActivity(20, 1);
			if(count($feed->friendsArray[1]) < $feed->s_per_page) {
				$TMPL['suggestions'] = $feed->sidebarSuggestions($verify['interests']);
			}
			$TMPL['ad'] = generateAd($settings['ad2']);
			
			$sidebar = $skin->make();
			
			$skin = new skin('shared/top'); $top = '';
			$TMPL['token_input'] = generateToken($_SESSION['token_id']);
			// Load the sidebar plugins
			unset($TMPL['plugins']);
			foreach($plugins as $plugin) {
				if(array_intersect(array("e"), str_split($plugin['type']))) {
					$data = $feed->user; $data['site_url'] = $CONF['url']; $data['site_title'] = $settings['title']; $data['site_email'] = $CONF['email']; unset($data['password']); unset($data['salted']);
					$TMPL['plugins'] .= plugin($plugin['name'], $data, 3);
				}
			}
			
			$TMPL['theme_url'] = $CONF['theme_url'];
			$TMPL['private_message'] = $verify['privacy'];
			$TMPL['privacy_class'] = (($verify['privacy']) ? (($verify['privacy'] == 2) ? 'friends' : 'public') : 'private');
			$TMPL['avatar'] = $verify['image'];
			$TMPL['url'] = $CONF['url'];
			
			$top = $skin->make();
			
			$TMPL = $TMPL_old; unset($TMPL_old);
			$TMPL['top'] = $top;
			$TMPL['rows'] = $rows;
			$TMPL['sidebar'] = $sidebar;
		}
	} else {
		// If the session or cookies are not set, redirect to home-page
		header('Location: '.permalink($CONF['url'].'/index.php?a=welcome'));
	}
	
	if(isset($_GET['logout']) == 1) {
		if($_GET['token_id'] == $_SESSION['token_id']) {
			$loggedIn->logOut();
			// If the user is a moderator
			if($verify['user_group'] == 1) {
				$loggedInAdmin = new loggedInAdmin();
				$loggedInAdmin->logOut();
			}
			header('Location: '.permalink($CONF['url'].'/index.php?a=welcome'));
		}
	}

	$TMPL['url'] = $CONF['url'];
	$TMPL['title'] = $LNG['title_feed'].' - '.$settings['title'];

	// Load the Feed page plugins
	unset($TMPL['plugins']);
	foreach($plugins as $plugin) {
		if(array_intersect(array("5"), str_split($plugin['type']))) {
			$data = $feed->user; $data['site_url'] = $CONF['url']; $data['site_title'] = $settings['title']; $data['site_email'] = $CONF['email']; unset($data['password']); unset($data['salted']);
			$TMPL['plugins'] .= plugin($plugin['name'], $data, 0);
		}
	}
	
	$skin = new skin('shared/timeline');
	return $skin->make();
}
?>
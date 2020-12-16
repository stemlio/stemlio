<?php
function PageMain() {
	global $TMPL, $LNG, $CONF, $db, $loggedIn, $settings, $plugins;
	
	$feed = new feed();
	$feed->db = $db;
	$feed->url = $CONF['url'];
	
	if(isset($_SESSION['username']) && isset($_SESSION['password']) || isset($_COOKIE['username']) && isset($_COOKIE['password'])) {	
		$verify = $loggedIn->verify();
		
		if($verify['username']) {
			$feed->user = $verify;
			$feed->username = $verify['username'];
			$feed->id = $verify['idu'];
		}
	}

	$feed->per_page = $settings['perpage'];
	$feed->time = $settings['time'];
	$feed->censor = $settings['censor'];
	$feed->smiles = $settings['smiles'];
	$feed->c_per_page = $settings['cperpage'];
	$feed->c_start = 0;
	$feed->l_per_post = $settings['lperpost'];
	$feed->plugins = $plugins;
	
	$TMPL_old = $TMPL; $TMPL = array();
	$skin = new skin('shared/rows'); $rows = '';
	
	if(empty($_GET['filter'])) {
		$_GET['filter'] = '';
	}
	if(empty($settings['groups'])) {
		$_GET['groups'] = '';
	}
	if(empty($settings['pages'])) {
		$_GET['pages'] = '';
	}
	// Allowed types
	if(isset($_GET['tag'])) {
		// If the $_GET keyword is empty [hashtag]
		if($_GET['tag'] == '') {
			header("Location: ".$CONF['url']."/index.php?a=welcome");
		}
		$hashtags = $feed->getHashtags(0, $settings['sperpage'], $_GET['tag'], null, $_GET['filter']);
		$TMPL['messages'] = $hashtags[0];
	} elseif($_GET['groups']) {
		// If the $_GET keyword is empty [group]
		if($_GET['groups'] == '') {
			header("Location: ".$CONF['url']."/index.php?a=welcome");
		}
		$feed->per_page = $settings['sperpage'];
		$TMPL['messages'] = $feed->getGroups(0, $_GET['groups']);
	} elseif($_GET['pages']) {
		// If the $_GET keyword is empty [page]
		if($_GET['pages'] == '') {
			header("Location: ".$CONF['url']."/index.php?a=welcome");
		}
		$feed->per_page = $settings['sperpage'];
		$TMPL['messages'] = $feed->getPages(0, $_GET['pages']);
	} else {
		// If the $_GET keyword is empty [user]
		if($_GET['q'] == '') {
			header("Location: ".$CONF['url']."/index.php?a=welcome");
		}
		$TMPL['messages'] = $feed->getSearch(0, $settings['sperpage'], $_GET['q'], $_GET['filter'], $_GET['age']);
	}
	$rows = $skin->make();
	
	$skin = new skin('search/sidebar'); $sidebar = '';

	if(isset($_GET['tag'])) {
		$TMPL['trending'] = $feed->sidebarTrending($_GET['tag'], 10);
		$TMPL['dates'] = $feed->sidebarDates($_GET['filter'], 1);
	} elseif(isset($_GET['groups'])) {
	} elseif(isset($_GET['pages'])) {
	} else {
		$TMPL['genre'] = $feed->sidebarGender($_GET['filter']);
		$TMPL['age'] = $feed->sidebarAge($_GET['age']);
	}
	$TMPL['search'] = $feed->sidebarSearch($settings);
	$TMPL['ad'] = generateAd($settings['ad6']);
	
	$sidebar = $skin->make();
	
	$TMPL = $TMPL_old; unset($TMPL_old);
	$TMPL['rows'] = $rows;
	$TMPL['sidebar'] = $sidebar;

	$TMPL['url'] = $CONF['url'];
	
	$_GET['tag'] = htmlspecialchars($_GET['tag']);
	$_GET['q'] = htmlspecialchars($_GET['q']);
	$_GET['groups'] = htmlspecialchars($_GET['groups']);
	$_GET['pages'] = htmlspecialchars($_GET['pages']);
	
	if(!empty($_GET['tag'])) {
		$TMPL['title'] = '#'.$_GET['tag'].' - '.$settings['title'];
		$current_search = '#'.$_GET['tag'];
	} elseif(!empty($_GET['groups'])) {
		$TMPL['title'] = '!'.$_GET['groups'].' - '.$settings['title'];
		$current_search = '!'.$_GET['groups'];
	} elseif(!empty($_GET['pages'])) {
		$TMPL['title'] = '@'.$_GET['pages'].' - '.$settings['title'];
		$current_search = '@'.$_GET['pages'];
	} else {
		$TMPL['title'] = $LNG['title_search'].' - '.$_GET['q'].' - '.$settings['title'];
		$current_search = $_GET['q'];
	}
	
	$TMPL['current_search'] = $current_search;
	
	$skin = new skin('shared/timeline');
	return $skin->make();
}
?>
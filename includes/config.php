<?php
error_reporting(0);

$CONF = $TMPL = array();

// The MySQL credentials
$CONF['host'] = 'localhost';
$CONF['user'] = 'u985760683_conrad';
$CONF['pass'] = 'SabrinaLou2014!';
$CONF['name'] = 'u985760683_stemlio';
$CONF['port'] = 3306;

// The Installation URL
$CONF['url'] = 'https://stemlio.com';

// The Notifications e-mail
$CONF['email'] = 'notifications@yourdomain.com';

// The themes directory
$CONF['theme_path'] = 'themes';

// The plugins directory
$CONF['plugin_path'] = 'plugins';

$action = array('admin'			=> 'admin',
				'feed'			=> 'feed',
				'settings'		=> 'settings',
				'messages'		=> 'messages',
				'post'			=> 'post',
				'recover'		=> 'recover',
				'profile'		=> 'profile',
				'notifications'	=> 'notifications',
				'search'		=> 'search',
				'group'			=> 'group',
				'page'			=> 'page',
				'info'			=> 'info'
				);
?>
-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2017 at 05:15 AM
-- Server version: 10.1.24-MariaDB
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpsocial`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(256) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99');

-- --------------------------------------------------------

--
-- Table structure for table `blocked`
--

CREATE TABLE `blocked` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `read` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `likes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `read` tinyint(4) NOT NULL,
  `cid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friendships`
--

CREATE TABLE `friendships` (
  `id` int(11) NOT NULL,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `cover` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `privacy` int(11) NOT NULL,
  `posts` int(11) NOT NULL,
  `members` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups_users`
--

CREATE TABLE `groups_users` (
  `id` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `permissions` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `info_pages`
--

CREATE TABLE `info_pages` (
  `id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `public` tinyint(4) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `info_pages`
--

INSERT INTO `info_pages` (`id`, `title`, `url`, `public`, `content`) VALUES
(1, '{$lng->developers}', 'developers', 1, 'Our API allows you to retrieve informations from our website via <strong>GET</strong> request and supports the following query parameters:\r\n					<br /><br />\r\n					<table border=\"1\" width=\"100%\">\r\n						<tr>\r\n							<td width=\"20%\" valign=\"top\">Name</td>\r\n							<td width=\"20%\" valign=\"top\">Meaning</td>\r\n							<td width=\"60%\" valign=\"top\">Description</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"20%\" valign=\"top\"><strong>t (required)</strong></td>\r\n							<td width=\"20%\" valign=\"top\">Query type.</td>\r\n							<td width=\"60%\" valign=\"top\">This parameter specify the type of the query, <strong><code>u</code></strong> is for profile informations, <strong><code>m</code></strong> is for messages informations.</td>\r\n						</tr>\r\n						<tr>\r\n							<td width=\"20%\" valign=\"top\"><strong>q (required)</strong></td>\r\n							<td width=\"20%\" valign=\"top\">Requested <strong>username</strong>.</td>\r\n							<td width=\"60%\" valign=\"top\">The <code>t</code> parameter supports two values: \r\n							<ul>\r\n								<li>u = <strong>username</strong> [returns basic profile informations containing the following]\r\n									<ul>\r\n										<li><code>id</code> = returns the user Unique Id</li>\r\n										<li><code>username</code> = returns the Username</li>\r\n										<li><code>first_name</code> = returns the First Name</li>\r\n										<li><code>last_name</code> = returns the Last Name</li></li>\r\n										<li><code>website</code> = returns the website</li></li>\r\n										<li><code>country</code> = returns the country</li></li>\r\n										<li><code>city</code> = returns the city</li></li>\r\n										<li><code>address</code> = returns the address</li></li>\r\n										<li><code>image</code> = returns the profile avatar image</li></li>\r\n										<li><code>cover</code> = returns the profile cover image</li></li>\r\n										<li><code>verified</code> = returns whether user is verified or not [possible values: 0 (not verified); 1(verified);]</li></li>\r\n									</ul>\r\n								</li>\r\n									\r\n								<li>m = <strong>username</strong> [returns a list of latest 20 message from an user containing the following]\r\n									<ul>\r\n										<li><code>id</code> = returns the Message Unique Id</li>\r\n										<li><code>by</code> = returns the User Unique Id</li>\r\n										<li><code>message</code> = returns the Message</li>\r\n										<li><code>type</code> = returns the Message Type [possible values: map; game; video; food; visited; movie; music;</li>\r\n										<li><code>time</code> = returns the date time when was published</li>\r\n										<li><code>likes</code> = returns the number of likes</li>\r\n									</ul>\r\n								</li>\r\n							</ul></td>\r\n						</tr>\r\n					</table>\r\n					<br />\r\n					<hr>\r\n					<div id=\"jump-url\"><strong>Examples of requests:</strong></div><br />\r\n					For profile information from an user:\r\n					<br />\r\n					<code class=\"api-request\">{$site_url}/api.php?t=u&q=USERNAME</code>\r\n					<br /><br />\r\n					For a list of latest 20 messages from an user:\r\n					<br />\r\n					<code class=\"api-request\">{$site_url}/api.php?t=m&q=USERNAME</code>\r\n					<hr>\r\n					An example of <strong>json</strong> decoding would be the following PHP code:\r\n					<br /><br />\r\n					<code>\r\n					<?php<br />\r\n						header(\'Content-Type: text/plain; charset=utf-8;\');\r\n						<br />\r\n						$file = file_get_contents(\"{$site_url}/api.php?t=m&q=USERNAME\");<br />\r\n						print_r(json_decode($file));<br />\r\n					?>\r\n					</code>\r\n					\r\n					<br />');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `by` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(12) NOT NULL,
  `uid` int(32) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(16) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `value` varchar(1024) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `group` int(11) NOT NULL DEFAULT '0',
  `page` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `public` int(11) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `child` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL,
  `read` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `by` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `category` int(3) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cover` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE `plugins` (
  `id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(12) NOT NULL,
  `post` varchar(11) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL,
  `by` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `title` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `theme` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `perpage` int(11) NOT NULL,
  `censor` varchar(2048) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `captcha` int(11) NOT NULL,
  `intervalm` int(11) NOT NULL,
  `intervaln` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `message` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `format` varchar(256) NOT NULL,
  `mail` int(11) NOT NULL,
  `sizemsg` int(11) NOT NULL,
  `formatmsg` varchar(256) NOT NULL,
  `cperpage` int(11) NOT NULL,
  `ilimit` int(11) NOT NULL,
  `climit` int(11) NOT NULL,
  `uperpage` int(11) NOT NULL,
  `sperpage` int(11) NOT NULL,
  `nperpage` tinyint(4) NOT NULL,
  `nperwidget` tinyint(4) NOT NULL,
  `lperpost` int(11) NOT NULL,
  `aperip` int(11) NOT NULL,
  `conline` int(4) NOT NULL,
  `ronline` tinyint(4) NOT NULL,
  `mperpage` tinyint(4) NOT NULL,
  `verified` int(11) NOT NULL,
  `chatr` int(11) NOT NULL,
  `email_activation` tinyint(4) NOT NULL,
  `email_comment` tinyint(4) NOT NULL,
  `email_like` tinyint(4) NOT NULL,
  `email_new_friend` tinyint(4) NOT NULL,
  `email_group_invite` tinyint(4) NOT NULL,
  `email_page_invite` tinyint(4) NOT NULL,
  `smiles` tinyint(4) NOT NULL,
  `permalinks` tinyint(4) NOT NULL,
  `fbapp` int(11) NOT NULL,
  `fbappid` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fbappsecret` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `smtp_email` int(11) NOT NULL,
  `smtp_host` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `smtp_port` int(11) NOT NULL,
  `smtp_auth` int(11) NOT NULL,
  `smtp_username` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `smtp_password` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email_provider` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `friends_limit` int(11) NOT NULL,
  `pages_limit` int(11) NOT NULL,
  `groups_limit` int(11) NOT NULL,
  `pages` int(11) NOT NULL,
  `groups` int(11) NOT NULL,
  `timezone` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ad1` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ad2` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ad3` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ad4` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ad5` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ad6` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ad7` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tracking_code` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`title`, `theme`, `perpage`, `censor`, `captcha`, `intervalm`, `intervaln`, `time`, `message`, `size`, `format`, `mail`, `sizemsg`, `formatmsg`, `cperpage`, `ilimit`, `climit`, `uperpage`, `sperpage`, `nperpage`, `nperwidget`, `lperpost`, `aperip`, `conline`, `ronline`, `mperpage`, `verified`, `chatr`, `email_activation`, `email_comment`, `email_like`, `email_new_friend`, `email_group_invite`, `email_page_invite`, `smiles`, `permalinks`, `fbapp`, `fbappid`, `fbappsecret`, `smtp_email`, `smtp_host`, `smtp_port`, `smtp_auth`, `smtp_username`, `smtp_password`, `language`, `email_provider`, `friends_limit`, `pages_limit`, `groups_limit`, `pages`, `groups`, `timezone`, `ad1`, `ad2`, `ad3`, `ad4`, `ad5`, `ad6`, `ad7`, `tracking_code`) VALUES
('Stemlio', 'stemlio', 10, 'word1,word2', 1, 10000, 10000, 0, 500, 1048576, 'png,jpg,gif,jpeg', 1, 1048576, 'png,jpg,gif,jpeg', 3, 9, 500, 10, 10, 100, 10, 5, 3, 600, 10, 10, 0, 3, 0, 1, 1, 1, 1, 1, 1, 0, 0, '', '', 0, '', 0, 0, '', '', 'english', '', 2000, 50, 100, 1, 1, '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idu` int(11) NOT NULL,
  `username` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `location` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `work` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `school` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `bio` varchar(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date` date NOT NULL,
  `facebook` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `twitter` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gplus` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image` varchar(128) NOT NULL DEFAULT '',
  `private` int(11) NOT NULL DEFAULT '0',
  `suspended` int(11) NOT NULL DEFAULT '0',
  `salted` varchar(256) NOT NULL DEFAULT '',
  `cover` varchar(128) NOT NULL DEFAULT '',
  `verified` int(11) NOT NULL DEFAULT '0',
  `privacy` int(11) NOT NULL DEFAULT '1',
  `gender` tinyint(4) NOT NULL DEFAULT '0',
  `interests` tinyint(4) NOT NULL DEFAULT '0',
  `online` int(11) NOT NULL DEFAULT '0',
  `offline` tinyint(4) NOT NULL DEFAULT '0',
  `ip` varchar(45) NOT NULL DEFAULT '',
  `user_group` int(11) NOT NULL DEFAULT '0',
  `notificationl` tinyint(4) NOT NULL,
  `notificationc` tinyint(4) NOT NULL,
  `notifications` tinyint(4) NOT NULL,
  `notificationd` tinyint(4) NOT NULL,
  `notificationf` tinyint(4) NOT NULL,
  `notificationg` tinyint(4) NOT NULL,
  `notificationx` tinyint(4) NOT NULL,
  `notificationp` tinyint(4) NOT NULL,
  `email_comment` tinyint(4) NOT NULL,
  `email_like` tinyint(4) NOT NULL,
  `email_new_friend` tinyint(4) NOT NULL,
  `email_group_invite` tinyint(4) NOT NULL,
  `email_page_invite` tinyint(4) NOT NULL,
  `sound_new_notification` tinyint(4) NOT NULL,
  `sound_new_chat` tinyint(4) NOT NULL,
  `born` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blocked`
--
ALTER TABLE `blocked`
  ADD PRIMARY KEY (`id`),
  ADD KEY `block_status` (`uid`,`by`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unread_chat_messages` (`from`,`to`,`read`),
  ADD KEY `update_chat_status` (`to`,`read`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mid` (`mid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `admin_stats` (`time`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD KEY `chat_count` (`to`,`read`),
  ADD KEY `chat_notifications_AND_chat_pagination` (`cid`);

--
-- Indexes for table `friendships`
--
ALTER TABLE `friendships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verify_friendship` (`user1`,`user2`,`status`),
  ADD KEY `user1_count_friends_AND_select_friends` (`user1`,`status`,`id`),
  ADD KEY `user2_count_friends_AND_select_friends` (`user2`,`status`,`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `admin_stats` (`time`);

--
-- Indexes for table `groups_users`
--
ALTER TABLE `groups_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_user_data` (`group`,`user`),
  ADD KEY `group_members_count` (`group`,`status`,`permissions`),
  ADD KEY `group_requests_blocked` (`group`,`status`,`time`),
  ADD KEY `joined_groups` (`user`,`status`);

--
-- Indexes for table `info_pages`
--
ALTER TABLE `info_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profile_likes_count` (`by`,`type`),
  ADD KEY `verify_like` (`post`,`by`,`type`),
  ADD KEY `likes_statistics` (`post`,`type`,`time`),
  ADD KEY `admin_stats` (`time`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `public` (`public`),
  ADD KEY `type` (`type`),
  ADD KEY `uid` (`uid`),
  ADD KEY `group` (`group`),
  ADD KEY `page` (`page`),
  ADD KEY `time` (`time`),
  ADD KEY `news_feed` (`uid`,`group`,`page`,`public`),
  ADD KEY `value` (`value`(255));

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_activity` (`from`,`type`),
  ADD KEY `notifications_widget` (`to`,`type`,`read`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_page` (`name`),
  ADD KEY `page_owner` (`by`),
  ADD KEY `admin_stats` (`time`);

--
-- Indexes for table `plugins`
--
ALTER TABLE `plugins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state` (`state`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idu`),
  ADD KEY `username` (`username`),
  ADD KEY `gender` (`gender`),
  ADD KEY `admin_manage_user_moderators` (`user_group`),
  ADD KEY `admin_manage_user_verified` (`verified`),
  ADD KEY `admin_manage_user_suspended` (`suspended`),
  ADD KEY `user_active` (`idu`,`suspended`),
  ADD KEY `admin_stats_registered` (`date`),
  ADD KEY `admin_stats_online` (`online`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `blocked`
--
ALTER TABLE `blocked`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `friendships`
--
ALTER TABLE `friendships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups_users`
--
ALTER TABLE `groups_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `info_pages`
--
ALTER TABLE `info_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `plugins`
--
ALTER TABLE `plugins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idu` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

header('Content-Type: text/xml; charset=utf-8');
	echo '<' . '?xml version="1.0" encoding="UTF-8"?' . '>';
	echo '<userdata>';
	echo '<username><![CDATA[' . $username . ']]></username>';
	echo '<regdate><![CDATA[' . $username . ']]></regdate>';
	echo '<posts><![CDATA[' . $row['user_posts'] . ']]></posts>';
	echo '<from><![CDATA[' . (!empty($row['user_from']) ? $row['user_from'] : $user->lang['NA']) . ']]></from>';
	echo '<lastvisit><![CDATA[' . (!empty($row['user_lastvisit']) ? $user->format_date($row['user_lastvisit']) : $user->lang['NA']) . ']]></lastvisit>';
	echo '<website><![CDATA[' . (!empty($row['user_website']) ? $row['user_website'] : $user->lang['NA']) . ']]></website>';
	echo '<avatar><![CDATA[' . (!empty($avatar) ? $avatar : '<img src="' . $theme_path . '/images/no_avatar.gif" alt="" />') . ']]></avatar>';
	echo '<rank><![CDATA[' . (!empty($rank_title) ? $rank_title : $user->lang['NA']) . ']]></rank>';
	echo '</userdata>';
<?php
/*
Plugin Name: Shared Users Reloaded
Plugin URI: http://smwphosting.com/extend/shared-users-reloaded
Description: Uses the user table from another Wordpress install.
Version: 1.0.1
Author: Seth Carstens
Author URI: http://smwphosting.com/
*/

/*  Copyright 2010

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
	
*/

include_once(WP_PLUGIN_DIR . '/shared-users-reloaded/shared-users-functions.php');

// Activation hook
register_activation_hook(__FILE__, 'shared_users_activate');
function shared_users_activate() {
  global $table_prefix;
  add_option('shared_users_prefix', $table_prefix, '', 'no');
  add_option('shared_users_import_existing', 'no', '', 'no');
}

// Function for setting up (registering) plugin only scripts
add_action('admin_init', 'shared_users_admin_init');
function shared_users_admin_init() {
	wp_register_script('shared-users-reloaded-js', WP_PLUGIN_URL . '/shared-users-reloaded/shared-users-reloaded.js');
	wp_register_script('shared-users-reloaded-jqui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js');
}

// Function for setting up options page
add_action('admin_menu', 'shared_users_options');
function shared_users_options() {
	$page = add_submenu_page('users.php', 'Shared Users Options', 'Shared Users', 10, __FILE__, 'shared_users_options_page');
	/* Using registered $page handle to hook script load */
	add_action('admin_print_scripts-' . $page, 'shared_users_admin_scripts');
}

//only load the javascript on pages associated with this plugin's admin pages
function shared_users_admin_scripts() {
	wp_enqueue_script('shared-users-reloaded-jqui');
	wp_enqueue_script('shared-users-reloaded-js');
	
}


// Function for displaying contents of options page
function shared_users_options_page() {
	global $wpdb, $table_prefix;
	$updateExecuted = false;
  	
	//detect if form was submitted from Users Table Options
	if($_POST['action'] == 'users-table-update') {
		if(get_option('shared_users_prefix') != $_POST['shared_users_prefix']) {
			update_option('shared_users_prefix', $_POST['shared_users_prefix']); 
			$updateExecuted = true;
		}
		if(get_option('shared_users_import_existing') != $_POST['shared_users_import_existing']) {
			update_option('shared_users_import_existing', $_POST['shared_users_import_existing']); 
			$updateExecuted = true;
		}
	}//end if action updated

	//detect if form was submitted from Users Security Options
	elseif ($_POST['action'] == 'shared-users-security-update'){
		foreach($_POST as $key=>$value) {
			if(strstr($key, 'capabilities') ) {
				if($value != "")
					update_user_meta($_POST['user_id'], $key, buildRoleMetaValue($value));
				else
					delete_user_meta($_POST['user_id'], $key);
				$updateExecuted = true;
			}
		}
	}
	
	//Actual HTML / PHP Contents of the Admin Panel.
	include_once(WP_PLUGIN_DIR . '/shared-users-reloaded/shared-users-panel.php');
}

// Function called when prefix is changed
// Every level 10 user in the foreign installation should also be a level 10 user for the local installation
add_action('update_option_shared_users_prefix', 'shared_users_change_prefix', 10, 2);
function shared_users_change_prefix($old, $new) {
  global $wpdb;
  
  // Foreign usermeta table name
  $usermeta = $wpdb->escape($new . "usermeta");
  // Foreign user_level meta_key name
  $f_user_level = $wpdb->escape($new . "user_level");
  // Local user_level meta_key name
  $l_user_level = $wpdb->escape($wpdb->prefix . "user_level");
  // Foreign capabilities meta_key name
  $f_capabilities = $wpdb->escape($new . "capabilities");
  // Local capabilities meta_key name
  $l_capabilities = $wpdb->escape($wpdb->prefix . "capabilities");
  
  // Get a list of level 10 users in the foreign database
  $foreign10s = $wpdb->get_col("SELECT user_id FROM $usermeta WHERE meta_key = '$f_user_level' AND meta_value = 10");
  	if(get_option('shared_users_import_existing') == 'yes') {

		foreach ($foreign10s as $userid) {
			// Check if 'user_level' and 'capabilities' has already been copied
			$level = $wpdb->get_var("SELECT meta_value FROM $usermeta WHERE user_id = $userid AND meta_key = '$l_user_level'");
			$capabilities = $wpdb->get_var("SELECT meta_value FROM $usermeta WHERE user_id = $userid AND meta_key = '$f_capabilities'");
			if ($level === null) {
				// Not copied, let's insert
				$wpdb->query("INSERT INTO $usermeta SET user_id = $userid, meta_key = '$l_user_level', meta_value = 10");
				$wpdb->query("INSERT INTO $usermeta SET user_id = $userid, meta_key = '$l_capabilities', meta_value = '$capabilities'");
			} else {
				// Already copied, let's update
				$wpdb->query("UPDATE $usermeta SET meta_value = 10 WHERE user_id = $userid AND meta_key = '$l_user_level'");
				$wpdb->query("UPDATE $usermeta SET meta_value = '$capabilities' WHERE user_id = $userid AND meta_key = '$l_capabilities'");
			}
		}//end foreach

	}// end if import set to 'yes'
}

// On every startup, switch users and usermeta tables to ALL USERS TABLE prefix
add_action('plugins_loaded', 'shared_users_patch_wpdb');
function shared_users_patch_wpdb() {
  global $wpdb;
  $prefix = get_option('shared_users_prefix');
  if ($prefix != "") {
    $wpdb->users = $prefix . "users";
    $wpdb->usermeta = $prefix . "usermeta";
  }
}

//Load Custom Admin CSS for Admin Pages (jquery UI theme)
function shared_users_scripts_loader() {
    $url = WP_CONTENT_URL . '/plugins/shared-users-reloaded/wp-admin.css';
    echo '<link rel="stylesheet" type="text/css" href="' . $url . '" />';
}

add_action('admin_head', 'shared_users_scripts_loader');


?>
<?php
/***********************************************/
/* Administration Panel: Users -> Shared Users */
/***********************************************/

  if ($updateExecuted) echo '<div id="message" class="updated fade"><p><strong>Shared Users settings updated.</strong><br /></p></div>';


  // The current prefix
  $current_prefix = get_option('shared_users_prefix');
  
  // The local prefix
  $local_prefix = $table_prefix;
  
  // All possible prefixes
  $user_tables = $wpdb->get_col("SHOW TABLES LIKE '%users'");
  foreach ($user_tables as $table) {
    $prefixes[] = str_replace("users", "", $table);
  }
  
  // Admin Panel Contents
  echo '<div class="wrap">';
  echo '<div class="icon32" id="icon-users"><br></div><h2>Shared Users Options</h2>';
  echo '<form method="post" action="">';
  wp_nonce_field('update-options');
  echo "<table>";
  echo "<td><label>Share users from this blog</label></td><td><select name=\"shared_users_prefix\">";
  foreach ($prefixes as $p) {
    $options = $p . "options";
    $blogname = $wpdb->get_var("SELECT option_value FROM $options WHERE option_name = 'blogname'");
    echo "<option value=\"$p\"", ($p == $current_prefix) ? " selected=\"selected\"" : "", ">$blogname [" . $p . "users]", ($p == $local_prefix) ? " (turns off user sharing)" : "", "</option>";
  }
  echo "</select></td>";
  echo "</tr>";
 
  echo "<td><label>Import Users from Original Blog Table [" . $local_prefix . "users]</label><td><select name=\"shared_users_import_existing\">";
  echo '<option value="no" ', (get_option('shared_users_import_existing') == 'no') ? " selected=\"selected\"" : "", '>No</option>';
  echo '<option value="yes" ', (get_option('shared_users_import_existing') == 'yes') ? " selected=\"selected\"" : "", '>Yes</option>';
  echo "</select></td>";
  echo "</tr>";
  echo "</table>";
  
  echo "<input type=\"hidden\" name=\"action\" value=\"users-table-update\" />";
  echo "<input type=\"hidden\" name=\"page_options\" value=\"shared_users_prefix\" />";
  echo "<p class=\"submit\"><input type=\"submit\" name=\"Submit\" value=\"", __('Save Changes'), "\" class=\"button\" /></p>";
  echo "</form>";
  echo "</div>";
	
	echo '<div id="memberWrapper" style="margin-right: 100px">';
	echo '<div id="accordion">';
	/*	You could sort them by: ID, user_login, user_nicename, user_email, user_url, user_registered */
	$szSort = "user_registered";
	
	// Now we build the custom query to get the ID of the users.
	$aUsersID = $wpdb->get_col( $wpdb->prepare("SELECT $wpdb->users.ID FROM $wpdb->users ORDER BY %s ASC", $szSort ));
	
	//Once we have the IDs we loop through them with a Foreach statement.
	foreach ( $aUsersID as $iUserID ) {
		//We use get_userdata() function with each ID.
		$user = get_userdata( $iUserID );		
		
		//Here we finally print the details wanted.
		echo '<h3><a href="#' . $user->user_login . '">' . ucwords(strtolower($user->first_name . ' ' . $user->last_name)) . ' (' . $user->user_login . ')</a></h3>';
		//Pass through all the roles and set selected roles to dropdowns.
		echo '<form method="post" actions="">';
		wp_nonce_field('update-options');
		echo '<div><fieldset style="margin-left: 10px; padding: 5px; border: 1px dashed #CCC; ">';
		foreach ($prefixes as $p) {	
			$options = $p . "options";
			$blogname = $wpdb->get_var("SELECT option_value FROM $options WHERE option_name = 'blogname'");
			echo '<label for="" style="float:left; font-weight:bold; margin-right:0.5em; padding-top:0.2em; text-align:right; width:400px;">' . $blogname . ' (' . $p . '):</label><select name="' . $p . 'capabilities"><option value="">None</option>';
			$capabilities = $user->{$p . 'capabilities'};
			if ( !isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
				print_r($wp_roles);
			}
			foreach ( $wp_roles->role_names as $role => $name ) :
				if(is_array($capabilities)) {
					if (array_key_exists( $role, $capabilities ))
						echo '<option value="'. $role . '" selected="selected">' . $role . '</option>';
					else
						echo '<option value="'. $role . '">' . $role . '</option>';
				}
				else {
					if (strstr($role, $capabilities))
						echo '<option value="'. $role . '" selected="selected">' . $role . '</option>';
					else
						echo '<option value="'. $role . '">' . $role . '</option>';
				}
			endforeach;
			echo '</select><br style="clear: both;" />';
		} //end foreach prefixes
		
		echo "<input type=\"hidden\" name=\"user_id\" value=\"" . $iUserID . "\" />";
		echo "<input type=\"hidden\" name=\"action\" value=\"shared-users-security-update\" />";
		echo "<input type=\"hidden\" name=\"page_options\" value=\"shared_users_reloaded\" />";
		echo '<label for="" style="float:left; font-weight:bold; margin-right:0.5em; padding-top:0.2em; text-align:right; width:400px;">&nbsp;</label><input type="submit" class="button" value="' . __('Update User Roles') . '" name="Submit">';
		echo '</fieldset></div></form>';
		 //The strtolower and ucwords part is to be sure the full names will all be capitalized.
	}// end the users loop.     
    echo '</div><br /><br />';
	echo '</div>'; //close memberWrapper Div
	//Uncomment code below to debug form posted variables
	//var_dump($_POST);
?>
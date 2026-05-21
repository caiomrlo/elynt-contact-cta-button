<?php

if (!defined('WPINC')) {
	exit;
}

/**
 * Fired during plugin activation.
 */
class ELYNCOCT_Chat_Button_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE `{$wpdb->prefix}elyncoct_chat_buttons` (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			type varchar(50) NOT NULL,
			status varchar(50) NOT NULL DEFAULT 'active',
			options longtext NOT NULL,
			created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

}

<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://developer.wordpress.org/plugins/the-basics/uninstall-methods/
 * @since      1.0.0
 *
 * @package    ELYNT_Chat_Button
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/** @var wpdb $wpdb */
global $wpdb;

/**
 * Cleanup database data.
 * This removes all chat buttons and configurations when the plugin is deleted.
 */
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}ELYNT_chat_buttons`" );

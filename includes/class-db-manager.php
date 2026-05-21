<?php

if (!defined('WPINC')) {
	exit;
}

/**
 * Handles all database operations for the buttons.
 */
class ELYNT_Chat_Button_DB_Manager
{

	private $table_name;

	public function __construct()
	{
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'ELYNT_chat_buttons';
	}

	public function get_all_buttons()
	{
		global $wpdb;

		$cache_key   = 'all_buttons';
		$cache_group = 'elynt_chat_buttons';
		$results     = wp_cache_get($cache_key, $cache_group);

		if (false === $results) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$results = $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY id DESC", ARRAY_A);

			if (!empty($results)) {
				foreach ($results as &$row) {
					$row['options'] = json_decode($row['options'], true);
				}
			}
			wp_cache_set($cache_key, $results, $cache_group);
		}

		return $results;
	}

	public function get_active_fixed_buttons()
	{
		global $wpdb;

		$cache_key   = 'active_fixed_buttons';
		$cache_group = 'elynt_chat_buttons';
		$results     = wp_cache_get($cache_key, $cache_group);

		if (false === $results) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE status = %s AND type = %s", 'active', 'fixed'), ARRAY_A);

			if (!empty($results)) {
				foreach ($results as &$row) {
					$row['options'] = json_decode($row['options'], true);
				}
			}
			wp_cache_set($cache_key, $results, $cache_group);
		}

		return $results;
	}

	public function get_button($id)
	{
		global $wpdb;

		$cache_key   = 'button_' . $id;
		$cache_group = 'elynt_chat_buttons';
		$row         = wp_cache_get($cache_key, $cache_group);

		if (false === $row) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id), ARRAY_A);

			if ($row) {
				$row['options'] = json_decode($row['options'], true);
			}
			wp_cache_set($cache_key, $row, $cache_group);
		}

		return $row;
	}

	public function create_button($data)
	{
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->insert(
			$this->table_name,
			array(
				'name' => sanitize_text_field($data['name']),
				'type' => sanitize_text_field($data['type']),
				'status' => sanitize_text_field($data['status']),
				'options' => wp_json_encode($data['options']),
				'created_at' => current_time('mysql')
			),
			array('%s', '%s', '%s', '%s', '%s')
		);

		$insert_id = $wpdb->insert_id;

		if ($insert_id) {
			wp_cache_delete('all_buttons', 'elynt_chat_buttons');
			wp_cache_delete('active_fixed_buttons', 'elynt_chat_buttons');
		}

		return $insert_id;
	}

	public function update_button($id, $data)
	{
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->update(
			$this->table_name,
			array(
				'name' => sanitize_text_field($data['name']),
				'type' => sanitize_text_field($data['type']),
				'status' => sanitize_text_field($data['status']),
				'options' => wp_json_encode($data['options']),
			),
			array('id' => $id),
			array('%s', '%s', '%s', '%s'),
			array('%d')
		);

		wp_cache_delete('all_buttons', 'elynt_chat_buttons');
		wp_cache_delete('active_fixed_buttons', 'elynt_chat_buttons');
		wp_cache_delete('button_' . $id, 'elynt_chat_buttons');

		return true;
	}

	public function delete_button($id)
	{
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->delete(
			$this->table_name,
			array('id' => $id),
			array('%d')
		);

		wp_cache_delete('all_buttons', 'elynt_chat_buttons');
		wp_cache_delete('active_fixed_buttons', 'elynt_chat_buttons');
		wp_cache_delete('button_' . $id, 'elynt_chat_buttons');

		return true;
	}
}

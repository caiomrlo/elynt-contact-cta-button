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
		$results = $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY id DESC", ARRAY_A);

		if (!empty($results)) {
			foreach ($results as &$row) {
				$row['options'] = json_decode($row['options'], true);
			}
		}

		return $results;
	}

	public function get_active_fixed_buttons()
	{
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE status = %s AND type = %s", 'active', 'fixed'), ARRAY_A);

		if (!empty($results)) {
			foreach ($results as &$row) {
				$row['options'] = json_decode($row['options'], true);
			}
		}

		return $results;
	}

	public function get_button($id)
	{
		global $wpdb;
		$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id), ARRAY_A);

		if ($row) {
			$row['options'] = json_decode($row['options'], true);
		}

		return $row;
	}

	public function create_button($data)
	{
		global $wpdb;

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

		return $wpdb->insert_id;
	}

	public function update_button($id, $data)
	{
		global $wpdb;

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

		return true;
	}

	public function delete_button($id)
	{
		global $wpdb;

		$wpdb->delete(
			$this->table_name,
			array('id' => $id),
			array('%d')
		);

		return true;
	}
}

<?php

if (!defined('WPINC')) {
	exit;
}
class ELYNT_Chat_Button_Ajax_Handler
{

	private $db;

	public function __construct()
	{
		$this->db = new ELYNT_Chat_Button_DB_Manager();
	}

	private function verify_request()
	{
		if (!isset($_POST['nonce']) || !wp_verify_nonce(wp_unslash($_POST['nonce']), 'ecb_admin_nonce')) {
			wp_send_json_error(array('message' => 'Invalid security token.'));
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('message' => 'Unauthorized access.'));
		}
	}

	public function ajax_get_list()
	{
		$this->verify_request();

		$buttons = $this->db->get_all_buttons();

		ob_start();
		require ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'admin/views/partials/list.php';
		$html = ob_get_clean();

		wp_send_json_success(array('html' => $html));
	}

	public function ajax_get_form()
	{
		$this->verify_request();

		$button_id = isset($_POST['id']) ? intval(wp_unslash($_POST['id'])) : 0;
		$button = null;

		if ($button_id > 0) {
			$button = $this->db->get_button($button_id);
		}

		ob_start();
		require ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'admin/views/partials/form.php';
		$html = ob_get_clean();

		wp_send_json_success(array('html' => $html));
	}

	public function ajax_save_button()
	{
		$this->verify_request();

		$button_id = isset($_POST['id']) ? intval(wp_unslash($_POST['id'])) : 0;

		$data = array(
			'name' => isset($_POST['button_name']) ? sanitize_text_field(wp_unslash($_POST['button_name'])) : 'Unnamed',
			'type' => isset($_POST['button_type']) ? sanitize_text_field(wp_unslash($_POST['button_type'])) : 'fixed',
			'status' => isset($_POST['button_status']) ? sanitize_text_field(wp_unslash($_POST['button_status'])) : 'active',
			'options' => array(
				'text' => isset($_POST['button_text']) ? sanitize_text_field(wp_unslash($_POST['button_text'])) : '',
				'number' => isset($_POST['whatsapp_number']) ? sanitize_text_field(wp_unslash($_POST['whatsapp_number'])) : '',
				'position' => isset($_POST['button_position']) ? sanitize_text_field(wp_unslash($_POST['button_position'])) : 'right',
				'layout' => isset($_POST['button_layout']) ? sanitize_text_field(wp_unslash($_POST['button_layout'])) : 'standard',
				'initial_message' => isset($_POST['initial_message']) ? sanitize_textarea_field(wp_unslash($_POST['initial_message'])) : '',
				'bg_color' => isset($_POST['bg_color']) ? sanitize_hex_color(wp_unslash($_POST['bg_color'])) : '#25D366',
				'text_color' => isset($_POST['text_color']) ? sanitize_hex_color(wp_unslash($_POST['text_color'])) : '#ffffff',
			)
		);

		if ($button_id > 0) {
			$this->db->update_button($button_id, $data);
			wp_send_json_success(array(
				'message' => 'Button updated successfully.',
				'id' => $button_id
			));
		} else {
			$new_id = $this->db->create_button($data);
			wp_send_json_success(array(
				'message' => 'Button created successfully.',
				'id' => $new_id
			));
		}
	}

	public function ajax_delete_button()
	{
		$this->verify_request();

		$button_id = isset($_POST['id']) ? intval(wp_unslash($_POST['id'])) : 0;
		if ($button_id > 0) {
			$this->db->delete_button($button_id);
			wp_send_json_success(array('message' => 'Button deleted successfully.'));
		}

		wp_send_json_error(array('message' => 'Invalid ID.'));
	}
}

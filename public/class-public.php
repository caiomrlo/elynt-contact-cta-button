<?php

if (!defined('WPINC')) {
	exit;
}
class ELYNT_Chat_Button_Public
{

	private $plugin_name;
	private $version;
	private $db;

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->db = new ELYNT_Chat_Button_DB_Manager();
	}

	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, ELYNT_CHAT_BUTTON_PLUGIN_URL . 'public/css/public-style.css', array(), $this->version, 'all');
	}

	public function render_fixed_buttons()
	{
		$buttons = $this->db->get_active_fixed_buttons();

		if (empty($buttons)) {
			return;
		}

		foreach ($buttons as $button) {
			$this->load_button_view($button);
		}
	}

	public function render_inline_button_shortcode($atts)
	{
		$atts = shortcode_atts(array(
			'id' => 0,
		), $atts, 'ELYNT_chat_button');

		$id = intval($atts['id']);

		if ($id === 0) {
			return '';
		}

		$button = $this->db->get_button($id);

		if (!$button || $button['status'] !== 'active') {
			return '';
		}

		// Ensure we don't apply fixed positioning classes for inline buttons, although the view handles this.
		ob_start();
		$this->load_button_view($button);
		return ob_get_clean();
	}

	private function load_button_view($button)
	{
		require ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'public/views/button.php';
	}
}

<?php

if (!defined('WPINC')) {
	exit;
}
class ELYNT_Chat_Button_Admin
{

	private $plugin_name;
	private $version;

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function add_plugin_admin_menu()
	{
		add_menu_page(
			'Elynt Contact CTA Button',
			'Elynt Contact CTA Button',
			'manage_options',
			$this->plugin_name,
			array($this, 'display_plugin_setup_page'),
			$this->get_menu_icon(),
			26
		);
	}

	public function enqueue_styles($hook)
	{
		if ('toplevel_page_' . $this->plugin_name !== $hook) {
			return;
		}

		wp_enqueue_style('intl-tel-input', ELYNT_CHAT_BUTTON_PLUGIN_URL . 'admin/vendors/intl-tel-input/css/intlTelInput.min.css', array(), '28.1.0', 'all');
		wp_enqueue_style($this->plugin_name, ELYNT_CHAT_BUTTON_PLUGIN_URL . 'admin/assets/css/admin-style.css', array('intl-tel-input'), $this->version, 'all');
	}

	public function enqueue_scripts($hook)
	{
		if ('toplevel_page_' . $this->plugin_name !== $hook) {
			return;
		}

		wp_enqueue_script('intl-tel-input', ELYNT_CHAT_BUTTON_PLUGIN_URL . 'admin/vendors/intl-tel-input/js/intlTelInput.min.js', array(), '28.1.0', true);
		wp_enqueue_script($this->plugin_name, ELYNT_CHAT_BUTTON_PLUGIN_URL . 'admin/assets/js/admin-app.js', array('jquery', 'intl-tel-input'), $this->version, true);

		wp_localize_script($this->plugin_name, 'ecb_admin', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ecb_admin_nonce'),
			'utils_script' => ELYNT_CHAT_BUTTON_PLUGIN_URL . 'admin/vendors/intl-tel-input/js/utils.js'
		));
	}

	public function display_plugin_setup_page()
	{
		require_once ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'admin/views/app-container.php';
	}

	/**
	 * Returns the custom SVG menu icon from the assets file.
	 * 
	 * @return string
	 */
	private function get_menu_icon()
	{
		$icon_path = ELYNT_CHAT_BUTTON_PLUGIN_DIR . 'admin/assets/icon-base64.php';
		if (file_exists($icon_path)) {
			return include $icon_path;
		}
		return 'dashicons-format-chat'; // Fallback if file is missing
	}
}
